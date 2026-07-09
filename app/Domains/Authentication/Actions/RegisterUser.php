<?php

namespace App\Domains\Authentication\Actions;

use App\Domains\Authentication\DTOs\UserData;
use App\Domains\Authentication\Events\UserRegistered;
use App\Models\User;

/**
 * Registers a new user account.
 *
 * Creates the User model from the provided DTO, fires UserRegistered
 * so that downstream listeners (welcome email, default setup) can react,
 * and returns the fresh user instance.
 */
class RegisterUser
{
    public function __invoke(UserData $data): User
    {
        $user = User::create([
            'name' => $data->name,
            'email' => $data->email,
            'password' => $data->password,
        ]);

        event(new UserRegistered($user));

        return $user;
    }
}
