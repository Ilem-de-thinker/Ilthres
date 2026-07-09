<?php

namespace App\Domains\Promotion\Policies;

use App\Models\Promotion;
use App\Models\User;

class PromotionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->businesses()->exists();
    }

    public function view(User $user, Promotion $promotion): bool
    {
        return $promotion->business->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->businesses()->exists();
    }

    public function update(User $user, Promotion $promotion): bool
    {
        return $promotion->business->user_id === $user->id;
    }

    public function delete(User $user, Promotion $promotion): bool
    {
        return $promotion->business->user_id === $user->id;
    }
}
