<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoyaltyPointsTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id', 'customer_id', 'customer_identifier', 'action',
        'reason', 'points', 'reference_type', 'reference_id',
    ];

    protected function casts(): array
    {
        return [
            'points' => 'integer',
        ];
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
}
