<?php

namespace App\Domains\Marketing\Events;

use App\Models\LoyaltyProgram;

class LoyaltyProgramCreated
{
    public function __construct(
        public LoyaltyProgram $program,
    ) {}
}
