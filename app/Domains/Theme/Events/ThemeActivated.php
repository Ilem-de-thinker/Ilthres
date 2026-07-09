<?php

namespace App\Domains\Theme\Events;

use App\Models\Business;
use App\Models\Theme;

/**
 * Fired when a business activates a theme.
 *
 * Listeners may clear cached public profile pages, regenerate
 * the business's CSS, or notify the business owner.
 */
class ThemeActivated
{
    public function __construct(
        public Business $business,
        public Theme $theme,
    ) {}
}
