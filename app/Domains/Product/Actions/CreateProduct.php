<?php

namespace App\Domains\Product\Actions;

use App\Domains\Product\DTOs\ProductData;
use App\Domains\Product\Events\ProductCreated;
use App\Models\Product;

/**
 * Creates a new product record for a business.
 *
 * Accepts validated product data via ProductData DTO, persists it,
 * and fires ProductCreated so search indexes and other listeners can react.
 */
class CreateProduct
{
    public function __invoke(ProductData $data): Product
    {
        $product = Product::create([
            'business_id' => $data->businessId,
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

        event(new ProductCreated($product));

        return $product;
    }
}
