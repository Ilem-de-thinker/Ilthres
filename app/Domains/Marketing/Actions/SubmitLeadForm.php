<?php

namespace App\Domains\Marketing\Actions;

use App\Domains\Marketing\Events\LeadFormSubmitted;
use App\Models\LeadForm;
use App\Models\LeadSubmission;

class SubmitLeadForm
{
    public function __invoke(LeadForm $form, array $data): LeadSubmission
    {
        $submission = LeadSubmission::create([
            'lead_form_id' => $form->id,
            'business_id' => $form->business_id,
            'data' => $data['data'] ?? [],
            'customer_email' => $data['customer_email'] ?? null,
            'customer_phone' => $data['customer_phone'] ?? null,
        ]);

        $form->increment('submission_count');

        event(new LeadFormSubmitted($submission));

        return $submission;
    }
}
