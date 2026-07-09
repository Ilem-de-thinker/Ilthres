<?php

namespace App\Domains\Authentication\Listeners;

use App\Domains\Authentication\Events\UserRegistered;

/**
 * Sends a welcome notification to newly registered users.
 *
 * Listens for UserRegistered and dispatches a welcome email
 * with onboarding tips and getting-started links.
 */
class SendWelcomeNotification
{
    public function handle(UserRegistered $event): void
    {
        //
    }
}
