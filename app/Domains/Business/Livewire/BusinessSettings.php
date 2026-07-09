<?php

namespace App\Domains\Business\Livewire;

use App\Domains\Business\Actions\UploadCover;
use App\Domains\Business\Actions\UploadLogo;
use App\Models\Business;
use App\Models\Theme;
use Livewire\Component;
use Livewire\WithFileUploads;

/**
 * Business settings page — logo/cover upload, theme management.
 *
 * Shows a business selector at the top. The owner picks which
 * business to manage, then uploads logos/covers or customises themes.
 */
class BusinessSettings extends Component
{
    use WithFileUploads;

    public ?int $selectedBusinessId = null;
    public ?Business $business = null;

    // Logo & cover uploads.
    public $newLogo = null;
    public $newCover = null;

    // Theme selection.
    public ?int $themeId = null;
    public string $themePrimary = '#4F46E5';
    public string $themeSecondary = '#10B981';
    public string $themeBackground = '#FFFFFF';
    public string $themeText = '#111827';
    public string $themeAccent = '#F59E0B';

    public ?string $successMessage = null;
    public ?string $errorMessage = null;

    /**
     * Auto-select the first business on mount.
     */
    public function mount(): void
    {
        $businesses = auth()->user()->businesses;

        if ($businesses->isEmpty()) {
            return;
        }

        $defaultId = session('current_business_id', $businesses->first()->id);

        $this->loadBusiness($defaultId);
    }

    /**
     * Load a business and its active theme.
     */
    public function loadBusiness(int $businessId): void
    {
        $business = auth()->user()->businesses()->findOrFail($businessId);

        $this->authorize('update', $business);

        $this->business = $business;
        $this->selectedBusinessId = $business->id;

        // Load active theme customization.
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

    /**
     * When the dropdown changes, reload the business.
     */
    public function updatedSelectedBusinessId(int $value): void
    {
        $this->loadBusiness($value);
        $this->newLogo = null;
        $this->newCover = null;
        $this->successMessage = null;
        $this->errorMessage = null;
    }

    protected function rules(): array
    {
        return [
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

    public function uploadLogo(UploadLogo $uploadLogo): void
    {
        $this->validateOnly('newLogo');

        try {
            $uploadLogo($this->business, $this->newLogo);
            $this->newLogo = null;
            $this->successMessage = 'Logo updated.';
        } catch (\Throwable $e) {
            $this->errorMessage = 'Failed to upload logo.';
            report($e);
        }
    }

    public function uploadCover(UploadCover $uploadCover): void
    {
        $this->validateOnly('newCover');

        try {
            $uploadCover($this->business, $this->newCover);
            $this->newCover = null;
            $this->successMessage = 'Cover image updated.';
        } catch (\Throwable $e) {
            $this->errorMessage = 'Failed to upload cover image.';
            report($e);
        }
    }

    /**
     * Save theme selection and customization.
     */
    public function saveTheme(): void
    {
        $this->validate([
            'themeId' => ['required', 'exists:themes,id'],
            'themePrimary' => ['required', 'string', 'max:7'],
            'themeSecondary' => ['required', 'string', 'max:7'],
            'themeBackground' => ['required', 'string', 'max:7'],
            'themeText' => ['required', 'string', 'max:7'],
            'themeAccent' => ['required', 'string', 'max:7'],
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

    public function render()
    {
        return view('livewire.business.business-settings', [
            'businesses' => auth()->user()->businesses,
            'themes' => Theme::where('is_active', true)
                ->forBusinessType($this->business?->type)
                ->get(),
        ]);
    }
}
