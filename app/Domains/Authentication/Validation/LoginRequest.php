<?php

namespace App\Domains\Authentication\Validation;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validates login credentials before authentication.
 *
 * Ensures email is well-formed and password is present.
 * Authentication failure messaging is handled by AuthenticateSession.
 */
class LoginRequest extends FormRequest
{
    /** Allow all users to submit login requests (guests + auth both valid). */
    public function authorize(): bool
    {
        return true;
    }

    /** Email must be valid format; password must be non-empty. */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ];
    }
}
