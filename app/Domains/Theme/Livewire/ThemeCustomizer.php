<?php

namespace App\Domains\Theme\Livewire;

use App\Domains\Theme\Actions\ActivateTheme;
use App\Domains\Theme\Actions\CustomizeTheme;
use App\Models\Business;
use App\Models\Theme;
use Livewire\Component;

class ThemeCustomizer extends Component
{
    public Business $business;

    public ?int $selectedThemeId = null;

    public string $primary = '#3B82F6';

    public string $secondary = '#10B981';

    public string $background = '#FFFFFF';

    public string $text = '#111827';

    public string $accent = '#F59E0B';

    public string $headingFont = 'Inter';

    public string $bodyFont = 'Inter';

    public string $radius = '0.5rem';

    public string $animations = '1';

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
        $activeTheme = $this->business->themes()
            ->wherePivot('is_active', true)
            ->first();

        if ($activeTheme) {
            $customization = $activeTheme->pivot->customization;
            $this->selectedThemeId = $activeTheme->id;
            $this->primary = $customization['colors']['primary'] ?? '#3B82F6';
            $this->secondary = $customization['colors']['secondary'] ?? '#10B981';
            $this->background = $customization['colors']['background'] ?? '#FFFFFF';
            $this->text = $customization['colors']['text'] ?? '#111827';
            $this->accent = $customization['colors']['accent'] ?? '#F59E0B';
            $this->headingFont = $customization['typography']['heading'] ?? 'Inter';
            $this->bodyFont = $customization['typography']['body'] ?? 'Inter';
            $this->radius = $customization['radius'] ?? '0.5rem';
            $this->animations = ($customization['animations'] ?? true) ? '1' : '0';
        }
    }

    public function selectTheme(int $themeId, ActivateTheme $activateTheme): void
    {
        $theme = Theme::findOrFail($themeId);
        $this->selectedThemeId = $themeId;

        $activateTheme($this->business, $theme);

        $defaults = $theme->config ?? [];
        $this->primary = $defaults['colors']['primary'] ?? '#3B82F6';
        $this->secondary = $defaults['colors']['secondary'] ?? '#10B981';
        $this->background = $defaults['colors']['background'] ?? '#FFFFFF';
        $this->text = $defaults['colors']['text'] ?? '#111827';
        $this->accent = $defaults['colors']['accent'] ?? '#F59E0B';
        $this->headingFont = $defaults['typography']['heading'] ?? 'Inter';
        $this->bodyFont = $defaults['typography']['body'] ?? 'Inter';
        $this->radius = $defaults['radius'] ?? '0.5rem';
        $this->animations = ($defaults['animations'] ?? true) ? '1' : '0';

        $this->saveCustomization(app(CustomizeTheme::class));
    }

    public function saveCustomization(CustomizeTheme $customizeTheme): void
    {
        if (! $this->selectedThemeId) {
            $this->errorMessage = 'Select a theme first.';

            return;
        }

        $customizeTheme($this->business, [
            'colors' => [
                'primary' => $this->primary,
                'secondary' => $this->secondary,
                'background' => $this->background,
                'text' => $this->text,
                'accent' => $this->accent,
            ],
            'typography' => [
                'heading' => $this->headingFont,
                'body' => $this->bodyFont,
            ],
            'radius' => $this->radius,
            'animations' => $this->animations === '1',
        ]);

        $this->successMessage = 'Theme saved.';
    }

    public function render()
    {
        return view('livewire.theme.theme-customizer', [
            'themes' => Theme::where('is_active', true)
                ->forBusinessType($this->business->type)
                ->orderBy('sort_order')
                ->get(),
        ]);
    }
}
