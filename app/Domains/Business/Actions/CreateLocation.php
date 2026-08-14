<?php

namespace App\Domains\Business\Actions;

use App\Domains\Business\DTOs\LocationData;
use App\Domains\Business\Events\LocationCreated;
use App\Models\BusinessLocation;

class CreateLocation
{
    public function __invoke(LocationData $data): BusinessLocation
    {
        if ($data->isPrimary) {
            BusinessLocation::where('business_id', $data->businessId)
                ->where('is_primary', true)
                ->update(['is_primary' => false]);
        }

        $location = BusinessLocation::create([
            'business_id' => $data->businessId,
            'country' => $data->country,
            'state' => $data->state,
            'city' => $data->city,
            'address' => $data->address,
            'postal_code' => $data->postalCode,
            'latitude' => $data->latitude,
            'longitude' => $data->longitude,
            'phone' => $data->phone,
            'email' => $data->email,
            'opening_hours' => $data->openingHours,
            'is_primary' => $data->isPrimary,
            'is_headquarters' => $data->isHeadquarters,
        ]);

        event(new LocationCreated($location));

        return $location;
    }
}
