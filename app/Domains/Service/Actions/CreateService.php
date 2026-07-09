<?php

namespace App\Domains\Service\Actions;

use App\Domains\Service\DTOs\ServiceData;
use App\Domains\Service\Events\ServiceCreated;
use App\Models\Service;

class CreateService
{
    public function __invoke(ServiceData $data): Service
    {
        $service = Service::create([
            'business_id' => $data->businessId,
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

        event(new ServiceCreated($service));

        return $service;
    }
}
