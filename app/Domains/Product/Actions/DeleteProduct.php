<?php

namespace App\Domains\Product\Actions;

use App\Domains\Product\Events\ProductDeleted;
use App\Models\Product;

/**
 * Removes a product (soft-delete) and notifies the system.
 *
 * Fires ProductDeleted so search indexes and caches can clean up.
 */
class DeleteProduct
{
    public function __invoke(Product $product): void
    {
        $product->delete();

        event(new ProductDeleted($product));
    }
}
