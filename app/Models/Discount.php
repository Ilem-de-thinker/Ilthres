<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id', 'code', 'type', 'value', 'min_order',
        'max_uses', 'used_count', 'expires_at', 'scope', 'scope_ids', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'min_order' => 'decimal:2',
            'value' => 'decimal:2',
            'max_uses' => 'integer',
            'used_count' => 'integer',
            'scope_ids' => 'array',
            'is_active' => 'boolean',
            'expires_at' => 'date',
        ];
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function redemptions(): HasMany
    {
        return $this->hasMany(DiscountRedemption::class);
    }

    public function isValid(): bool
    {
        if (! $this->is_active) {
            return false;
        }
        if ($this->max_uses && $this->used_count >= $this->max_uses) {
            return false;
        }
        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        return true;
    }
}
