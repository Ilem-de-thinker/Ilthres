<?php

namespace App\Domains\Product\Actions;

use App\Models\Product;

/**
 * Toggles a product's is_active flag.
 *
 * Used for quick enable/disable from the product list or quick-edit panel.
 * No event is fired since this is a lightweight status toggle.
 */
class ToggleStatus
{
    public function __invoke(Product $product): Product
    {
        $product->update(['is_active' => !$product->is_active]);

        return $product;
    }
}
