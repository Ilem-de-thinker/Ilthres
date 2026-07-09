<?php

namespace App\Domains\Theme\DTOs;

/**
 * Immutable data for reading a theme's config + a business's customization.
 *
 * Merges theme defaults with per-business overrides so that every
 * customization value has a fallback chain:
 *   business override → theme default → engine default.
 */
class ThemeData
{
    public function __construct(
        public readonly Theme $theme,
        public readonly array $colors,
        public readonly array $typography,
        public readonly string $radius,
        public readonly bool $animations,
    ) {}
}
