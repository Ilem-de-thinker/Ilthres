<?php

namespace App\Domains\Website\Events;

use App\Models\WebsiteSection;

class SectionUpdated
{
    public function __construct(
        public WebsiteSection $section,
    ) {}
}
