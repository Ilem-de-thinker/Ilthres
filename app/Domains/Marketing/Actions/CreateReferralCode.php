<?php

namespace App\Domains\Marketing\Actions;

use App\Models\Referral;
use App\Domains\Marketing\Events\ReferralCreated;

class CreateReferralCode
{
    public function __invoke(int $businessId, int $referrerId): Referral
    {
        $code = strtoupper(substr(md5(uniqid()), 0, 8));

        $referral = Referral::create([
            'business_id' => $businessId,
            'referrer_id' => $referrerId,
            'code' => $code,
        ]);

        event(new ReferralCreated($referral));

        return $referral;
    }
}
