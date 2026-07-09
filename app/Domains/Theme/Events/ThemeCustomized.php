<?php

namespace App\Domains\Theme\Events;

use App\Models\Business;
use App\Models\Theme;

/**
 * Fired when a business customises its active theme's colors, fonts, or layout.
 *
 * Stores the full customization payload so listeners can regenerate
 * cached CSS or preview thumbnails.
 */
class ThemeCustomized
{
    public function __construct(
        public Business $business,
        public Theme $theme,
        public array $customization,
    ) {}
}
