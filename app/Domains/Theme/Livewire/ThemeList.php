<?php

namespace App\Domains\Theme\Livewire;

use App\Models\Theme;
use Livewire\Component;

/**
 * Grid of available themes for a business owner to browse.
 *
 * Shows theme cards with name, description, thumbnail, and premium badge.
 * Filtered by business type so product stores only see store themes
 * and service businesses only see service themes.
 */
class ThemeList extends Component
{
    public string $businessType = '';

    public function render()
    {
        $query = Theme::where('is_active', true)->orderBy('sort_order')->orderBy('name');

        if ($this->businessType) {
            $query->forBusinessType($this->businessType);
        }

        return view('livewire.theme.theme-list', [
            'themes' => $query->get(),
        ]);
    }
}
