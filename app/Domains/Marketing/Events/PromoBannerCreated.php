<?php

namespace App\Domains\Marketing\Events;

use App\Models\PromoBanner;

class PromoBannerCreated
{
    public function __construct(
        public PromoBanner $banner,
    ) {}
}
