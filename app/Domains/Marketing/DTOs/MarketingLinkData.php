<?php

namespace App\Domains\Marketing\DTOs;

class MarketingLinkData
{
    public function __construct(
        public readonly int $businessId,
        public readonly string $name,
        public readonly string $slug,
        public readonly string $url,
        public readonly ?string $description = null,
        public readonly bool $isActive = true,
    ) {}
}
