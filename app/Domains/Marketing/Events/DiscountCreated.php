<?php

namespace App\Domains\Marketing\Events;

use App\Models\Discount;

class DiscountCreated
{
    public function __construct(
        public Discount $discount,
    ) {}
}
