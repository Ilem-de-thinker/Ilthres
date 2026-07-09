<?php

namespace App\Domains\Business\Events;

use App\Models\Business;

/**
 * Fired when a business profile is updated.
 *
 * Listeners may re-index search data, clear cached public pages,
 * or notify team members of the change.
 */
class BusinessUpdated
{
    public function __construct(
        public Business $business,
    ) {}
}
