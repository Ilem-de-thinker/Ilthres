<?php

namespace App\Domains\Authentication\Livewire;

use App\Domains\Authentication\Actions\AuthenticateSession;
use Illuminate\Cache\RateLimiter;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

/**
 * Handles the login form on the /login page.
 *
 * Rate-limited to 5 attempts per minute per email+IP combination
 * to prevent brute-force attacks.
 */
class Login extends Component
{
    public string $email = '';

    public string $password = '';

    public bool $remember = false;

    protected function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ];
    }

    /**
     * Attempt authentication with rate limiting.
     *
     * Uses Laravel's RateLimiter to throttle login attempts.
     * After 5 failed attempts, the user must wait 1 minute.
     */
    public function login(AuthenticateSession $authenticateSession, RateLimiter $limiter): void
    {
        $this->validate();

        // Throttle key = email + IP to prevent distributed brute force.
        $throttleKey = 'login:'.strtolower($this->email).'|'.request()->ip();

        // Check if too many attempts.
        if ($limiter->tooManyAttempts($throttleKey, 5)) {
            $seconds = $limiter->availableIn($throttleKey);

            $this->addError('email', trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]));

            return;
        }

        try {
            $authenticateSession($this->email, $this->password, $this->remember);

            // Clear rate limiter on success.
            $limiter->clear($throttleKey);

            $redirectUrl = auth()->user()->is_admin ? route('admin.dashboard') : route('dashboard');

            $this->redirect($redirectUrl, navigate: true);

        } catch (ValidationException) {
            // Hit — increment the counter and surface the error.
            $limiter->hit($throttleKey, 60); // 60-second decay window.

            $this->addError('email', 'These credentials do not match our records.');
        }
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
