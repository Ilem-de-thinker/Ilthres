<?php

namespace App\Domains\Business\Events;

use App\Models\BusinessVerification;

class VerificationApproved
{
    public function __construct(
        public BusinessVerification $verification,
    ) {}
}
