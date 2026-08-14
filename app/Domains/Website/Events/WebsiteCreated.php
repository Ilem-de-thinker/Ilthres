<?php

namespace App\Domains\Website\Events;

use App\Models\Website;

/**
 * Fired when a new website is created for a business.
 */
class WebsiteCreated
{
    public function __construct(
        public Website $website,
    ) {}
}
