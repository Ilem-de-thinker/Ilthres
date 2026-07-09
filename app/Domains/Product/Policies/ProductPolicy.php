<?php

namespace App\Domains\Product\Policies;

use App\Models\Product;
use App\Models\User;

/**
 * Authorizes access to product resources.
 *
 * Any visitor can view products. Only the business owner
 * (the user who created the business) can create, update, or delete products.
 */
class ProductPolicy
{
    /** Anyone can browse the product catalogue. */
    public function viewAny(User $user, Product $product): bool
    {
        return true;
    }

    /** Anyone can view a single product. */
    public function view(User $user, Product $product): bool
    {
        return true;
    }

    /** Only merchants with a business can create products. */
    public function create(User $user): bool
    {
        return $user->businesses()->exists();
    }

    /** Only the business owner can edit products. */
    public function update(User $user, Product $product): bool
    {
        return $user->id === $product->business->user_id;
    }

    /** Only the business owner can delete products. */
    public function delete(User $user, Product $product): bool
    {
        return $user->id === $product->business->user_id;
    }
}
