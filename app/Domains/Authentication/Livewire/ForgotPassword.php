<?php

namespace App\Domains\Authentication\Livewire;

use App\Domains\Authentication\Actions\SendPasswordResetLink;
use Illuminate\Cache\RateLimiter;
use Livewire\Component;

/**
 * Handles the password reset request form on the /forgot-password page.
 *
 * Rate-limited to 3 requests per minute per email+IP combination
 * to prevent abuse of the password-reset endpoint.
 */
class ForgotPassword extends Component
{
    public string $email = '';

    public ?string $status = null;

    protected function rules(): array
    {
        return [
            'email' => ['required', 'email'],
        ];
    }

    /**
     * Send a password-reset link with rate limiting.
     *
     * After 3 attempts the user must wait 1 minute.
     * Always shows the same status message whether the email exists or not
     * to prevent email enumeration.
     */
    public function sendResetLink(SendPasswordResetLink $sendPasswordResetLink, RateLimiter $limiter): void
    {
        $this->validate();

        $throttleKey = 'password-reset:'.strtolower($this->email).'|'.request()->ip();

        if ($limiter->tooManyAttempts($throttleKey, 3)) {
            $seconds = $limiter->availableIn($throttleKey);

            $this->addError('email', trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]));

            return;
        }

        $limiter->hit($throttleKey, 60);

        // Always show the same message to prevent email enumeration.
        $sendPasswordResetLink($this->email);

        $this->status = 'If that email address is registered, you will receive a password reset link shortly.';
    }

    public function render()
    {
        return view('livewire.auth.forgot-password');
    }
}
