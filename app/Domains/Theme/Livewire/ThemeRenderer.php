<?php

namespace App\Domains\Theme\Livewire;

use App\Models\Business;
use Livewire\Component;

/**
 * Renders the active theme for a business's public profile.
 *
 * Resolves the business's active theme, merges its config with
 * per-business customization, injects CSS variables into <head>,
 * and renders the theme layout with the business's data.
 */
class ThemeRenderer extends Component
{
    public Business $business;
    public bool $previewMode = false;

    public function render()
    {
        $activeTheme = $this->business->themes()
            ->wherePivot('is_active', true)
            ->first();

        $config = $activeTheme?->config ?? [];
        $customization = $activeTheme?->pivot->customization ?? [];

        // Merge: business customization overrides theme defaults.
        $colors = array_merge(
            $config['colors'] ?? [],
            $customization['colors'] ?? []
        );
        $typography = array_merge(
            $config['typography'] ?? [],
            $customization['typography'] ?? []
        );
        $radius = $customization['radius'] ?? $config['radius'] ?? '0.5rem';
        $animations = $customization['animations'] ?? $config['animations'] ?? true;

        return view('livewire.theme.theme-renderer', [
            'colors' => $colors,
            'typography' => $typography,
            'radius' => $radius,
            'animations' => $animations,
        ]);
    }
}
