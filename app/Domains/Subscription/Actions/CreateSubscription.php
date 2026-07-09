<?php

namespace App\Domains\Subscription\Actions;

use App\Domains\Subscription\DTOs\SubscriptionData;
use App\Domains\Subscription\Events\SubscriptionCreated;
use App\Models\Subscription;

class CreateSubscription
{
    public function __invoke(SubscriptionData $data): Subscription
    {
        $subscription = Subscription::create([
            'business_id' => $data->businessId,
            'plan_id' => $data->planId,
            'status' => $data->status,
            'gateway' => $data->gateway,
            'gateway_reference' => $data->gatewayReference,
            'gateway_data' => $data->gatewayData,
            'trial_ends_at' => $data->trialEndsAt,
            'starts_at' => $data->startsAt ?? now(),
            'ends_at' => $data->endsAt,
        ]);

        event(new SubscriptionCreated($subscription));

        return $subscription;
    }
}
