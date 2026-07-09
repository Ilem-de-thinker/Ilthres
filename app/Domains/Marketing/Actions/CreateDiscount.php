<?php

namespace App\Domains\Marketing\Actions;

use App\Models\Discount;
use App\Domains\Marketing\Events\DiscountCreated;

class CreateDiscount
{
    public function __invoke(array $data): Discount
    {
        $discount = Discount::create($data);

        event(new DiscountCreated($discount));

        return $discount;
    }
}
