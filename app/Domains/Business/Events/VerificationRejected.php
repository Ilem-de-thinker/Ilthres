<?php

namespace App\Domains\Business\Events;

use App\Models\BusinessVerification;

class VerificationRejected
{
    public function __construct(
        public BusinessVerification $verification,
    ) {}
}
