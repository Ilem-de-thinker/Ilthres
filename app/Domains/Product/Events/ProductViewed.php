<?php

namespace App\Domains\Product\Events;

use App\Models\Product;

/**
 * Fired when a customer views a product on the public profile.
 *
 * Used by the Analytics domain to track popular products.
 * Dispatched as a queued event to avoid slowing down page rendering.
 */
class ProductViewed
{
    public function __construct(
        public Product $product,
    ) {}
}
