<?php

namespace App\Domains\Marketing\Actions;

use App\Models\LeadSubmission;
use App\Domains\Marketing\Events\LeadStatusChanged;

class UpdateLeadStatus
{
    public function __invoke(LeadSubmission $submission, string $status): LeadSubmission
    {
        $submission->update(['status' => $status]);

        event(new LeadStatusChanged($submission));

        return $submission;
    }
}
