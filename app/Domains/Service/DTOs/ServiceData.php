<?php

namespace App\Domains\Service\DTOs;

class ServiceData
{
    public function __construct(
        public readonly int $businessId,
        public readonly ?int $categoryId,
        public readonly string $name,
        public readonly string $slug,
        public readonly ?string $description,
        public readonly float $price,
        public readonly string $currency,
        public readonly ?int $durationMinutes,
        public readonly ?array $images,
        public readonly bool $isActive,
        public readonly int $sortOrder,
        public readonly bool $isPublic = false,
    ) {}
}
