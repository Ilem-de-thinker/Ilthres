<?php

namespace App\Domains\Marketing\Actions;

use App\Models\Discount;

class DisableDiscount
{
    public function __invoke(Discount $discount): void
    {
        $discount->update(['is_active' => false]);
    }
}
