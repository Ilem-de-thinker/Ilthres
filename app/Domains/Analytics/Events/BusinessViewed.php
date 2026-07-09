<?php

namespace App\Domains\Analytics\Events;

use App\Models\Business;

class BusinessViewed
{
    public function __construct(
        public Business $business,
        public ?string $source = null,
    ) {}
}
