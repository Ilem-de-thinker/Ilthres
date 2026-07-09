<?php

namespace App\Domains\Business\Policies;

use App\Models\Business;
use App\Models\User;

/**
 * Authorizes access to business profile resources.
 *
 * Any visitor can view a public business profile.
 * Only the business owner can edit settings or delete the business.
 */
class BusinessPolicy
{
    /** Anyone can view a public business page. */
    public function view(?User $user, Business $business): bool
    {
        return true;
    }

    /** Only the owner can update business details. */
    public function update(User $user, Business $business): bool
    {
        return $user->id === $business->user_id;
    }

    /** Only the owner can delete their business. */
    public function delete(User $user, Business $business): bool
    {
        return $user->id === $business->user_id;
    }
}
