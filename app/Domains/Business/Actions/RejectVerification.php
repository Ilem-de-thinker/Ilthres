<?php

namespace App\Domains\Business\Actions;

use App\Domains\Business\Events\VerificationRejected;
use App\Models\BusinessVerification;
use App\Models\User;

class RejectVerification
{
    public function __invoke(BusinessVerification $verification, User $admin, string $reason): BusinessVerification
    {
        $verification->update([
            'status' => 'rejected',
            'admin_notes' => $reason,
            'reviewed_by' => $admin->id,
            'reviewed_at' => now(),
        ]);

        $verification->business->update(['is_verified' => false]);

        event(new VerificationRejected($verification));

        return $verification;
    }
}
