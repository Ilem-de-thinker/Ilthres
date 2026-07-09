<?php

namespace App\Domains\Notifications\Actions;

use App\Models\User;

class UpdatePreferences
{
    public function __invoke(User $user, array $preferences): void
    {
        $user->update([
            'notification_preferences' => $preferences,
        ]);
    }
}
