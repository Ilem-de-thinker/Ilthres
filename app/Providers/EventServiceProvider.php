<?php

namespace App\Providers;

use App\Domains\Authentication\Events\UserRegistered;
use App\Domains\Authentication\Listeners\SendWelcomeNotification;
use App\Domains\Business\Events\BusinessCreated;
use App\Domains\Business\Events\BusinessDeleted;
use App\Domains\Business\Events\BusinessUpdated;
use App\Domains\Business\Events\VerificationApproved;
use App\Domains\Business\Events\VerificationRejected;
use App\Domains\Business\Events\VerificationSubmitted;
use App\Domains\Business\Listeners\InitializeBusinessSettings;
use App\Domains\Business\Listeners\UpdateBusinessSearchIndex;
use App\Domains\Business\Listeners\UpdateBusinessSearchIndexCreated;
use App\Domains\Business\Listeners\UpdateBusinessSearchIndexUpdted;
use App\Domains\Product\Events\ProductCreated;
use App\Domains\Product\Events\ProductDeleted;
use App\Domains\Product\Events\ProductUpdated;
use App\Domains\Product\Listeners\UpdateProductSearchIndex;
use App\Domains\Service\Events\ServiceCreated;
use App\Domains\Service\Events\ServiceDeleted;
use App\Domains\Service\Events\ServiceUpdated;
use App\Domains\Service\Listeners\UpdateServiceSearchIndex;
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

        // ─── Business ─────────────────────────────────
        BusinessCreated::class => [
            InitializeBusinessSettings::class,
            UpdateBusinessSearchIndexCreated::class,
        ],
        BusinessUpdated::class => [
            UpdateBusinessSearchIndexUpdted::class,
        ],
        BusinessDeleted::class => [
            UpdateBusinessSearchIndex::class,
        ],

        // ─── Products ─────────────────────────────────
        ProductCreated::class => [
            UpdateProductSearchIndex::class,
        ],
        ProductUpdated::class => [
            UpdateProductSearchIndex::class,
        ],
        ProductDeleted::class => [
            UpdateProductSearchIndex::class,
        ],

        // ─── Services ─────────────────────────────────
        ServiceCreated::class => [
            UpdateServiceSearchIndex::class,
        ],
        ServiceUpdated::class => [
            UpdateServiceSearchIndex::class,
        ],
        ServiceDeleted::class => [
            UpdateServiceSearchIndex::class,
        ],

        // ─── Verification ──────────────────────────────
        VerificationSubmitted::class => [],
        VerificationApproved::class => [],
        VerificationRejected::class => [],
    ];

    /** Model observers to register. */
    protected $observers = [];

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
