<?php

namespace App\Domains\Subscription\Events;

use App\Models\Subscription;

class SubscriptionCreated
{
    public function __construct(
        public Subscription $subscription,
    ) {}
}
