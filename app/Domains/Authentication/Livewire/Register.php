<?php

namespace App\Domains\Authentication\Livewire;

use App\Domains\Authentication\Actions\RegisterUser;
use App\Domains\Authentication\DTOs\UserData;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;

/**
 * Handles the registration form on the /register page.
 *
 * Enforces strong passwords (mixed case, numbers, symbols, minimum 8 chars).
 * After successful registration, the user is redirected to onboarding.
 */
class Register extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $passwordConfirmation = '';
    public bool $agree = false;

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email', 'max:255'],
            'password' => [
                'required',
                'same:passwordConfirmation',
                Password::min(8)
                    ->mixedCase()   // At least one uppercase and one lowercase.
                    ->letters()     // At least one letter.
                    ->numbers()     // At least one digit.
                    ->symbols(),    // At least one special character.
            ],
            'agree' => ['accepted'],
        ];
    }

    protected $messages = [
        'password' => 'The password must be at least 8 characters with uppercase, lowercase, numbers, and a symbol.',
        'agree.accepted' => 'You must agree to the Terms & Conditions and Privacy Policy.',
    ];

    public function register(RegisterUser $registerUser): void
    {
        $this->validate();

        $registerUser(new UserData(
            name: $this->name,
            email: $this->email,
            password: $this->password,
        ));

        auth()->attempt(['email' => $this->email, 'password' => $this->password]);
        session()->regenerate();

        // Mark onboarding as not started — middleware will redirect to /onboarding.
        auth()->user()->forceFill(['onboarding_step' => 0, 'onboarding_completed' => false])->save();

        $this->redirect(route('onboarding'), navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
