<?php

namespace App\Domains\Authentication\Events;

use App\Models\User;

/**
 * Fired when a new user completes registration.
 *
 * Listeners can send welcome emails, create default settings,
 * or trigger onboarding workflows.
 */
class UserRegistered
{
    public function __construct(
        /** The newly created user instance. */
        public User $user,
    ) {}
}
