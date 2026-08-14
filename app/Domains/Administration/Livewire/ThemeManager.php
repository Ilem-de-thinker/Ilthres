<?php

namespace App\Domains\Administration\Livewire;

use App\Models\Theme;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Admin theme management.
 *
 * Lists all themes (global, not per-business). Admins can activate,
 * deactivate, or delete themes from this panel.
 */
#[Layout('layouts.admin')]
class ThemeManager extends Component
{
    use WithPagination;

    /**
     * Toggle the active status of a theme.
     */
    public function toggleActive(int $themeId): void
    {
        $theme = Theme::findOrFail($themeId);
        $theme->update(['is_active' => ! $theme->is_active]);

        session()->flash('success', "Theme {$theme->name} has been ".($theme->is_active ? 'enabled' : 'disabled').'.');
    }

    public function render()
    {
        $themes = Theme::latest()->paginate(20);

        return view('livewire.administration.theme-manager', [
            'themes' => $themes,
        ]);
    }
}
