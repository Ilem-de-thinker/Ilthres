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

class EditBusiness extends Component
{
    use WithFileUploads;

    public ?int $selectedBusinessId = null;
    public ?Business $business = null;

    public string $name = '';
    public string $description = '';
    public string $about = '';
    public ?int $categoryId = null;
    public bool $isDiscoverable = false;

    public string $email = '';
    public string $phone = '';
    public string $whatsapp = '';
    public string $address = '';
    public string $city = '';
    public string $state = '';
    public string $country = 'Nigeria';
    public ?float $latitude = null;
    public ?float $longitude = null;

    public string $socialLinksJson = '[]';

    public $newLogo = null;
    public $newCover = null;

    public ?int $themeId = null;
    public string $themePrimary = '#4F46E5';
    public string $themeSecondary = '#10B981';
    public string $themeBackground = '#FFFFFF';
    public string $themeText = '#111827';
    public string $themeAccent = '#F59E0B';

    public ?string $successMessage = null;
    public ?string $errorMessage = null;

    public function mount(): void
    {
        $businesses = auth()->user()->businesses;

        if ($businesses->isEmpty()) {
            return;
        }

        $defaultId = session('current_business_id', $businesses->first()->id);

        $this->loadBusiness($defaultId);
    }

    public function loadBusiness(int $businessId): void
    {
        $business = auth()->user()->businesses()->findOrFail($businessId);

        $this->authorize('update', $business);

        $this->business = $business;
        $this->selectedBusinessId = $business->id;
        $this->name = $business->name;
        $this->description = $business->description ?? '';
        $this->about = $business->about ?? '';
        $this->categoryId = $business->category_id;
        $this->isDiscoverable = $business->is_discoverable;
        $this->email = $business->email ?? '';
        $this->phone = $business->phone ?? '';
        $this->whatsapp = $business->whatsapp ?? '';
        $this->address = $business->address ?? '';
        $this->city = $business->city ?? '';
        $this->state = $business->state ?? '';
        $this->country = $business->country ?? 'Nigeria';
        $this->latitude = $business->latitude ? (float) $business->latitude : null;
        $this->longitude = $business->longitude ? (float) $business->longitude : null;
        $this->socialLinksJson = json_encode($business->social_links ?? []);
        $this->newLogo = null;
        $this->newCover = null;

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
    }

    public function save(): void
    {
        $this->validate();

        $socialLinks = json_decode($this->socialLinksJson, true) ?? [];

        try {
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

            $this->business->update([
                'social_links' => $socialLinks,
            ]);

            if ($this->newLogo) {
                app(UploadLogo::class)($this->business, $this->newLogo);
                $this->newLogo = null;
            }

            if ($this->newCover) {
                app(UploadCover::class)($this->business, $this->newCover);
                $this->newCover = null;
            }

            if ($this->themeId) {
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
                        ]),
                        'is_active' => true,
                    ],
                ]);
            }

            $this->successMessage = 'Business saved.';
        } catch (\Throwable $e) {
            $this->errorMessage = 'Something went wrong. Please try again.';
            report($e);
        }
    }

    public function render()
    {
        return view('livewire.business.edit-business', [
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
