<?php

namespace App\Providers;

use App\Domains\Authentication\Events\UserRegistered;
use App\Domains\Authentication\Listeners\SendWelcomeNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

/**
 * Registers event-to-listener mappings for the application.
 *
 * Domains communicate exclusively through events. Listeners are
 * registered here as the single source of truth for event wiring.
 */
class EventServiceProvider extends ServiceProvider
{
    /** Event-to-listener mappings. */
    protected $listen = [
        // ─── Authentication ───────────────────────────
        UserRegistered::class => [
            SendWelcomeNotification::class,
        ],
    ];

    /** Model observers to register. */
    protected $observers = [];

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
