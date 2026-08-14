<?php

namespace App\Domains\Marketing\Actions;

use App\Domains\Marketing\Events\ReferralConverted;
use App\Models\Referral;

class TrackReferral
{
    public function __invoke(Referral $referral, int $referredUserId): Referral
    {
        $referral->update([
            'status' => 'converted',
            'referred_id' => $referredUserId,
            'converted_at' => now(),
        ]);

        event(new ReferralConverted($referral));

        return $referral;
    }
}
