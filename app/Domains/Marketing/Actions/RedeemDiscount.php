<?php

namespace App\Domains\Marketing\Actions;

use App\Models\Discount;
use App\Models\DiscountRedemption;
use App\Domains\Marketing\Events\DiscountRedeemed;

class RedeemDiscount
{
    public function __invoke(Discount $discount, float $discountAmount, ?string $customerIdentifier = null): DiscountRedemption
    {
        $redemption = DiscountRedemption::create([
            'discount_id' => $discount->id,
            'business_id' => $discount->business_id,
            'discount_amount' => $discountAmount,
            'customer_identifier' => $customerIdentifier,
        ]);

        $discount->increment('used_count');

        event(new DiscountRedeemed($discount, $redemption));

        return $redemption;
    }
}
