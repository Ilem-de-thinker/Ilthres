<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LeadForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id', 'name', 'slug', 'description', 'template', 'is_active', 'submission_count',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'submission_count' => 'integer',
        ];
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function fields(): HasMany
    {
        return $this->hasMany(LeadFormField::class)->orderBy('sort_order');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(LeadSubmission::class);
    }
}
