<?php

namespace App\Domains\Product\Events;

use App\Models\Product;

/**
 * Fired when a new product is created.
 *
 * Listeners typically update search indexes,
 * clear relevant caches, or trigger analytics.
 */
class ProductCreated
{
    public function __construct(
        public Product $product,
    ) {}
}
