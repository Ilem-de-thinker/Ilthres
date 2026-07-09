<?php

namespace App\Domains\Theme\Livewire;

use App\Models\Business;
use App\Models\Theme;
use Livewire\Component;

/**
 * Live iframe preview of a theme with the business's own data.
 *
 * Merchants can preview themes with their content before activating.
 * The iframe loads the ThemeRenderer with sample mode enabled.
 */
class ThemePreview extends Component
{
    public Business $business;
    public Theme $theme;

    public function render()
    {
        return view('livewire.theme.theme-preview');
    }
}
