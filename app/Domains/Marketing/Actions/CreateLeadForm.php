<?php

namespace App\Domains\Marketing\Actions;

use App\Domains\Marketing\Events\LeadFormCreated;
use App\Models\LeadForm;

class CreateLeadForm
{
    public function __invoke(array $data): LeadForm
    {
        $form = LeadForm::create($data);

        event(new LeadFormCreated($form));

        return $form;
    }
}
