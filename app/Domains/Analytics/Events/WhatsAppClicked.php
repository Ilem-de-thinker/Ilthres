<?php

namespace App\Domains\Analytics\Events;

use App\Models\Business;

class WhatsAppClicked
{
    public function __construct(
        public Business $business,
    ) {}
}
