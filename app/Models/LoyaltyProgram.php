<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LoyaltyProgram extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id', 'name', 'earning_rules', 'rewards', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'earning_rules' => 'array',
            'rewards' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(LoyaltyPointsTransaction::class, 'business_id', 'business_id');
    }
}
