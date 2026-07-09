<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 */
class Service extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'business_id', 'category_id', 'name', 'slug',
        'description', 'price', 'currency', 'duration_minutes',
        'images', 'is_active', 'is_public', 'sort_order',
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
}
