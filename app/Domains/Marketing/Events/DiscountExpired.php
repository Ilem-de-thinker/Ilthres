<?php

namespace App\Domains\Marketing\Events;

use App\Models\Discount;

class DiscountExpired
{
    public function __construct(
        public Discount $discount,
    ) {}
}
