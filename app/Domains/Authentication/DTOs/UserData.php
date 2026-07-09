<?php

namespace App\Domains\Authentication\DTOs;

/**
 * Data transfer object for user registration.
 *
 * Carries the minimum required fields to create a new user account.
 * Password is expected to be already hashed by the caller.
 */
class UserData
{
    public function __construct(
        /** The display name shown on the profile and dashboard. */
        public readonly string $name,

        /** Unique email address used as the login credential. */
        public readonly string $email,

        /** Plain-text password — hashing is handled by the model's cast. */
        public readonly string $password,
    ) {}
}
