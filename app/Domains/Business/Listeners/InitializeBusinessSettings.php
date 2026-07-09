<?php

namespace App\Domains\Business\Listeners;

use App\Domains\Business\Events\BusinessCreated;

/**
 * Sets up default configurations for a newly created business.
 *
 * Assigns the default theme, creates initial analytics records,
 * and flags the business for any first-run setup tours.
 */
class InitializeBusinessSettings
{
    public function handle(BusinessCreated $event): void
    {
        // Default theme assignment happens here (Phase 1 Feature 7).
        // For now, the business uses the platform's default theme.
    }
}
