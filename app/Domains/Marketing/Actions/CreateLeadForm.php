<?php

namespace App\Domains\Marketing\Actions;

use App\Models\LeadForm;
use App\Domains\Marketing\Events\LeadFormCreated;

class CreateLeadForm
{
    public function __invoke(array $data): LeadForm
    {
        $form = LeadForm::create($data);

        event(new LeadFormCreated($form));

        return $form;
    }
}
