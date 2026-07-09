<?php

namespace App\Domains\Marketing\Events;

use App\Models\LoyaltyPointsTransaction;

class PointsRedeemed
{
    public function __construct(
        public LoyaltyPointsTransaction $transaction,
    ) {}
}
