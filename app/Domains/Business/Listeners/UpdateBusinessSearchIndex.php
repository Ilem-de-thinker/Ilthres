<?php

namespace App\Domains\Business\Listeners;

use App\Domains\Business\Events\BusinessCreated;
use App\Domains\Business\Events\BusinessDeleted;
use App\Domains\Business\Events\BusinessUpdated;

/**
 * Keeps the search index in sync with business changes.
 */
class UpdateBusinessSearchIndex
{
    public function created(BusinessCreated $event): void
    {
        $event->business->searchable();
        $event->business->updateQuietly(['last_indexed_at' => now()]);
    }

    public function updated(BusinessUpdated $event): void
    {
        $event->business->searchable();
        $event->business->updateQuietly(['last_indexed_at' => now()]);
    }

    public function deleted(BusinessDeleted $event): void
    {
        $event->business->unsearchable();
    }
}
