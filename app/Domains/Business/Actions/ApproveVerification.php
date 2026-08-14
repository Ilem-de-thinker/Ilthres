<?php

namespace App\Domains\Business\Actions;

use App\Domains\Business\Events\VerificationApproved;
use App\Models\BusinessVerification;
use App\Models\User;

class ApproveVerification
{
    public function __invoke(BusinessVerification $verification, User $admin, ?string $notes = null): BusinessVerification
    {
        $verification->update([
            'status' => 'verified',
            'admin_notes' => $notes,
            'reviewed_by' => $admin->id,
            'reviewed_at' => now(),
        ]);

        $verification->business->update(['is_verified' => true]);

        event(new VerificationApproved($verification));

        return $verification;
    }
}
