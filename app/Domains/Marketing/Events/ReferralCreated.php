<?php

namespace App\Domains\Marketing\Events;

use App\Models\Referral;

class ReferralCreated
{
    public function __construct(
        public Referral $referral,
    ) {}
}
