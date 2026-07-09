<?php

namespace App\Domains\Subscription\Events;

use App\Models\Plan;
use App\Models\Subscription;

class SubscriptionPlanChanged
{
    public function __construct(
        public Subscription $subscription,
        public Plan $oldPlan,
    ) {}
}
