<?php

namespace App\Domains\Business\Actions;

use App\Models\BusinessLocation;

class SetPrimaryLocation
{
    public function __invoke(BusinessLocation $location): BusinessLocation
    {
        BusinessLocation::where('business_id', $location->business_id)
            ->where('is_primary', true)
            ->update(['is_primary' => false]);

        $location->update(['is_primary' => true]);

        return $location;
    }
}
