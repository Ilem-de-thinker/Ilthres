<?php

namespace App\Domains\Authentication\Policies;

use App\Models\User;

/**
 * Authorizes access to user-profile resources.
 *
 * Users can only view, update, or delete their own profile.
 * Admin-level override is intentionally excluded — Phase 4 will
 * introduce an Admin domain with its own policy.
 */
class UserPolicy
{
    /** Only the account owner can view their profile. */
    public function view(User $user, User $target): bool
    {
        return $user->id === $target->id;
    }

    /** Only the account owner can update their details. */
    public function update(User $user, User $target): bool
    {
        return $user->id === $target->id;
    }

    /** Only the account owner can delete their account. */
    public function delete(User $user, User $target): bool
    {
        return $user->id === $target->id;
    }
}
