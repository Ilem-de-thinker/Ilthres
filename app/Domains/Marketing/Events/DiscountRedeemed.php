<?php

namespace App\Domains\Marketing\Events;

use App\Models\Discount;
use App\Models\DiscountRedemption;

class DiscountRedeemed
{
    public function __construct(
        public Discount $discount,
        public DiscountRedemption $redemption,
    ) {}
}
