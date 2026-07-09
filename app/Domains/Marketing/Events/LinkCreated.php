<?php

namespace App\Domains\Marketing\Events;

use App\Models\MarketingLink;

class LinkCreated
{
    public function __construct(
        public MarketingLink $link,
    ) {}
}
