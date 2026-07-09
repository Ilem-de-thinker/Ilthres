<?php

namespace App\Domains\Marketing\Actions;

use App\Models\LoyaltyProgram;

class UpdateLoyaltyProgram
{
    public function __invoke(LoyaltyProgram $program, array $data): LoyaltyProgram
    {
        $program->update($data);

        return $program;
    }
}
