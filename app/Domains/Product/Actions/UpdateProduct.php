<?php

namespace App\Domains\Product\Actions;

use App\Domains\Product\DTOs\ProductData;
use App\Domains\Product\Events\ProductUpdated;
use App\Models\Product;

/**
 * Updates an existing product with new data.
 *
 * Fires ProductUpdated so search indexes and caches refresh their entries.
 */
class UpdateProduct
{
    public function __invoke(Product $product, ProductData $data): Product
    {
        $product->update([
            'category_id' => $data->categoryId,
            'name' => $data->name,
            'slug' => $data->slug,
            'description' => $data->description,
            'price' => $data->price,
            'compare_price' => $data->comparePrice,
            'currency' => $data->currency,
            'images' => $data->images,
            'variants' => $data->variants,
            'is_active' => $data->isActive,
            'is_featured' => $data->isFeatured,
            'is_public' => $data->isPublic,
            'sort_order' => $data->sortOrder,
        ]);

        event(new ProductUpdated($product));

        return $product;
    }
}
