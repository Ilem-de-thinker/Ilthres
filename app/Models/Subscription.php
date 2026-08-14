<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Represents a subscription record linking a business to a plan.
 *
 * @property int $id
 * @property int $business_id
 * @property int $plan_id
 * @property string $status trial | active | expired | cancelled | past_due
 * @property Carbon|null $trial_ends_at
 * @property Carbon $starts_at
 * @property Carbon|null $ends_at
 * @property Carbon|null $cancelled_at
 * @property string|null $gateway Payment gateway used (paystack, flutterwave, etc.)
 * @property string|null $gateway_reference Gateway subscription reference
 * @property array|null $gateway_data Raw gateway response data
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Subscription extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'business_id', 'plan_id', 'price_paid', 'scheduled_plan_id', 'status',
        'trial_ends_at', 'starts_at', 'ends_at', 'cancelled_at',
        'gateway', 'gateway_reference', 'gateway_data',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'trial_ends_at' => 'datetime',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'cancelled_at' => 'datetime',
            'gateway_data' => 'array',
        ];
    }

    /**
     * The business this subscription belongs to.
     */
    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    /**
     * The plan this subscription is tied to.
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * The plan scheduled to take effect when the current billing period ends (for downgrades).
     */
    public function scheduledPlan(): BelongsTo
    {
        return $this->belongsTo(Plan::class, 'scheduled_plan_id');
    }

    /**
     * Determine if the subscription is currently active or in trial.
     */
    public function isActive(): bool
    {
        return in_array($this->status, ['trial', 'active']);
    }

    /**
     * Check if a plan change is scheduled for the end of the billing period.
     */
    public function hasScheduledChange(): bool
    {
        return $this->scheduled_plan_id !== null && $this->scheduled_plan_id !== $this->plan_id;
    }
}
