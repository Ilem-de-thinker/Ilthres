<?php

namespace App\Domains\Business\Livewire;

use App\Domains\Business\Actions\UpdateBusiness;
use App\Domains\Business\Actions\UploadCover;
use App\Domains\Business\Actions\UploadLogo;
use App\Domains\Business\DTOs\BusinessData;
use App\Models\Business;
use App\Models\Category;
use App\Models\Theme;
use Livewire\Component;
use Livewire\WithFileUploads;

class BusinessSettings extends Component
{
    use WithFileUploads;

    public string $activeTab = 'details';

    public ?int $selectedBusinessId = null;

    public ?Business $business = null;

    // Details fields.
    public string $name = '';

    public string $description = '';

    public string $about = '';

    public ?int $categoryId = null;

    public bool $isDiscoverable = false;

    // Contact fields.
    public string $email = '';

    public string $phone = '';

    public string $whatsapp = '';

    // Address fields.
    public string $address = '';

    public string $city = '';

    public string $state = '';

    public string $country = 'Nigeria';

    public ?float $latitude = null;

    public ?float $longitude = null;

    // Social links.
    public string $socialLinksJson = '[]';

    // Logo & cover.
    public $newLogo = null;

    public $newCover = null;

    // Theme fields.
    public ?int $themeId = null;

    public string $themePrimary = '#4F46E5';

    public string $themeSecondary = '#10B981';

    public string $themeBackground = '#FFFFFF';

    public string $themeText = '#111827';

    public string $themeAccent = '#F59E0B';

    public string $headingFont = 'Inter';

    public string $bodyFont = 'Inter';

    public string $themeRadius = '0.5rem';

    public bool $themeAnimations = true;

    // Payment fields.
    public string $orderMode = 'inquiry';

    public string $bankName = '';

    public string $accountNumber = '';

    public string $accountName = '';

    public ?string $successMessage = null;

    public ?string $errorMessage = null;

    public array $availableFonts = [
        'Playfair Display',
        'Plus Jakarta Sans',
        'Inter',
        'Hanken Grotesk',
        'Anton',
        'Archivo Narrow',
        'Space Mono',
        'Work Sans',
        'Be Vietnam Pro',
        'Source Serif 4',
        'JetBrains Mono',
    ];

    public function mount(): void
    {
        $businesses = auth()->user()->businesses;

        if ($businesses->isEmpty()) {
            return;
        }

        $defaultId = session('current_business_id', $businesses->first()->id);
        $this->loadBusiness($defaultId);

        $tab = request()->query('tab');
        if (in_array($tab, ['details', 'theme', 'payments'])) {
            $this->activeTab = $tab;
        }
    }

    public function loadBusiness(int $businessId): void
    {
        $business = auth()->user()->businesses()->findOrFail($businessId);

        $this->authorize('update', $business);

        $this->business = $business;
        $this->selectedBusinessId = $business->id;

        // Details.
        $this->name = $business->name;
        $this->description = $business->description ?? '';
        $this->about = $business->about ?? '';
        $this->categoryId = $business->category_id;
        $this->isDiscoverable = $business->is_discoverable;

        // Contact.
        $this->email = $business->email ?? '';
        $this->phone = $business->phone ?? '';
        $this->whatsapp = $business->whatsapp ?? '';

        // Address.
        $this->address = $business->address ?? '';
        $this->city = $business->city ?? '';
        $this->state = $business->state ?? '';
        $this->country = $business->country ?? 'Nigeria';
        $this->latitude = $business->latitude ? (float) $business->latitude : null;
        $this->longitude = $business->longitude ? (float) $business->longitude : null;

        // Social links.
        $this->socialLinksJson = json_encode($business->social_links ?? []);

        // Reset uploads.
        $this->newLogo = null;
        $this->newCover = null;

        // Payment.
        $this->orderMode = $business->order_mode ?? 'inquiry';
        $this->bankName = $business->bank_name ?? '';
        $this->accountNumber = $business->account_number ?? '';
        $this->accountName = $business->account_name ?? '';

        // Active theme.
        $activeTheme = $business->themes()
            ->wherePivot('is_active', true)
            ->first();

        if ($activeTheme) {
            $customization = $activeTheme->pivot->customization;

            $this->themeId = $activeTheme->id;
            $this->themePrimary = $customization['colors']['primary'] ?? '#4F46E5';
            $this->themeSecondary = $customization['colors']['secondary'] ?? '#10B981';
            $this->themeBackground = $customization['colors']['background'] ?? '#FFFFFF';
            $this->themeText = $customization['colors']['text'] ?? '#111827';
            $this->themeAccent = $customization['colors']['accent'] ?? '#F59E0B';
            $this->headingFont = $customization['typography']['heading'] ?? 'Inter';
            $this->bodyFont = $customization['typography']['body'] ?? 'Inter';
            $this->themeRadius = $customization['radius'] ?? '0.5rem';
            $this->themeAnimations = $customization['animations'] ?? true;
        } else {
            $this->themeId = null;
        }
    }

    public function updatedSelectedBusinessId(int $value): void
    {
        $this->loadBusiness($value);
        $this->successMessage = null;
        $this->errorMessage = null;
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'about' => ['nullable', 'string', 'max:5000'],
            'categoryId' => ['nullable', 'exists:categories,id'],
            'isDiscoverable' => ['boolean'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'whatsapp' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'newLogo' => ['nullable', 'image', 'max:2048'],
            'newCover' => ['nullable', 'image', 'max:5120'],
            'themeId' => ['nullable', 'exists:themes,id'],
            'themePrimary' => ['required_with:themeId', 'string', 'max:7'],
            'themeSecondary' => ['required_with:themeId', 'string', 'max:7'],
            'themeBackground' => ['required_with:themeId', 'string', 'max:7'],
            'themeText' => ['required_with:themeId', 'string', 'max:7'],
            'themeAccent' => ['required_with:themeId', 'string', 'max:7'],
            'headingFont' => ['required_with:themeId', 'string', 'max:100'],
            'bodyFont' => ['required_with:themeId', 'string', 'max:100'],
            'themeRadius' => ['required_with:themeId', 'string', 'max:20'],
            'themeAnimations' => ['boolean'],
            'orderMode' => ['required', 'in:checkout,inquiry'],
            'bankName' => ['nullable', 'string', 'max:255'],
            'accountNumber' => ['nullable', 'string', 'max:50'],
            'accountName' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function selectTheme(int $themeId): void
    {
        $theme = Theme::find($themeId);
        if (! $theme) {
            return;
        }

        $config = $theme->config;

        $this->themeId = $themeId;
        $this->themePrimary = $config['colors']['primary'] ?? '#4F46E5';
        $this->themeSecondary = $config['colors']['secondary'] ?? '#10B981';
        $this->themeBackground = $config['colors']['background'] ?? '#FFFFFF';
        $this->themeText = $config['colors']['text'] ?? '#111827';
        $this->themeAccent = $config['colors']['accent'] ?? '#F59E0B';
        $this->headingFont = $config['typography']['heading'] ?? 'Inter';
        $this->bodyFont = $config['typography']['body'] ?? 'Inter';
        $this->themeRadius = $config['radius'] ?? '0.5rem';
        $this->themeAnimations = $config['animations'] ?? true;
    }

    public function saveDetails(): void
    {
        $this->successMessage = null;
        $this->errorMessage = null;

        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'about' => ['nullable', 'string', 'max:5000'],
            'categoryId' => ['nullable', 'exists:categories,id'],
            'isDiscoverable' => ['boolean'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'whatsapp' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'newLogo' => ['nullable', 'image', 'max:2048'],
            'newCover' => ['nullable', 'image', 'max:5120'],
        ]);

        try {
            $socialLinks = json_decode($this->socialLinksJson, true) ?? [];

            app(UpdateBusiness::class)($this->business, new BusinessData(
                userId: $this->business->user_id,
                type: $this->business->type,
                name: $this->name,
                slug: $this->business->slug,
                description: $this->description ?: null,
                about: $this->about ?: null,
                email: $this->email ?: null,
                phone: $this->phone ?: null,
                whatsapp: $this->whatsapp ?: null,
                address: $this->address ?: null,
                city: $this->city ?: null,
                state: $this->state ?: null,
                country: $this->country,
                latitude: $this->latitude,
                longitude: $this->longitude,
                categoryId: $this->categoryId,
                logo: $this->business->logo,
                cover: $this->business->cover,
                isDiscoverable: $this->isDiscoverable,
            ));

            $this->business->update(['social_links' => $socialLinks]);

            if ($this->newLogo) {
                app(UploadLogo::class)($this->business, $this->newLogo);
                $this->newLogo = null;
            }

            if ($this->newCover) {
                app(UploadCover::class)($this->business, $this->newCover);
                $this->newCover = null;
            }

            $this->successMessage = 'Business details saved.';
        } catch (\Throwable $e) {
            $this->errorMessage = 'Failed to save business details.';
            report($e);
        }
    }

    public function saveTheme(): void
    {
        $this->successMessage = null;
        $this->errorMessage = null;

        $this->validate([
            'themeId' => ['required', 'exists:themes,id'],
            'themePrimary' => ['required', 'string', 'max:7'],
            'themeSecondary' => ['required', 'string', 'max:7'],
            'themeBackground' => ['required', 'string', 'max:7'],
            'themeText' => ['required', 'string', 'max:7'],
            'themeAccent' => ['required', 'string', 'max:7'],
            'headingFont' => ['required', 'string', 'max:100'],
            'bodyFont' => ['required', 'string', 'max:100'],
            'themeRadius' => ['required', 'string', 'max:20'],
            'themeAnimations' => ['boolean'],
        ]);

        try {
            $this->business->themes()->updateExistingPivot(
                $this->business->themes->pluck('id')->toArray(),
                ['is_active' => false]
            );

            $this->business->themes()->syncWithoutDetaching([
                $this->themeId => [
                    'customization' => json_encode([
                        'colors' => [
                            'primary' => $this->themePrimary,
                            'secondary' => $this->themeSecondary,
                            'background' => $this->themeBackground,
                            'text' => $this->themeText,
                            'accent' => $this->themeAccent,
                        ],
                        'typography' => [
                            'heading' => $this->headingFont,
                            'body' => $this->bodyFont,
                        ],
                        'radius' => $this->themeRadius,
                        'animations' => $this->themeAnimations,
                    ]),
                    'is_active' => true,
                ],
            ]);

            $this->successMessage = 'Theme saved.';
        } catch (\Throwable $e) {
            $this->errorMessage = 'Failed to save theme.';
            report($e);
        }
    }

    public function savePaymentSettings(): void
    {
        $this->successMessage = null;
        $this->errorMessage = null;

        if ($this->orderMode === 'checkout' && ! $this->business->is_verified) {
            $this->errorMessage = 'Only verified businesses can enable online payment (checkout).';

            return;
        }

        $this->validate([
            'orderMode' => ['required', 'in:checkout,inquiry'],
            'bankName' => ['nullable', 'string', 'max:255'],
            'accountNumber' => ['nullable', 'string', 'max:50'],
            'accountName' => ['nullable', 'string', 'max:255'],
        ]);

        try {
            $this->business->update([
                'order_mode' => $this->orderMode,
                'bank_name' => $this->bankName,
                'account_number' => $this->accountNumber,
                'account_name' => $this->accountName,
            ]);

            $this->successMessage = 'Payment settings updated.';
        } catch (\Throwable $e) {
            $this->errorMessage = 'Failed to save payment settings.';
            report($e);
        }
    }

    public function render()
    {
        return view('livewire.business.business-settings', [
            'businesses' => auth()->user()->businesses,
            'categories' => Category::where('is_active', true)
                ->orderBy('sort_order')
                ->get(),
            'themes' => Theme::where('is_active', true)
                ->forBusinessType($this->business?->type)
                ->get(),
        ]);
    }
}
