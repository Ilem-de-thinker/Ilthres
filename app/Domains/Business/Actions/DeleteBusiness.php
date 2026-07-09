<?php

namespace App\Domains\Business\Actions;

use App\Domains\Business\Events\BusinessDeleted;
use App\Models\Business;

/**
 * Soft-deletes a business profile.
 *
 * The business is soft-deleted so data can be recovered within 30 days.
 * Fires BusinessDeleted so listeners can clean up related resources.
 */
class DeleteBusiness
{
    public function __invoke(Business $business): void
    {
        $business->delete();

        event(new BusinessDeleted($business));
    }
}
