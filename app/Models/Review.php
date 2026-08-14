<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'business_id',
        'order_id',
        'booking_id',
        'user_id',
        'reviewer_name',
        'rating',
        'comment',
        'seller_response',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function canBeEditedBy(User $user): bool
    {
        if ($this->user_id !== $user->id) {
            return false;
        }

        return $this->created_at->diffInHours(now()) < 24;
    }

    public function edit(array $data): bool
    {
        if (! isset($this->user_id)) {
            return false;
        }

        return $this->update($data);
    }

    public function respond(string $response): bool
    {
        return $this->update(['seller_response' => $response]);
    }
}
