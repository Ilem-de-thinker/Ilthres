<?php

namespace App\Domains\Service\Events;

use App\Models\Service;

class ServiceCreated
{
    public function __construct(
        public Service $service,
    ) {}
}
