<?php

namespace App\Domains\Service\Actions;

use App\Domains\Service\DTOs\ServiceData;
use App\Domains\Service\Events\ServiceUpdated;
use App\Models\Service;

class UpdateService
{
    public function __invoke(Service $service, ServiceData $data): Service
    {
        $service->update([
            'category_id' => $data->categoryId,
            'name' => $data->name,
            'slug' => $data->slug,
            'description' => $data->description,
            'price' => $data->price,
            'currency' => $data->currency,
            'duration_minutes' => $data->durationMinutes,
            'images' => $data->images,
            'is_active' => $data->isActive,
            'is_public' => $data->isPublic,
            'sort_order' => $data->sortOrder,
        ]);

        event(new ServiceUpdated($service));

        return $service;
    }
}
