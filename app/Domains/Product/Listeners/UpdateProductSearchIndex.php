<?php

namespace App\Domains\Product\Listeners;

use App\Domains\Product\Events\ProductCreated;
use App\Domains\Product\Events\ProductDeleted;
use App\Domains\Product\Events\ProductUpdated;

/**
 * Keeps the search index in sync with product changes.
 *
 * Listens to ProductCreated, ProductUpdated, and ProductDeleted
 * to add, update, or remove products from the search index.
 */
class UpdateProductSearchIndex
{
    public function created(ProductCreated $event): void
    {
        //
    }

    public function updated(ProductUpdated $event): void
    {
        //
    }

    public function deleted(ProductDeleted $event): void
    {
        //
    }
}
