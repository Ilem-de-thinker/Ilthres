<?php

namespace App\Domains\Authentication\Events;

use App\Models\User;

/**
 * Fired when a user successfully authenticates.
 *
 * Useful for auditing, updating last-login timestamps,
 * or restoring previous session data.
 */
class UserLoggedIn
{
    public function __construct(
        /** The authenticated user instance. */
        public User $user,
    ) {}
}
