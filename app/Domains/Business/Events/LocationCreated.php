<?php

namespace App\Domains\Business\Events;

use App\Models\BusinessLocation;

class LocationCreated
{
    public function __construct(
        public BusinessLocation $location,
    ) {}
}
