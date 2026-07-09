<?php

namespace App\Domains\Business\Events;

use App\Models\Business;

/**
 * Fired when a business profile is soft-deleted.
 *
 * Listeners may deactivate the business theme, cancel subscriptions,
 * and mark products/services as unavailable.
 */
class BusinessDeleted
{
    public function __construct(
        public Business $business,
    ) {}
}
