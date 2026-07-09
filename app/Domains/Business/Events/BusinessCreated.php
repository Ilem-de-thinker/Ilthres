<?php

namespace App\Domains\Business\Events;

use App\Models\Business;

/**
 * Fired when a new business profile is created via onboarding.
 *
 * Listeners initialize default settings, create the business-theme pivot,
 * set up analytics tracking, and trigger any post-creation workflows.
 */
class BusinessCreated
{
    public function __construct(
        public Business $business,
    ) {}
}
