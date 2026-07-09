<?php

namespace App\Domains\Service\Events;

use App\Models\Service;

class ServiceViewed
{
    public function __construct(
        public Service $service,
    ) {}
}
