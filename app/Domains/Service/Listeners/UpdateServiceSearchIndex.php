<?php

namespace App\Domains\Service\Listeners;

use App\Domains\Service\Events\ServiceCreated;
use App\Domains\Service\Events\ServiceDeleted;
use App\Domains\Service\Events\ServiceUpdated;

/**
 * Keeps the search index in sync with service changes.
 */
class UpdateServiceSearchIndex
{
    public function created(ServiceCreated $event): void
    {
        $event->service->searchable();
        $event->service->updateQuietly(['last_indexed_at' => now()]);
    }

    public function updated(ServiceUpdated $event): void
    {
        $event->service->searchable();
        $event->service->updateQuietly(['last_indexed_at' => now()]);
    }

    public function deleted(ServiceDeleted $event): void
    {
        $event->service->unsearchable();
    }
}
