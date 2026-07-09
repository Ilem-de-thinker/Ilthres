<?php

namespace App\Domains\Theme\Actions;

use App\Domains\Theme\Events\ThemeCustomized;
use App\Models\Business;

/**
 * Saves per-business theme customization overrides.
 *
 * Merges the provided customization with the pivot record for the
 * business's active theme. Listeners can regenerate cached CSS
 * or preview thumbnails after this runs.
 */
class CustomizeTheme
{
    public function __invoke(Business $business, array $customization): void
    {
        $activeTheme = $business->themes()
            ->wherePivot('is_active', true)
            ->first();

        if (! $activeTheme) {
            return;
        }

        $business->themes()->updateExistingPivot($activeTheme->id, [
            'customization' => $customization,
        ]);

        event(new ThemeCustomized($business, $activeTheme, $customization));
    }
}
