<?php

namespace App\Domains\Website\Events;

use App\Models\Website;

/**
 * Fired when a website is activated and becomes publicly accessible.
 */
class WebsitePublished
{
    public function __construct(
        public Website $website,
    ) {}
}
