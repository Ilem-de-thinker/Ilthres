<?php

namespace App\Domains\Authentication\Events;

use App\Models\User;

/**
 * Fired when a user logs out or has their session invalidated.
 *
 * Can be used to clear ephemeral state, stop tracking,
 * or log session duration for analytics.
 */
class UserLoggedOut
{
    public function __construct(
        /** The user who logged out. */
        public User $user,
    ) {}
}
