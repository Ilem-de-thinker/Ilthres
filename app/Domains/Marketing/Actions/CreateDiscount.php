<?php

namespace App\Domains\Marketing\Actions;

use App\Domains\Marketing\Events\DiscountCreated;
use App\Models\Discount;

class CreateDiscount
{
    public function __invoke(array $data): Discount
    {
        $discount = Discount::create($data);

        event(new DiscountCreated($discount));

        return $discount;
    }
}
