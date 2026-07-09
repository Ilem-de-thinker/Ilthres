<?php

namespace App\Domains\Marketing\Events;

use App\Models\LeadSubmission;

class LeadStatusChanged
{
    public function __construct(
        public LeadSubmission $submission,
    ) {}
}
