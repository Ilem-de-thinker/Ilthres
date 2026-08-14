<?php

namespace App\Domains\Business\Actions;

use App\Domains\Business\Events\VerificationSubmitted;
use App\Models\Business;
use App\Models\BusinessVerification;
use Illuminate\Http\UploadedFile;

class SubmitVerification
{
    const DOCUMENT_TYPES = [
        'government_id',
        'business_registration',
        'utility_bill',
        'tax_id',
    ];

    public function __invoke(Business $business, array $uploadedFiles): BusinessVerification
    {
        $documents = [];

        foreach (self::DOCUMENT_TYPES as $type) {
            if (! isset($uploadedFiles[$type]) || ! $uploadedFiles[$type] instanceof UploadedFile) {
                continue;
            }

            $path = $uploadedFiles[$type]->store("verifications/{$business->id}", 'public');

            $documents[$type] = [
                'path' => $path,
                'original_name' => $uploadedFiles[$type]->getClientOriginalName(),
                'mime_type' => $uploadedFiles[$type]->getMimeType(),
                'size' => $uploadedFiles[$type]->getSize(),
                'accepted' => false,
            ];
        }

        $verification = BusinessVerification::updateOrCreate(
            ['business_id' => $business->id],
            [
                'status' => 'pending',
                'documents' => $documents,
                'submitted_at' => now(),
                'admin_notes' => null,
                'reviewed_by' => null,
                'reviewed_at' => null,
            ],
        );

        event(new VerificationSubmitted($verification));

        return $verification;
    }
}
