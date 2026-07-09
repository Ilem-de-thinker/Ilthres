<?php

namespace App\Domains\Marketing\Actions;

use App\Models\LeadForm;

class UpdateLeadForm
{
    public function __invoke(LeadForm $form, array $data): LeadForm
    {
        $form->update($data);

        return $form;
    }
}
