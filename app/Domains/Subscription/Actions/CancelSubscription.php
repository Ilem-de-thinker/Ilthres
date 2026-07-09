<?php

namespace App\Domains\Subscription\Actions;

use App\Domains\Subscription\Events\SubscriptionCancelled;
use App\Models\Subscription;

class CancelSubscription
{
    public function __invoke(Subscription $subscription): Subscription
    {
        $subscription->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

        event(new SubscriptionCancelled($subscription));

        return $subscription->fresh();
    }
}
