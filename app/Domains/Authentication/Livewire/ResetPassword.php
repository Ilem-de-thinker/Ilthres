<?php

namespace App\Domains\Authentication\Livewire;

use App\Domains\Authentication\Actions\ResetUserPassword;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;

/**
 * Handles the password reset form on the /reset-password/{token} page.
 *
 * Accepts the reset token from the URL, the user's email, and the new password.
 * Calls ResetUserPassword action and redirects to login on success.
 */
class ResetPassword extends Component
{
    public string $token;
    public string $email = '';
    public string $password = '';
    public string $passwordConfirmation = '';

    public function mount(string $token): void
    {
        $this->token = $token;
    }

    protected function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => [
                'required',
                'same:passwordConfirmation',
                Password::min(8)
                    ->mixedCase()
                    ->letters()
                    ->numbers()
                    ->symbols(),
            ],
        ];
    }

    protected $messages = [
        'password' => 'The password must be at least 8 characters with uppercase, lowercase, numbers, and a symbol.',
    ];

    public function resetPassword(ResetUserPassword $resetUserPassword): void
    {
        $this->validate();

        $resetUserPassword(
            email: $this->email,
            password: $this->password,
            passwordConfirmation: $this->passwordConfirmation,
            token: $this->token,
        );

        $this->redirect(route('login'), navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.reset-password');
    }
}
