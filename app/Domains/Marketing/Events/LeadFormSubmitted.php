<?php

namespace App\Domains\Marketing\Events;

use App\Models\LeadSubmission;

class LeadFormSubmitted
{
    public function __construct(
        public LeadSubmission $submission,
    ) {}
}
