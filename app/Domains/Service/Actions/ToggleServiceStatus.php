<?php

namespace App\Domains\Service\Actions;

use App\Models\Service;

class ToggleServiceStatus
{
    public function __invoke(Service $service): Service
    {
        $service->update(['is_active' => !$service->is_active]);

        return $service;
    }
}
