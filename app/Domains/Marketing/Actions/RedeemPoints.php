<?php

namespace App\Domains\Marketing\Actions;

use App\Domains\Marketing\Events\PointsRedeemed;
use App\Models\LoyaltyPointsTransaction;
use App\Models\LoyaltyProgram;

class RedeemPoints
{
    public function __invoke(LoyaltyProgram $program, int $points, ?array $customer = null): LoyaltyPointsTransaction
    {
        $transaction = LoyaltyPointsTransaction::create([
            'business_id' => $program->business_id,
            'customer_id' => $customer['id'] ?? null,
            'customer_identifier' => $customer['identifier'] ?? null,
            'action' => 'redeemed',
            'reason' => 'reward',
            'points' => $points,
        ]);

        event(new PointsRedeemed($transaction));

        return $transaction;
    }
}
