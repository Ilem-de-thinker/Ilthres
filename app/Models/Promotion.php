<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id', 'name', 'slug', 'description',
        'promotion_type', 'scope',
        'discount_type', 'discount_value', 'fixed_price',
        'buy_x', 'buy_y', 'buy_y_discount_type', 'buy_y_discount_value',
        'min_order_amount',
        'max_uses', 'uses_count', 'customer_limit',
        'priority', 'status',
        'banner_image', 'show_countdown', 'show_badge', 'featured',
        'badge_config',
        'starts_at', 'ends_at', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'discount_value' => 'decimal:2',
            'fixed_price' => 'decimal:2',
            'buy_y_discount_value' => 'decimal:2',
            'min_order_amount' => 'decimal:2',
            'uses_count' => 'integer',
            'priority' => 'integer',
            'show_countdown' => 'boolean',
            'show_badge' => 'boolean',
            'featured' => 'boolean',
            'badge_config' => 'array',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function products(): MorphToMany
    {
        return $this->morphedByMany(Product::class, 'promotionable');
    }

    public function services(): MorphToMany
    {
        return $this->morphedByMany(Service::class, 'promotionable');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'promotion_categories');
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['active', 'scheduled'])
            ->where(function ($q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            });
    }

    public function scopeRunning($query)
    {
        return $query->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            });
    }

    public function isRunning(): bool
    {
        if ($this->status === 'archived') {
            return false;
        }

        $now = now();

        if ($this->starts_at && $this->starts_at->gt($now)) {
            return false;
        }

        if ($this->ends_at && $this->ends_at->lt($now)) {
            return false;
        }

        return in_array($this->status, ['active', 'scheduled']);
    }

    public function hasStarted(): bool
    {
        return ! $this->starts_at || $this->starts_at->lte(now());
    }

    public function hasExpired(): bool
    {
        return $this->ends_at && $this->ends_at->lt(now());
    }

    public function daysRemaining(): int
    {
        if (! $this->ends_at) {
            return 0;
        }

        return max(0, now()->diffInDays($this->ends_at, false));
    }

    public function getBadgeTextAttribute(): ?string
    {
        return match ($this->promotion_type) {
            'percentage' => $this->discount_value ? round($this->discount_value).'% OFF' : null,
            'fixed_amount' => $this->discount_value ? '₦'.number_format($this->discount_value).' OFF' : null,
            'fixed_price' => $this->fixed_price ? 'NEW PRICE' : null,
            'free_shipping' => 'FREE SHIPPING',
            'buy_x_get_y' => 'BUY '.($this->buy_x ?? 1).' GET '.($this->buy_y ?? 1),
            'free_item' => 'FREE ITEM',
            default => null,
        };
    }

    public function getBadgeLabelAttribute(): ?string
    {
        return $this->badge_config['custom_text'] ?? $this->name ?? $this->getBadgeTextAttribute();
    }

    public function getBadgeColorAttribute(): string
    {
        return $this->badge_config['color'] ?? 'red';
    }

    public function getBadgePositionAttribute(): string
    {
        return $this->badge_config['position'] ?? 'top-left';
    }

    public function getDiscountedPrice(float $originalPrice): float
    {
        return match ($this->promotion_type) {
            'percentage' => round($originalPrice - ($originalPrice * ($this->discount_value / 100)), 2),
            'fixed_amount' => round(max(0, $originalPrice - $this->discount_value), 2),
            'fixed_price' => round(max(0, $this->fixed_price ?? 0), 2),
            default => $originalPrice,
        };
    }

    public function getDiscountPercent(float $originalPrice): ?float
    {
        return match ($this->promotion_type) {
            'percentage' => round($this->discount_value, 0),
            'fixed_amount' => $originalPrice > 0 ? round(($this->discount_value / $originalPrice) * 100, 0) : null,
            default => null,
        };
    }
}
