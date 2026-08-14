<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Pivot model connecting a business to a theme with per-business customization.
 *
 * Stores the customization overrides (colors, typography, radius) and
 * tracks which theme is currently active for a given business.
 * Extends Pivot so the Theme model's BelongsToMany can use it via ->using().
 *
 * @property int $business_id
 * @property int $theme_id
 * @property array|null $customization Color, font, radius overrides
 * @property bool $is_active Whether this is the business's active theme
 */
class BusinessTheme extends Pivot
{
    protected $casts = [
        'customization' => 'array',
        'is_active' => 'boolean',
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function theme(): BelongsTo
    {
        return $this->belongsTo(Theme::class);
    }
}
