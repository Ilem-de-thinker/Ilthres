<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Laravel\Scout\Searchable;

/**
 * Represents a product sold by a product-store business.
 *
 * @property int $id
 * @property int $business_id
 * @property int|null $category_id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property float $price
 * @property float|null $compare_price
 * @property string $currency
 * @property array|null $images
 * @property array|null $variants
 * @property bool $is_active
 * @property bool $is_featured
 * @property int $sort_order
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
class Product extends Model
{
    use HasFactory, Searchable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'business_id', 'category_id', 'name', 'slug',
        'description', 'price', 'compare_price', 'currency',
        'images', 'variants', 'is_active', 'is_featured', 'is_public', 'sort_order',
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
            'compare_price' => 'decimal:2',
            'images' => 'array',
            'variants' => 'collection',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'is_public' => 'boolean',
            'tags' => 'array',
            'last_indexed_at' => 'datetime',
        ];
    }

    /**
     * The business that owns this product.
     */
    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    /**
     * The category this product belongs to.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Active promotions that include this product.
     */
    public function promotions(): MorphToMany
    {
        return $this->morphToMany(Promotion::class, 'promotionable');
    }

    /**
     * Promo banners that feature this product.
     */
    public function banners(): MorphToMany
    {
        return $this->morphToMany(PromoBanner::class, 'bannerable');
    }

    /**
     * Get the best active promotion for this product.
     */
    public function scopeWithBestPromotion($query)
    {
        return $query->with(['promotions' => function ($q) {
            $q->active()->orderByDesc('priority')->orderByDesc('discount_value');
        }]);
    }

    /**
     * Get the best active promotion for this product.
     */
    public function getBestPromotionAttribute(): ?Promotion
    {
        if (! $this->relationLoaded('promotions')) {
            return null;
        }

        $now = now();

        return $this->promotions
            ->filter(fn ($p) => $p->isRunning())
            ->sortByDesc(fn ($p) => [$p->priority, $p->discount_value ?? 0])
            ->first();
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
            'compare_price' => $this->compare_price,
            'currency' => $this->currency,
            'category_id' => $this->category_id,
            'is_active' => $this->is_active,
            'is_featured' => $this->is_featured,
            'is_public' => $this->is_public,
            'tags' => $this->tags ?? [],
        ];
    }

    /**
     * Get the index name for the model.
     */
    public function searchableAs(): string
    {
        return 'products';
    }
}
