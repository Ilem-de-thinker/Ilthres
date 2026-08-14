<?php

namespace App\Domains\Product\Listeners;

use App\Domains\Product\Events\ProductCreated;
use App\Domains\Product\Events\ProductDeleted;
use App\Domains\Product\Events\ProductUpdated;

/**
 * Keeps the search index in sync with product changes.
 */
class UpdateProductSearchIndex
{
    public function created(ProductCreated $event): void
    {
        $event->product->searchable();
        $event->product->updateQuietly(['last_indexed_at' => now()]);
    }

    public function updated(ProductUpdated $event): void
    {
        $event->product->searchable();
        $event->product->updateQuietly(['last_indexed_at' => now()]);
    }

    public function deleted(ProductDeleted $event): void
    {
        $event->product->unsearchable();
    }
}
