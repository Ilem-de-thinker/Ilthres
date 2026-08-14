<?php

namespace App\Domains\Business\Events;

use App\Models\BusinessLocation;

class LocationUpdated
{
    public function __construct(
        public BusinessLocation $location,
    ) {}
}
