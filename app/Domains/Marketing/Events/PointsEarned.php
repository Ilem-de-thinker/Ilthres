<?php

namespace App\Domains\Marketing\Events;

use App\Models\LoyaltyPointsTransaction;

class PointsEarned
{
    public function __construct(
        public LoyaltyPointsTransaction $transaction,
    ) {}
}
