<?php

namespace App\Domains\Business\Listeners;

use App\Domains\Business\Events\BusinessCreated;
/**
 * Keeps the search index in sync with business changes.
 */

class UpdateBusinessSearchIndexCreated{

    public function __invoke(BusinessCreated $event): void
    {
        $event->business->searchable();
        $event->business->updateQuietly(['last_indexed_at' => now()]);
    }
}
