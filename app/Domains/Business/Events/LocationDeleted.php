<?php

namespace App\Domains\Business\Events;

use App\Models\BusinessLocation;

class LocationDeleted
{
    public function __construct(
        public BusinessLocation $location,
    ) {}
}
