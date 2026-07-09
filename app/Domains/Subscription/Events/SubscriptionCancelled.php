<?php

namespace App\Domains\Subscription\Events;

use App\Models\Subscription;

class SubscriptionCancelled
{
    public function __construct(
        public Subscription $subscription,
    ) {}
}
