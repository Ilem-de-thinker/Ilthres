<?php

namespace App\Domains\Marketing\Actions;

use App\Domains\Marketing\Events\PointsEarned;
use App\Models\LoyaltyPointsTransaction;
use App\Models\LoyaltyProgram;

class EarnPoints
{
    public function __invoke(LoyaltyProgram $program, string $reason, int $points, ?array $customer = null): LoyaltyPointsTransaction
    {
        $transaction = LoyaltyPointsTransaction::create([
            'business_id' => $program->business_id,
            'customer_id' => $customer['id'] ?? null,
            'customer_identifier' => $customer['identifier'] ?? null,
            'action' => 'earned',
            'reason' => $reason,
            'points' => $points,
        ]);

        event(new PointsEarned($transaction));

        return $transaction;
    }
}
