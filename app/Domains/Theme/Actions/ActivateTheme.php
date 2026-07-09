<?php

namespace App\Domains\Theme\Actions;

use App\Domains\Theme\Events\ThemeActivated;
use App\Models\Business;
use App\Models\Theme;

/**
 * Activates a theme for a given business.
 *
 * Deactivates all other themes for the business first, then
 * attaches or updates the chosen theme as active. Business owners
 * can only activate themes — they cannot create or modify them.
 */
class ActivateTheme
{
    public function __invoke(Business $business, Theme $theme): void
    {
        // Deactivate all currently active themes for this business.
        $activeIds = $business->themes()
            ->wherePivot('is_active', true)
            ->pluck('themes.id')
            ->toArray();

        $business->themes()->updateExistingPivot($activeIds, ['is_active' => false]);

        // Attach or update the chosen theme as active.
        $business->themes()->syncWithoutDetaching([
            $theme->id => ['is_active' => true],
        ]);

        event(new ThemeActivated($business, $theme));
    }
}
