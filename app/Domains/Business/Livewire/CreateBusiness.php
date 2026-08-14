<?php

namespace App\Domains\Business\Livewire;

use App\Domains\Business\Actions\CreateBusiness as CreateBusinessAction;
use App\Domains\Business\DTOs\BusinessData;
use App\Models\Category;
use App\Models\Theme;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

/**
 * Create a new business profile (for multi-business users).
 *
 * This is a simplified version of the onboarding wizard's step 4.
 * Used from the dashboard's "New business" link.
 */
class CreateBusiness extends Component
{
    use WithFileUploads;

    public string $name = '';

    public string $description = '';

    public ?int $categoryId = null;

    public string $type = 'product_store';

    // Theme selection.
    public ?int $themeId = null;

    public string $themePrimary = '#4F46E5';

    public string $themeSecondary = '#10B981';

    public string $themeBackground = '#FFFFFF';

    public string $themeText = '#111827';

    public string $themeAccent = '#F59E0B';

    public $logo = null;

    public ?string $successMessage = null;

    public ?string $errorMessage = null;

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'categoryId' => ['nullable', 'exists:categories,id'],
            'type' => ['required', 'in:product_store,service_business'],
            'themeId' => ['required', 'exists:themes,id'],
            'themePrimary' => ['required', 'string', 'max:7'],
            'themeSecondary' => ['required', 'string', 'max:7'],
            'themeBackground' => ['required', 'string', 'max:7'],
            'themeText' => ['required', 'string', 'max:7'],
            'themeAccent' => ['required', 'string', 'max:7'],
            'logo' => ['nullable', 'image', 'max:2048'],
        ];
    }

    protected $messages = [
        'name.required' => 'Give your business a name.',
        'type.required' => 'Select whether you sell products or offer services.',
        'themeId.required' => 'Select a theme for your business page.',
        'themeId.exists' => 'The selected theme is no longer available.',
        'logo.image' => 'The logo must be an image file (JPEG, PNG, WebP).',
        'logo.max' => 'The logo must not exceed 2MB.',
    ];

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

    public function removeLogo(): void
    {
        $this->logo = null;
    }

    public function save(CreateBusinessAction $createBusiness): void
    {
        $this->validate();

        try {
            $logoPath = null;
            if ($this->logo) {
                $logoPath = $this->logo->store('logos', 'public');
            }

            $business = $createBusiness(new BusinessData(
                userId: auth()->id(),
                type: $this->type,
                name: $this->name,
                slug: Str::slug($this->name),
                description: $this->description ?: null,
                email: auth()->user()->email,
                phone: null,
                whatsapp: null,
                address: null,
                city: null,
                state: null,
                country: 'Nigeria',
                categoryId: $this->categoryId,
                logo: $logoPath,
                cover: null,
            ));

            dd($business);

            // Attach the selected theme with customization.
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

            $this->successMessage = 'Business created!';

            $this->redirect(route('dashboard.switch', $business), navigate: true);

        } catch (\Throwable $e) {
            $this->errorMessage = 'Something went wrong. Please try again.';
            report($e);
        }
    }

    public function render()
    {
        return view('livewire.business.create-business', [
            'categories' => Category::where('is_active', true)
                ->orderBy('sort_order')
                ->get(),
            'themes' => Theme::where('is_active', true)
                ->forBusinessType($this->type)
                ->get(),
        ]);
    }
}
