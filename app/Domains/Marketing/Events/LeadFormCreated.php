<?php

namespace App\Domains\Marketing\Events;

use App\Models\LeadForm;

class LeadFormCreated
{
    public function __construct(
        public LeadForm $form,
    ) {}
}
