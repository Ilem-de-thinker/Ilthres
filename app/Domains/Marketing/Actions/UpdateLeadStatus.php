<?php

namespace App\Domains\Marketing\Actions;

use App\Domains\Marketing\Events\LeadStatusChanged;
use App\Models\LeadSubmission;

class UpdateLeadStatus
{
    public function __invoke(LeadSubmission $submission, string $status): LeadSubmission
    {
        $submission->update(['status' => $status]);

        event(new LeadStatusChanged($submission));

        return $submission;
    }
}
