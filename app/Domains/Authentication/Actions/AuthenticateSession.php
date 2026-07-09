<?php

namespace App\Domains\Authentication\Actions;

use App\Domains\Authentication\Events\UserLoggedIn;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

/**
 * Authenticates a user session with email + password credentials.
 *
 * Delegates to Laravel's Auth guard for credential verification.
 * Throws a validation exception (redirect-friendly) on failure,
 * fires UserLoggedIn on success, and returns the authenticated user.
 */
class AuthenticateSession
{
    public function __invoke(string $email, string $password, bool $remember = false): User
    {
        if (! Auth::attempt(['email' => $email, 'password' => $password], $remember)) {
            throw ValidationException::withMessages([
                'email' => 'These credentials do not match our records.',
            ]);
        }

        session()->regenerate();

        /** @var User $user */
        $user = Auth::user();

        event(new UserLoggedIn($user));

        return $user;
    }
}
