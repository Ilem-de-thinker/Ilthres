<?php

namespace App\Domains\Business\Events;

use App\Models\BusinessVerification;

class VerificationSubmitted
{
    public function __construct(
        public BusinessVerification $verification,
    ) {}
}
