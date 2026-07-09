<?php

namespace App\Domains\Subscription\Policies;

use App\Models\Subscription;
use App\Models\User;

class SubscriptionPolicy
{
    public function view(User $user, Subscription $subscription): bool
    {
        return $user->id === $subscription->business->user_id;
    }

    public function create(User $user): bool
    {
        return $user->businesses()->exists();
    }

    public function update(User $user, Subscription $subscription): bool
    {
        return $user->id === $subscription->business->user_id;
    }

    public function cancel(User $user, Subscription $subscription): bool
    {
        return $user->id === $subscription->business->user_id;
    }
}
