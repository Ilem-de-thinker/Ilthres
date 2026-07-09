<?php

namespace App\Domains\Product\DTOs;

/**
 * Data transfer object for creating or updating a product.
 *
 * All fields are immutable once the DTO is constructed.
 * Nullable fields represent optional product attributes
 * that are omitted from the public profile when absent.
 */
class ProductData
{
    public function __construct(
        /** The business that owns this product. */
        public readonly int $businessId,

        /** Optional category for product classification. */
        public readonly ?int $categoryId,

        /** Product display name. */
        public readonly string $name,

        /** URL-safe slug for public profile links. */
        public readonly string $slug,

        /** Long-form product description (supports basic HTML/markdown). */
        public readonly ?string $description,

        /** Current selling price. */
        public readonly float $price,

        /** Original price before discount (shown as strikethrough). */
        public readonly ?float $comparePrice,

        /** ISO 4217 currency code (e.g. NGN, USD). */
        public readonly string $currency,

        /** Array of image paths or URLs. */
        public readonly ?array $images,

        /** JSON-compatible variant definitions (size, colour, etc.). */
        public readonly ?array $variants,

        /** Whether the product is visible on the public profile. */
        public readonly bool $isActive,

        /** Whether the product appears in featured/suggested sections. */
        public readonly bool $isFeatured,

        /** Control display ordering within listings (lower = first). */
        public readonly int $sortOrder,

        /** Whether the product appears in public Explore search results. */
        public readonly bool $isPublic = false,
    ) {}
}
