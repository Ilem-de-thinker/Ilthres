<?php

namespace App\Domains\Service\Events;

use App\Models\Service;

class ServiceUpdated
{
    public function __construct(
        public Service $service,
    ) {}
}
