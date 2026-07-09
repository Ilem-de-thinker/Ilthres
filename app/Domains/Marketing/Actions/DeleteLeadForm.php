<?php

namespace App\Domains\Marketing\Actions;

use App\Models\LeadForm;

class DeleteLeadForm
{
    public function __invoke(LeadForm $form): void
    {
        $form->delete();
    }
}
