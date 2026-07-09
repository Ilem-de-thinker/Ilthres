<?php

namespace App\Domains\Service\Policies;

use App\Models\Service;
use App\Models\User;

class ServicePolicy
{
    public function viewAny(User $user, Service $service): bool
    {
        return true;
    }

    public function view(User $user, Service $service): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->businesses()->exists();
    }

    public function update(User $user, Service $service): bool
    {
        return $user->id === $service->business->user_id;
    }

    public function delete(User $user, Service $service): bool
    {
        return $user->id === $service->business->user_id;
    }
}
