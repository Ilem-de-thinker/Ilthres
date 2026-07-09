<?php

namespace App\Domains\Marketing\Events;

use App\Models\PromoBanner;

class PromoBannerUpdated
{
    public function __construct(
        public PromoBanner $banner,
    ) {}
}
