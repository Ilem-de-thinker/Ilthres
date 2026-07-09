<?php

namespace App\Domains\Promotion\DTOs;

class PromotionData
{
    public function __construct(
        public readonly int $businessId,
        public readonly string $name,
        public readonly string $slug,
        public readonly ?string $description,
        public readonly string $promotionType,
        public readonly string $scope,
        public readonly ?string $discountType = null,
        public readonly ?float $discountValue = null,
        public readonly ?float $fixedPrice = null,
        public readonly ?int $buyX = null,
        public readonly ?int $buyY = null,
        public readonly ?string $buyYDiscountType = null,
        public readonly ?float $buyYDiscountValue = null,
        public readonly ?float $minOrderAmount = null,
        public readonly ?int $maxUses = null,
        public readonly ?int $customerLimit = null,
        public readonly int $priority = 0,
        public readonly string $status = 'draft',
        public readonly ?string $bannerImage = null,
        public readonly bool $showCountdown = true,
        public readonly bool $showBadge = true,
        public readonly bool $featured = false,
        public readonly ?array $badgeConfig = null,
        public readonly ?string $startsAt = null,
        public readonly ?string $endsAt = null,
        public readonly ?int $createdBy = null,
        public readonly array $productIds = [],
        public readonly array $serviceIds = [],
        public readonly array $categoryIds = [],
    ) {}
}
