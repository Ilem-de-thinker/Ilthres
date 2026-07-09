<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeadSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_form_id', 'business_id', 'data', 'status',
        'customer_email', 'customer_phone', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'data' => 'array',
        ];
    }

    public function form(): BelongsTo
    {
        return $this->belongsTo(LeadForm::class, 'lead_form_id');
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }
}
