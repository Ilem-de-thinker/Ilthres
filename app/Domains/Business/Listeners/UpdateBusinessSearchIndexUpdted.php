<?php

namespace App\Domains\Business\Listeners;


use App\Domains\Business\Events\BusinessUpdated;

/**
 * Keeps the search index in sync with business changes.
 */
class UpdateBusinessSearchIndexUpdted
{
    public function __invoke(BusinessUpdated $event): void
    {
        $event->business->searchable();
        $event->business->updateQuietly(['last_indexed_at' => now()]);
    }

}
