<?php

namespace App\Domains\Analytics\Events;

use App\Models\Business;

class PhoneClicked
{
    public function __construct(
        public Business $business,
    ) {}
}
