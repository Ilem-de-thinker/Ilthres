<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BusinessLocation extends Model
{
    protected $fillable = [
        'business_id',
        'country',
        'state',
        'city',
        'address',
        'postal_code',
        'latitude',
        'longitude',
        'phone',
        'email',
        'opening_hours',
        'is_primary',
        'is_headquarters',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
            'opening_hours' => 'array',
            'is_primary' => 'boolean',
            'is_headquarters' => 'boolean',
        ];
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }
}
