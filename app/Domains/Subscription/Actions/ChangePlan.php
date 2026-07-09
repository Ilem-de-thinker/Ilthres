<?php

namespace App\Domains\Subscription\Actions;

use App\Domains\Subscription\Events\SubscriptionPlanChanged;
use App\Models\Plan;
use App\Models\Subscription;

class ChangePlan
{
    public function __invoke(Subscription $subscription, Plan $newPlan, ?array $gatewayData = null): Subscription
    {
        $oldPlan = $subscription->plan;

        $subscription->update([
            'plan_id' => $newPlan->id,
            'gateway_data' => $gatewayData,
        ]);

        event(new SubscriptionPlanChanged($subscription, $oldPlan));

        return $subscription->fresh();
    }
}
