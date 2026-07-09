<?php

namespace App\Domains\Marketing\Events;

use App\Models\LinkClick;
use App\Models\MarketingLink;

class LinkClicked
{
    public function __construct(
        public MarketingLink $link,
        public LinkClick $click,
    ) {}
}
