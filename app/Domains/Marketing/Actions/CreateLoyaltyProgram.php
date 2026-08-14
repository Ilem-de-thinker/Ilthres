<?php

namespace App\Domains\Marketing\Actions;

use App\Domains\Marketing\Events\LoyaltyProgramCreated;
use App\Models\LoyaltyProgram;

class CreateLoyaltyProgram
{
    public function __invoke(array $data): LoyaltyProgram
    {
        $program = LoyaltyProgram::create($data);

        event(new LoyaltyProgramCreated($program));

        return $program;
    }
}
