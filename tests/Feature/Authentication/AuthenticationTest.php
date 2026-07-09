<?php

use App\Domains\Authentication\Livewire\ForgotPassword;
use App\Domains\Authentication\Livewire\Login;
use App\Domains\Authentication\Livewire\Register;
use App\Domains\Authentication\Livewire\ResetPassword;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Livewire\Livewire;

uses()->group('auth');

beforeEach(function () {
    Notification::fake();
});

describe('Registration', function () {
    it('renders the registration page', function () {
        $this->get('/register')->assertSuccessful();
    });

    it('can register a new user', function () {
        Livewire::test(Register::class)
            ->set('name', 'John Doe')
            ->set('email', 'john@example.com')
            ->set('password', 'Password1!')
            ->set('passwordConfirmation', 'Password1!')
            ->call('register')
            ->assertRedirect('/onboarding');

        expect(User::where('email', 'john@example.com')->exists())->toBeTrue();
    });

    it('requires name, email, password', function () {
        Livewire::test(Register::class)
            ->call('register')
            ->assertHasErrors(['name', 'email', 'password']);
    });

    it('requires valid email', function () {
        Livewire::test(Register::class)
            ->set('name', 'John')
            ->set('email', 'not-an-email')
            ->set('password', 'Password1!')
            ->set('passwordConfirmation', 'Password1!')
            ->call('register')
            ->assertHasErrors(['email']);
    });

    it('requires strong password', function () {
        Livewire::test(Register::class)
            ->set('name', 'John')
            ->set('email', 'john@example.com')
            ->set('password', 'weak')
            ->set('passwordConfirmation', 'weak')
            ->call('register')
            ->assertHasErrors(['password']);
    });

    it('requires password confirmation to match', function () {
        Livewire::test(Register::class)
            ->set('name', 'John')
            ->set('email', 'john@example.com')
            ->set('password', 'Password1!')
            ->set('passwordConfirmation', 'Different1!')
            ->call('register')
            ->assertHasErrors(['password']);
    });

    it('redirects authenticated users to dashboard', function () {
        $user = User::factory()->create(['onboarding_completed' => true]);
        $this->actingAs($user);

        $this->get('/register')->assertRedirect('/dashboard');
    });
});

describe('Login', function () {
    it('renders the login page', function () {
        $this->get('/login')->assertSuccessful();
    });

    it('can authenticate with valid credentials', function () {
        $user = User::factory()->create([
            'email' => 'jane@example.com',
            'password' => Hash::make('Password1!'),
        ]);

        Livewire::test(Login::class)
            ->set('email', 'jane@example.com')
            ->set('password', 'Password1!')
            ->call('login')
            ->assertRedirect('/dashboard');

        expect(auth()->check())->toBeTrue();
    });

    it('rejects invalid credentials', function () {
        $user = User::factory()->create([
            'email' => 'jane@example.com',
            'password' => Hash::make('Password1!'),
        ]);

        Livewire::test(Login::class)
            ->set('email', 'jane@example.com')
            ->set('password', 'wrong-password')
            ->call('login')
            ->assertHasErrors('email');

        expect(auth()->check())->toBeFalse();
    });

    it('requires email and password', function () {
        Livewire::test(Login::class)
            ->call('login')
            ->assertHasErrors(['email', 'password']);
    });

    it('redirects authenticated users to dashboard', function () {
        $user = User::factory()->create(['onboarding_completed' => true]);
        $this->actingAs($user);

        $this->get('/login')->assertRedirect('/dashboard');
    });

    it('rate limits login attempts', function () {
        $user = User::factory()->create([
            'email' => 'rate@example.com',
            'password' => Hash::make('Password1!'),
        ]);

        for ($i = 0; $i < 5; $i++) {
            Livewire::test(Login::class)
                ->set('email', 'rate@example.com')
                ->set('password', 'wrong')
                ->call('login');
        }

        Livewire::test(Login::class)
            ->set('email', 'rate@example.com')
            ->set('password', 'Password1!')
            ->call('login')
            ->assertHasErrors('email');
    });
});

describe('Logout', function () {
    it('can log out', function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->post('/logout')->assertRedirect('/');

        expect(auth()->check())->toBeFalse();
    });
});

describe('Password Reset', function () {
    it('renders forgot password page', function () {
        $this->get('/forgot-password')->assertSuccessful();
    });

    it('sends password reset email', function () {
        $user = User::factory()->create(['email' => 'reset@example.com']);

        Livewire::test(ForgotPassword::class)
            ->set('email', 'reset@example.com')
            ->call('sendResetLink')
            ->assertSee('If that email address is registered, you will receive a password reset link shortly.');

        Notification::assertSentTo($user, ResetPasswordNotification::class);
    });

    it('requires valid email for reset', function () {
        Livewire::test(ForgotPassword::class)
            ->set('email', 'not-an-email')
            ->call('sendResetLink')
            ->assertHasErrors(['email']);
    });

    it('renders reset password page with token', function () {
        $user = User::factory()->create();
        $token = Password::createToken($user);

        $this->get("/reset-password/{$token}")->assertSuccessful();
    });

    it('can reset password with valid token', function () {
        $user = User::factory()->create(['email' => 'reset2@example.com']);
        $token = Password::createToken($user);

        Livewire::test(ResetPassword::class, ['token' => $token])
            ->set('email', 'reset2@example.com')
            ->set('password', 'NewPass1!')
            ->set('passwordConfirmation', 'NewPass1!')
            ->call('resetPassword')
            ->assertRedirect('/login');

        expect(Hash::check('NewPass1!', $user->fresh()->password))->toBeTrue();
    });
});
