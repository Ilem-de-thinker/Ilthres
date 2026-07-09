<?php

namespace App\Domains\Marketing\Events;

use App\Models\Referral;

class ReferralConverted
{
    public function __construct(
        public Referral $referral,
    ) {}
}
