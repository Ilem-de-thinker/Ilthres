<?php

namespace App\Domains\Service\Actions;

use App\Domains\Service\Events\ServiceDeleted;
use App\Models\Service;

class DeleteService
{
    public function __invoke(Service $service): void
    {
        $service->delete();

        event(new ServiceDeleted($service));
    }
}
