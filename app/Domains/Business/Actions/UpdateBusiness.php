<?php

namespace App\Domains\Business\Actions;

use App\Domains\Business\DTOs\BusinessData;
use App\Domains\Business\Events\BusinessUpdated;
use App\Models\Business;

class UpdateBusiness
{
    public function __invoke(Business $business, BusinessData $data): Business
    {
        $business->update([
            'name' => $data->name,
            'slug' => $data->slug,
            'description' => $data->description,
            'about' => $data->about,
            'email' => $data->email,
            'phone' => $data->phone,
            'whatsapp' => $data->whatsapp,
            'address' => $data->address,
            'city' => $data->city,
            'state' => $data->state,
            'country' => $data->country,
            'latitude' => $data->latitude,
            'longitude' => $data->longitude,
            'category_id' => $data->categoryId,
            'is_discoverable' => $data->isDiscoverable,
        ]);

        event(new BusinessUpdated($business));

        return $business;
    }
}
