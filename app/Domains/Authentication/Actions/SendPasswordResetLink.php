<?php

namespace App\Domains\Authentication\Actions;

use Illuminate\Support\Facades\Password;

/**
 * Sends a password-reset link to the given email address.
 *
 * Wraps Laravel's built-in password broker. Returns the status string
 * so the caller can decide the appropriate response (e.g. "sent" vs "throttled").
 */
class SendPasswordResetLink
{
    public function __invoke(string $email): string
    {
        $status = Password::sendResetLink(['email' => $email]);

        return $status;
    }
}
