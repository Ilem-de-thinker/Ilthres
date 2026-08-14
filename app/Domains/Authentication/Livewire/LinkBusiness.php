<?php

namespace App\Domains\Authentication\Livewire;

use App\Domains\Business\Actions\CreateBusiness as CreateBusinessAction;
use App\Domains\Business\DTOs\BusinessData;
use App\Models\Category;
use App\Models\Plan;
use App\Models\PlatformSetting;
use App\Models\Subscription;
use HopekellDev\Paystack\Paystack;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Component;

/**
 * Link an external business website to the user's Ilthres account.
 *
 * Flow:
 * 1. User enters their external website URL.
 * 2. System verifies the URL is reachable.
 * 3. User fills in basic business details (name, type, category).
 * 4. If pricing is enabled → redirect to Paystack payment (7,500 NGN).
 *    If pricing is disabled → create business + free subscription directly.
 * 5. Onboarding is marked complete — user goes to dashboard.
 */
class LinkBusiness extends Component
{
    public string $websiteUrl = '';

    public string $verifiedUrl = '';

    public bool $urlVerified = false;

    public string $name = '';

    public string $type = 'product_store';

    public ?int $categoryId = null;

    public ?string $fetchedTitle = null;

    public ?string $errorMessage = null;

    public ?string $successMessage = null;

    public bool $isVerifying = false;

    public bool $pricingEnabled = true;

    protected function rules(): array
    {
        return [
            'websiteUrl' => ['required', 'url', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:product_store,service_business'],
            'categoryId' => ['nullable', 'exists:categories,id'],
        ];
    }

    protected $messages = [
        'websiteUrl.required' => 'Please enter your website URL.',
        'websiteUrl.url' => 'Please enter a valid URL (e.g. https://example.com).',
        'name.required' => 'Give your business a name.',
        'type.required' => 'Select whether you sell products or offer services.',
    ];

    public function mount(): void
    {
        $this->pricingEnabled = (bool) PlatformSetting::get('pricing_enabled', true);
    }

    /**
     * Verify the external website URL is reachable.
     */
    public function verifyUrl(): void
    {
        $this->errorMessage = null;
        $this->urlVerified = false;
        $this->fetchedTitle = null;

        $url = $this->normalizeUrl($this->websiteUrl);

        if (! filter_var($url, FILTER_VALIDATE_URL)) {
            $this->errorMessage = 'Please enter a valid URL (e.g. https://example.com).';

            return;
        }

        $this->isVerifying = true;

        try {
            $response = Http::timeout(15)
                ->withoutVerifying()
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                ])
                ->get($url);

            // Any response means the server is reachable (even 403/404).
            $status = $response->status();
            if ($status > 0) {
                $this->verifiedUrl = $url;
                $this->urlVerified = true;

                // Try to extract the page title for convenience.
                $body = $response->body();
                if (preg_match('/<title[^>]*>(.*?)<\/title>/is', $body, $matches)) {
                    $this->fetchedTitle = trim(html_entity_decode($matches[1]));
                    if (empty($this->name) && $this->fetchedTitle) {
                        $this->name = $this->fetchedTitle;
                    }
                }
            } else {
                $this->errorMessage = "We couldn't reach that website (HTTP {$response->status()}). Please check the URL and try again.";
            }
        } catch (ConnectionException $e) {
            $this->errorMessage = 'Connection timed out. Please make sure the URL is correct and the site is online.';
        } catch (\Throwable $e) {
            $this->errorMessage = 'We couldn\'t reach that website. Please make sure the URL is correct and the site is online.';
        } finally {
            $this->isVerifying = false;
        }
    }

    /**
     * Create the business on Ilthres with the external website URL.
     *
     * If pricing is enabled, initiates Paystack payment first.
     * If pricing is disabled, creates business + free subscription directly.
     */
    public function save(CreateBusinessAction $createBusiness): void
    {
        $this->errorMessage = null;

        if (! $this->urlVerified) {
            $this->errorMessage = 'Please verify your website URL first.';

            return;
        }

        $this->validate();

        // Store business data in session for the callback to use.
        $businessData = [
            'user_id' => auth()->id(),
            'type' => $this->type,
            'name' => $this->name,
            'slug' => Str::slug($this->name),
            'email' => auth()->user()->email,
            'category_id' => $this->categoryId,
            'website_url' => $this->verifiedUrl,
        ];

        // If pricing is disabled, create business + free subscription directly.
        if (! $this->pricingEnabled) {
            $this->createBusinessDirectly($businessData, $createBusiness);

            return;
        }

        // Pricing is enabled — initiate Paystack payment.
        $this->initiatePayment($businessData);
    }

    /**
     * Create business and free subscription without payment (pricing disabled).
     */
    private function createBusinessDirectly(array $businessData, CreateBusinessAction $createBusiness): void
    {
        try {
            $business = $createBusiness(new BusinessData(
                userId: $businessData['user_id'],
                type: $businessData['type'],
                name: $businessData['name'],
                slug: $businessData['slug'],
                description: null,
                email: $businessData['email'],
                phone: null,
                whatsapp: null,
                address: null,
                city: null,
                state: null,
                country: 'Nigeria',
                categoryId: $businessData['category_id'],
                logo: null,
                cover: null,
                websiteUrl: $businessData['website_url'],
            ));

            // Create free subscription for linked business.
            $linkedPlan = Plan::where('slug', 'linked_business')->first();
            if ($linkedPlan) {
                Subscription::create([
                    'business_id' => $business->id,
                    'plan_id' => $linkedPlan->id,
                    'price_paid' => 0,
                    'status' => 'active',
                    'starts_at' => now(),
                    'ends_at' => null,
                ]);
            }

            // Mark onboarding as complete.
            auth()->user()->forceFill(['onboarding_step' => 5, 'onboarding_completed' => true])->save();

            // Set as current business.
            session(['current_business_id' => $business->id]);

            $this->redirect(route('dashboard'), navigate: true);

        } catch (\Throwable $e) {
            $this->errorMessage = 'Something went wrong. Please try again.';
            report($e);
        }
    }

    /**
     * Initiate Paystack payment for linked business plan.
     */
    private function initiatePayment(array $businessData): void
    {
        $plan = Plan::where('slug', 'linked_business')->first();

        if (! $plan) {
            $this->errorMessage = 'Plan not found. Please try again.';

            return;
        }

        $amountInKobo = (int) ((float) $plan->price * 100);
        $user = auth()->user();

        try {
            $response = Paystack::transactions()->initialize([
                'email' => $user->email,
                'amount' => $amountInKobo,
                'callback_url' => route('link-business.paystack.callback'),
                'metadata' => [
                    'plan_id' => $plan->id,
                    'user_id' => $user->id,
                    'action' => 'link_business',
                    'business_data' => $businessData,
                ],
            ]);

            if (! ($response['status'] ?? false) || ! ($response['data']['authorization_url'] ?? null)) {
                Log::error('Paystack link business initialization failed', [
                    'user_id' => $user->id,
                    'plan_id' => $plan->id,
                    'response' => $response,
                ]);
                $this->errorMessage = 'Failed to initialize payment. Please try again.';

                return;
            }

            session([
                'link_business_authorization_url' => $response['data']['authorization_url'],
                'link_business_reference' => $response['data']['reference'],
                'link_business_data' => $businessData,
            ]);

            $this->redirect(route('link-business.paystack.redirect'));

        } catch (\Throwable $e) {
            Log::error('Paystack link business exception', [
                'error' => $e->getMessage(),
                'plan_id' => $plan->id,
            ]);
            $this->errorMessage = 'Payment could not be initiated. Please try again.';
        }
    }

    public function goBack(): void
    {
        $this->redirect(route('get-started'), navigate: true);
    }

    /**
     * Ensure the URL has a scheme.
     */
    private function normalizeUrl(string $url): string
    {
        $url = trim($url);

        if (! preg_match('#^https?://#i', $url)) {
            $url = 'https://'.$url;
        }

        return $url;
    }

    public function render()
    {
        return view('livewire.auth.link-business', [
            'categories' => Category::where('is_active', true)
                ->orderBy('sort_order')
                ->get(),
            'linkedPlan' => Plan::where('slug', 'linked_business')->first(),
        ]);
    }
}
