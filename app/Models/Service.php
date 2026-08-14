<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Laravel\Scout\Searchable;

/**
 * Represents a service offered by a service-business.
 *
 * @property int $id
 * @property int $business_id
 * @property int|null $category_id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property float $price
 * @property string $currency
 * @property int|null $duration_minutes
 * @property array|null $images
 * @property bool $is_active
 * @property int $sort_order
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
class Service extends Model
{
    use HasFactory, Searchable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'business_id', 'category_id', 'name', 'slug',
        'description', 'price', 'currency', 'duration_minutes',
        'images', 'is_active', 'is_public', 'sort_order',
        'tags', 'last_indexed_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'images' => 'array',
            'is_active' => 'boolean',
            'is_public' => 'boolean',
            'tags' => 'array',
            'last_indexed_at' => 'datetime',
        ];
    }

    /**
     * The business that owns this service.
     */
    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    /**
     * The category this service belongs to.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Active promotions that include this service.
     */
    public function promotions(): MorphToMany
    {
        return $this->morphToMany(Promotion::class, 'promotionable');
    }

    /**
     * Promo banners that feature this service.
     */
    public function banners(): MorphToMany
    {
        return $this->morphToMany(PromoBanner::class, 'bannerable');
    }

    /**
     * Get the best active promotion for this service.
     */
    public function getBestPromotionAttribute(): ?Promotion
    {
        if (! $this->relationLoaded('promotions')) {
            return null;
        }

        return $this->promotions
            ->filter(fn ($p) => $p->isRunning())
            ->sortByDesc(fn ($p) => [$p->priority, $p->discount_value ?? 0])
            ->first();
    }

    /**
     * Check if this service has availability configured for bookings.
     */
    public function getHasAvailabilityAttribute(): bool
    {
        return ServiceAvailability::where('service_id', $this->id)
            ->where('is_active', true)
            ->exists();
    }

    /**
     * Whether this service requires payment at booking time.
     */
    public function getRequiresPaymentAttribute(): bool
    {
        return $this->business && $this->business->order_mode === 'checkout' && $this->price > 0;
    }

    /**
     * The availability records for this service.
     */
    public function availability(): HasMany
    {
        return $this->hasMany(ServiceAvailability::class);
    }

    /**
     * The bookings for this service.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get the indexable data array for the model.
     */
    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'business_id' => $this->business_id,
            'name' => $this->name,
            'description' => $this->description ?? '',
            'price' => $this->price,
            'currency' => $this->currency,
            'duration_minutes' => $this->duration_minutes,
            'category_id' => $this->category_id,
            'is_active' => $this->is_active,
            'is_public' => $this->is_public,
            'tags' => $this->tags ?? [],
        ];
    }

    /**
     * Get the index name for the model.
     */
    public function searchableAs(): string
    {
        return 'services';
    }
}
