<?php

namespace App\Domains\Business\Actions;

use App\Domains\Business\Events\LocationDeleted;
use App\Models\BusinessLocation;

class DeleteLocation
{
    public function __invoke(BusinessLocation $location): void
    {
        $location->delete();

        event(new LocationDeleted($location));
    }
}
