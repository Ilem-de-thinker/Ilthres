<?php

namespace App\Domains\Authentication\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates that a password meets minimum strength requirements.
 *
 * Enforces at least one uppercase, one lowercase, one digit,
 * and one special character. Used during registration and password reset.
 */
class StrongPassword implements ValidationRule
{
    /** Checks each requirement individually for clear error messages. */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! preg_match('/[A-Z]/', $value)) {
            $fail('The :attribute must contain at least one uppercase letter.');
        }

        if (! preg_match('/[a-z]/', $value)) {
            $fail('The :attribute must contain at least one lowercase letter.');
        }

        if (! preg_match('/[0-9]/', $value)) {
            $fail('The :attribute must contain at least one number.');
        }

        if (! preg_match('/[!@#$%^&*(),.?":{}|<>]/', $value)) {
            $fail('The :attribute must contain at least one special character.');
        }
    }
}
