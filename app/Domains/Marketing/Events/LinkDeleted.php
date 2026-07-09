<?php

namespace App\Domains\Marketing\Events;

use App\Models\MarketingLink;

class LinkDeleted
{
    public function __construct(
        public MarketingLink $link,
    ) {}
}
