<?php

namespace App\Domains\Marketing\Actions;

use App\Models\Discount;

class UpdateDiscount
{
    public function __invoke(Discount $discount, array $data): Discount
    {
        $discount->update($data);

        return $discount;
    }
}
