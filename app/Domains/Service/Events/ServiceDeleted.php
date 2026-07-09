<?php

namespace App\Domains\Service\Events;

use App\Models\Service;

class ServiceDeleted
{
    public function __construct(
        public Service $service,
    ) {}
}
