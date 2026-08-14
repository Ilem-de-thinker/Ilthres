<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessVerification extends Model
{
    const STATUS_UNVERIFIED = 'unverified';

    const STATUS_PENDING = 'pending';

    const STATUS_UNDER_REVIEW = 'under_review';

    const STATUS_VERIFIED = 'verified';

    const STATUS_REJECTED = 'rejected';

    const DOCUMENT_TYPES = [
        'government_id' => 'Government-issued ID',
        'business_registration' => 'Business Registration',
        'utility_bill' => 'Utility Bill',
        'tax_id' => 'Tax ID',
    ];

    protected $fillable = [
        'business_id',
        'status',
        'documents',
        'admin_notes',
        'reviewed_by',
        'reviewed_at',
        'submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'documents' => 'array',
            'reviewed_at' => 'datetime',
            'submitted_at' => 'datetime',
        ];
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function isUnverified(): bool
    {
        return $this->status === 'unverified';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isUnderReview(): bool
    {
        return $this->status === 'under_review';
    }

    public function isVerified(): bool
    {
        return $this->status === 'verified';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
}
