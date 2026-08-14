<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_availability_id',
        'date',
        'start_time',
        'end_time',
        'current_bookings',
        'max_bookings',
        'is_available',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'start_time' => 'datetime:H:i',
            'end_time' => 'datetime:H:i',
            'is_available' => 'boolean',
        ];
    }

    public function availability(): BelongsTo
    {
        return $this->belongsTo(ServiceAvailability::class, 'service_availability_id');
    }

    public function isFullyBooked(): bool
    {
        return $this->current_bookings >= $this->max_bookings;
    }

    public function getRemainingSlotsAttribute(): int
    {
        return max(0, $this->max_bookings - $this->current_bookings);
    }

    public function book(): bool
    {
        if ($this->isFullyBooked()) {
            return false;
        }

        $this->increment('current_bookings');

        if ($this->isFullyBooked()) {
            $this->update(['is_available' => false]);
        }

        return true;
    }

    public function cancel(): bool
    {
        if ($this->current_bookings > 0) {
            $this->decrement('current_bookings');
            $this->update(['is_available' => true]);

            return true;
        }

        return false;
    }
}
