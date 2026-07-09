<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeadFormField extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'lead_form_id', 'label', 'type', 'required', 'options', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'required' => 'boolean',
            'options' => 'array',
            'sort_order' => 'integer',
        ];
    }

    public function form(): BelongsTo
    {
        return $this->belongsTo(LeadForm::class, 'lead_form_id');
    }
}
