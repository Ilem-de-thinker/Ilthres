<?php

namespace App\Domains\Marketing\Actions;

use App\Models\LoyaltyProgram;
use App\Domains\Marketing\Events\LoyaltyProgramCreated;

class CreateLoyaltyProgram
{
    public function __invoke(array $data): LoyaltyProgram
    {
        $program = LoyaltyProgram::create($data);

        event(new LoyaltyProgramCreated($program));

        return $program;
    }
}
