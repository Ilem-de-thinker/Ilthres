<?php

namespace App\Domains\Authentication\Actions;

use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

/**
 * Resets a user's password using a valid reset token.
 *
 * Delegates to Laravel's password broker. Throws a validation exception
 * if the token is invalid, expired, or the email is not found.
 */
class ResetUserPassword
{
    public function __invoke(string $email, string $password, string $passwordConfirmation, string $token): void
    {
        $status = Password::reset(
            ['email' => $email, 'password' => $password, 'password_confirmation' => $passwordConfirmation, 'token' => $token],
            function ($user, $password) {
                $user->forceFill(['password' => $password])->save();
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            throw ValidationException::withMessages([
                'email' => __($status),
            ]);
        }
    }
}
