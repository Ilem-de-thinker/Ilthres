<?php

namespace App\Domains\Product\Events;

use App\Models\Product;

/**
 * Fired when a product is soft-deleted.
 *
 * Listeners remove the product from search indexes
 * and clean up related resources.
 */
class ProductDeleted
{
    public function __construct(
        public Product $product,
    ) {}
}
