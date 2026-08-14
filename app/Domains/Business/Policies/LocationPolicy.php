<?php

namespace App\Domains\Business\Policies;

use App\Models\BusinessLocation;
use App\Models\User;

class LocationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->businesses()->exists();
    }

    public function view(User $user, BusinessLocation $location): bool
    {
        return $user->id === $location->business->user_id;
    }

    public function create(User $user): bool
    {
        return $user->businesses()->exists();
    }

    public function update(User $user, BusinessLocation $location): bool
    {
        return $user->id === $location->business->user_id;
    }

    public function delete(User $user, BusinessLocation $location): bool
    {
        return $user->id === $location->business->user_id;
    }
}
