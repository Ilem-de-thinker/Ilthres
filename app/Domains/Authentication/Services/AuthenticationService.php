<?php

namespace App\Domains\Authentication\Services;

use App\Models\User;

/**
 * Shared business logic for authentication workflows.
 *
 * Keeps cross-cutting concerns (logout, duplicate checks) in one place
 * so that Livewire components and API controllers stay thin.
 */
class AuthenticationService
{
    /** Logs out the current user and invalidates the session. */
    public function logout(): void
    {
        auth()->logout();

        session()->invalidate();
        session()->regenerateToken();
    }

    /** Checks whether an email address is already registered. */
    public function isEmailTaken(string $email): bool
    {
        return User::where('email', $email)->exists();
    }
}
