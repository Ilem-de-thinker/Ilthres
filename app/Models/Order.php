<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'business_id', 'order_number', 'type', 'status',
        'buyer_name', 'buyer_email', 'buyer_phone', 'buyer_message',
        'subtotal', 'total', 'currency',
        'gateway', 'gateway_reference', 'gateway_data',
        'admin_notes', 'confirmed_at', 'completed_at', 'cancelled_at',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'total' => 'decimal:2',
            'gateway_data' => 'array',
            'confirmed_at' => 'datetime',
            'completed_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public static function generateOrderNumber(): string
    {
        $prefix = 'IL';
        $date = now()->format('ymd');
        $sequence = static::whereDate('created_at', today())->count() + 1;

        return $prefix.$date.'-'.str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
}
