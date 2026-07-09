<?php

namespace App\Domains\Product\Events;

use App\Models\Product;

/**
 * Fired when a product's details are changed.
 *
 * Listeners re-index the product in search
 * and invalidate cached product pages.
 */
class ProductUpdated
{
    public function __construct(
        public Product $product,
    ) {}
}
