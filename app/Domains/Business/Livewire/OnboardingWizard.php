<?php

namespace App\Domains\Business\Livewire;

use App\Domains\Business\Actions\CreateBusiness;
use App\Domains\Business\DTOs\BusinessData;
use App\Models\Business;
use App\Models\Category;
use App\Models\Plan;
use App\Models\PlatformSetting;
use App\Models\Subscription;
use App\Models\Theme;
use HopekellDev\Paystack\Exceptions\PaystackException;
use HopekellDev\Paystack\Paystack;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;

/**
 * Multi-step onboarding wizard for new users.
 *
 * Steps tracked on the User model (onboarding_step 0-5):
 *   0 → not started      (redirect here from middleware)
 *   1 → WhatsApp number
 *   2 → Business type
 *   3 → Pricing plan
 *   4 → Business setup   (name, description, category, theme, logo)
 *   5 → completed        (dashboard accessible)
 *
 * Progress is persisted to the user record on every step transition
 * so that logging out mid-wizard resumes from the correct step.
 */
class OnboardingWizard extends Component
{
    use WithFileUploads;

    // ─── Step tracking ─────────────────────────────────
    public int $step = 1;

    // ─── Step 1: WhatsApp ──────────────────────────────
    public string $whatsapp = '';

    // ─── Step 2: Business type ─────────────────────────
    public string $businessType = '';

    // ─── Step 3: Pricing ───────────────────────────────
    public ?int $selectedPlanId = null;

    public array $planNames = [];

    // ─── Step 4: Setup ─────────────────────────────────
    public string $businessName = '';

    public string $businessDescription = '';

    public ?int $categoryId = null;

    // Theme selection.
    public ?int $themeId = null;

    public string $themePrimary = '#4F46E5';

    public string $themeSecondary = '#10B981';

    public string $themeBackground = '#FFFFFF';

    public string $themeText = '#111827';

    public string $themeAccent = '#F59E0B';

    // Logo upload.
    public $logo = null; // Livewire TemporaryUploadedFile

    // ─── Error state ───────────────────────────────────
    public ?string $errorMessage = null;

    public ?string $successMessage = null;

    /**
     * Restore wizard state from the user's persisted onboarding_step.
     *
     * If the user returns mid-wizard (e.g. after logout), we restore
     * their progress so they don't have to start over.
     */
    public function mount(): void
    {
        $user = auth()->user();

        // Capture any error from the Paystack callback redirect.
        if ($flashError = session('error')) {
            $this->errorMessage = $flashError;
            session()->forget('error');
        }

        $this->step = max(1, min($user->onboarding_step, 4));

        $this->planNames = Plan::where('is_active', true)
            ->orderBy('sort_order')
            ->pluck('name', 'id')
            ->toArray();

        // Restore previously entered values from session.
        $this->whatsapp = session('onboarding.whatsapp', '');
        $this->businessType = session('onboarding.business_type', '');
        $this->selectedPlanId = session('onboarding.plan_id', null);
        $this->businessName = session('onboarding.business_name', '');
        $this->businessDescription = session('onboarding.description', '');
        $this->categoryId = session('onboarding.category_id', null);

        // Restore theme selection.
        $this->themeId = session('onboarding.theme_id', null);
        $this->themePrimary = session('onboarding.theme_primary', '#4F46E5');
        $this->themeSecondary = session('onboarding.theme_secondary', '#10B981');
        $this->themeBackground = session('onboarding.theme_background', '#FFFFFF');
        $this->themeText = session('onboarding.theme_text', '#111827');
        $this->themeAccent = session('onboarding.theme_accent', '#F59E0B');
    }

    // ─── Validation ────────────────────────────────────
    protected function rules(): array
    {
        return match ($this->step) {
            1 => ['whatsapp' => ['required', 'string', 'max:20']],
            2 => ['businessType' => ['required', 'in:product_store,service_business']],
            3 => ['selectedPlanId' => ['required', 'exists:plans,id']],
            4 => [
                'businessName' => ['required', 'string', 'max:255', 'unique:busnesses'],
                'businessDescription' => ['nullable', 'string', 'max:1000'],
                'categoryId' => ['nullable', 'exists:categories,id'],
                'themeId' => ['required', 'exists:themes,id'],
                'themePrimary' => ['required', 'string', 'max:7'],
                'themeSecondary' => ['required', 'string', 'max:7'],
                'themeBackground' => ['required', 'string', 'max:7'],
                'themeText' => ['required', 'string', 'max:7'],
                'themeAccent' => ['required', 'string', 'max:7'],
                'logo' => ['nullable', 'image', 'max:2048'],
            ],
            default => [],
        };
    }

    protected $messages = [
        'whatsapp.required' => 'Please enter your WhatsApp number so customers can reach you.',
        'businessType.required' => 'Select whether you sell products or offer services.',
        'businessType.in' => 'Choose either Product store or Service business.',
        'selectedPlanId.required' => 'Select a plan to continue.',
        'selectedPlanId.exists' => 'The selected plan is no longer available.',
        'businessName.required' => 'Give your business a name.',
        'themeId.required' => 'Select a theme for your business page.',
        'themeId.exists' => 'The selected theme is no longer available.',
        'logo.image' => 'The logo must be an image file (JPEG, PNG, WebP).',
        'logo.max' => 'The logo must not exceed 2MB.',
    ];

    /**
     * Persist current step data to session and advance.
     */
    public function nextStep(): void
    {
        $this->validate();
        $this->persistToSession();

        $this->step = min($this->step + 1, 4);

        auth()->user()->forceFill(['onboarding_step' => $this->step])->save();

        Log::info('Onboarding step advanced', [
            'user_id' => auth()->id(),
            'step' => $this->step,
            'business_type' => $this->businessType,
        ]);

        $this->errorMessage = null;
    }

    /** Go back to the previous step. */
    public function prevStep(): void
    {
        $this->step = max($this->step - 1, 1);
        $this->errorMessage = null;
    }

    /**
     * Select a theme and pre-fill its default color values.
     *
     * When the user clicks a theme card, we load the theme's default config
     * and populate the color fields so they can be tweaked before finalising.
     */
    public function selectTheme(int $themeId): void
    {
        $theme = Theme::find($themeId);
        if (! $theme) {
            Log::warning('Theme not found during selection', ['theme_id' => $themeId]);

            return;
        }

        $colors = ($theme->config)['colors'] ?? [];

        $this->themeId = $themeId;
        $this->themePrimary = $colors['primary'] ?? '#4F46E5';
        $this->themeSecondary = $colors['secondary'] ?? '#10B981';
        $this->themeBackground = $colors['background'] ?? '#FFFFFF';
        $this->themeText = $colors['text'] ?? '#111827';
        $this->themeAccent = $colors['accent'] ?? '#F59E0B';
    }

    /** Remove the uploaded logo and reset the field. */
    public function removeLogo(): void
    {
        $this->logo = null;
    }

    /**
     * Create the business, attach the chosen theme, and handle subscription payment.
     *
     * For the Free plan, a subscription is created immediately and onboarding
     * is marked complete. For paid plans, the user is redirected to Paystack
     * for payment before the subscription is activated.
     */
    public function complete(CreateBusiness $createBusiness): void
    {
        $this->validate();

        try {
            $plan = Plan::findOrFail($this->selectedPlanId);
            $pricingEnabled = (bool) PlatformSetting::get('pricing_enabled', true);

            // Free plan or pricing disabled — create directly without payment.
            if ((float) $plan->price === 0.0 || ! $pricingEnabled) {
                $this->createBusinessAndFreeSubscription($createBusiness, $plan);

                return;
            }

            $this->initializePaidSubscription($plan);

        } catch (\Throwable $e) {

        throw new \Exception($e->getMessage());
            Log::error('Onboarding completion failed', [
                'user_id' => auth()->id(),
                'step' => $this->step,
                'business_type' => $this->businessType,
                'plan_id' => $this->selectedPlanId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            $this->errorMessage = match (true) {
                $e instanceof PaystackException => 'Payment could not be initiated. Please check your payment settings and try again.',
                $e instanceof ModelNotFoundException => 'The selected plan is no longer available.',
                default => 'Something went wrong. Please try again.',
            };
        }
    }

    /**
     * Create business, theme, and free subscription in one go.
     */
    private function createBusinessAndFreeSubscription(CreateBusiness $createBusiness, Plan $plan): void
    {
        $business = $this->createBusinessRecord($createBusiness);
        $this->attachTheme($business);

        Subscription::create([
            'business_id' => $business->id,
            'plan_id' => $plan->id,
            'price_paid' => 0,
            'status' => 'active',
            'starts_at' => now(),
            'gateway' => null,
            'gateway_reference' => null,
            'gateway_data' => null,
        ]);

        auth()->user()->forceFill([
            'onboarding_step' => 5,
            'onboarding_completed' => true,
        ])->save();

        $this->successMessage = 'Your business is live! Redirecting to dashboard...';
        $this->redirect(route('dashboard'), navigate: true);
    }

    /**
     * Initialize Paystack payment without creating the business first.
     * Business is only created after successful payment verification.
     */
    private function initializePaidSubscription(Plan $plan): void
    {
        $user = auth()->user();
        $amount = (int) ((float) $plan->price * 100);

        $response = Paystack::transactions()->initialize([
            'email' => $user->email,
            'amount' => $amount,
            'callback_url' => route('onboarding.paystack.callback'),
            'metadata' => [
                'plan_id' => $plan->id,
                'user_id' => $user->id,
                'business_type' => $this->businessType,
                'business_name' => $this->businessName,
                'business_description' => $this->businessDescription,
                'category_id' => $this->categoryId,
                'theme_id' => $this->themeId,
                'theme_colors' => [
                    'primary' => $this->themePrimary,
                    'secondary' => $this->themeSecondary,
                    'background' => $this->themeBackground,
                    'text' => $this->themeText,
                    'accent' => $this->themeAccent,
                ],
            ],
        ]);

        if (! ($response['status'] ?? false) || ! ($response['data']['authorization_url'] ?? null)) {
            Log::error('Paystack initialization failed', [
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'response' => $response,
            ]);
            throw new PaystackException('Failed to initialize payment. No authorization URL returned.');
        }

        session([
            'paystack_authorization_url' => $response['data']['authorization_url'],
            'paystack_reference' => $response['data']['reference'],
            'paystack_onboarding_data' => [
                'business_name' => $this->businessName,
                'business_description' => $this->businessDescription,
                'business_type' => $this->businessType,
                'category_id' => $this->categoryId,
                'theme_id' => $this->themeId,
                'theme_primary' => $this->themePrimary,
                'theme_secondary' => $this->themeSecondary,
                'theme_background' => $this->themeBackground,
                'theme_text' => $this->themeText,
                'theme_accent' => $this->themeAccent,
                'whatsapp' => $this->whatsapp,
                'plan_id' => $this->selectedPlanId,
            ],
        ]);

        $this->redirect(route('onboarding.paystack.redirect'));
    }

    /**
     * Create the business record, upload logo if present.
     */
    private function createBusinessRecord(CreateBusiness $createBusiness): Business
    {
        $logoPath = null;
        if ($this->logo) {
            $logoPath = $this->logo->store('logos', 'public');
        }

        return $createBusiness(new BusinessData(
            userId: auth()->id(),
            type: $this->businessType,
            name: $this->businessName,
            slug: Str::slug($this->businessName),
            description: $this->businessDescription ?: null,
            email: auth()->user()->email,
            phone: null,
            whatsapp: $this->whatsapp ?: null,
            address: null,
            city: null,
            state: null,
            country: 'Nigeria',
            categoryId: $this->categoryId,
            logo: $logoPath,
            cover: null,
        ));
    }

    /**
     * Attach the selected theme with custom colors.
     */
    private function attachTheme(Business $business): void
    {
        $business->themes()->attach($this->themeId, [
            'customization' => json_encode([
                'colors' => [
                    'primary' => $this->themePrimary,
                    'secondary' => $this->themeSecondary,
                    'background' => $this->themeBackground,
                    'text' => $this->themeText,
                    'accent' => $this->themeAccent,
                ],
            ]),
            'is_active' => true,
        ]);
    }

    /** Persist current step data to session so it survives refresh. */
    private function persistToSession(): void
    {
        session([
            'onboarding.whatsapp' => $this->whatsapp,
            'onboarding.business_type' => $this->businessType,
            'onboarding.plan_id' => $this->selectedPlanId,
            'onboarding.business_name' => $this->businessName,
            'onboarding.description' => $this->businessDescription,
            'onboarding.category_id' => $this->categoryId,
            'onboarding.theme_id' => $this->themeId,
            'onboarding.theme_primary' => $this->themePrimary,
            'onboarding.theme_secondary' => $this->themeSecondary,
            'onboarding.theme_background' => $this->themeBackground,
            'onboarding.theme_text' => $this->themeText,
            'onboarding.theme_accent' => $this->themeAccent,
        ]);
    }

    #[Computed]
    public function getCategories()
    {
        return Category::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    public function getThemes()
    {
        return Theme::where('is_active', true)
            ->forBusinessType($this->businessType)
            ->get();
    }

    public function getPlans()
    {
        return Plan::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    public function render()
    {
        return view('livewire.business.onboarding-wizard', [
            'categories' => $this->getThemes(),
            'plans' => $this->getPlans(),
            'themes' => $this->getThemes(),
        ]);
    }
}
