<?php

namespace App\Domains\Marketing\Events;

use App\Models\ReferralReward;

class ReferrerRewarded
{
    public function __construct(
        public ReferralReward $reward,
    ) {}
}
