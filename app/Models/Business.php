<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Laravel\Scout\Searchable;

/**
 * Represents a business profile on the Ilthres Market platform.
 *
 * A business can be either a **product store** (`product_store`) selling physical/digital
 * goods, or a **service business** (`service_business`) offering services. The type is
 * set at creation and is mutually exclusive.
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $category_id
 * @property string $name
 * @property string $slug
 * @property string $type Either "product_store" or "service_business"
 * @property string|null $description
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $whatsapp
 * @property string|null $address
 * @property string|null $city
 * @property string|null $state
 * @property string|null $country
 * @property string|null $logo
 * @property string|null $cover
 * @property bool $is_verified
 * @property bool $is_active
 * @property array|null $social_links
 * @property array|null $seo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
class Business extends Model
{
    use HasFactory, Searchable, SoftDeletes;

    /** Business sells physical or digital products. */
    const TYPE_PRODUCT_STORE = 'product_store';

    /** Business offers services (e.g., salon, plumbing, consulting). */
    const TYPE_SERVICE_BUSINESS = 'service_business';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id', 'category_id', 'name', 'slug', 'type', 'order_mode',
        'bank_name', 'account_number', 'account_name',
        'description', 'about', 'email', 'phone', 'whatsapp',
        'address', 'city', 'state', 'country', 'latitude', 'longitude',
        'logo', 'cover', 'website_url', 'is_verified', 'is_active', 'is_discoverable',
        'social_links', 'seo', 'tags', 'search_boost', 'last_indexed_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_verified' => 'boolean',
            'is_active' => 'boolean',
            'is_discoverable' => 'boolean',
            'social_links' => 'array',
            'seo' => 'array',
            'tags' => 'array',
            'last_indexed_at' => 'datetime',
        ];
    }

    /**
     * The user who owns this business.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The category this business belongs to.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * The products this business offers (product_store only).
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * The services this business offers (service_business only).
     */
    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    /**
     * The media files owned by this business.
     */
    public function media(): HasMany
    {
        return $this->hasMany(Media::class);
    }

    /**
     * The website owned by this business.
     */
    public function website(): HasOne
    {
        return $this->hasOne(Website::class);
    }

    /**
     * The themes available to this business via the pivot table.
     */
    public function themes(): BelongsToMany
    {
        return $this->belongsToMany(Theme::class)
            ->using(BusinessTheme::class)
            ->withPivot(['customization', 'is_active'])
            ->withTimestamps();
    }

    /**
     * The reviews for this business.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class)->where('is_active', true);
    }

    /**
     * The bookings for this business.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * The locations (branches) for this business.
     */
    public function locations(): HasMany
    {
        return $this->hasMany(BusinessLocation::class);
    }

    /**
     * The primary location for this business.
     */
    public function primaryLocation(): HasOne
    {
        return $this->hasOne(BusinessLocation::class)->where('is_primary', true);
    }

    /**
     * The latest active subscription for this business.
     */
    public function subscription()
    {
        return $this->hasOne(Subscription::class)->latest();
    }

    /**
     * The latest KYC verification attempt for this business.
     */
    public function verification(): HasOne
    {
        return $this->hasOne(BusinessVerification::class)->latest();
    }

    /**
     * Determine if this business is a product store.
     */
    public function isProductStore(): bool
    {
        return $this->type === self::TYPE_PRODUCT_STORE;
    }

    /**
     * Determine if this business is a service business.
     */
    public function isServiceBusiness(): bool
    {
        return $this->type === self::TYPE_SERVICE_BUSINESS;
    }

    /**
     * Get the currency code used by this business's products/services.
     */
    public function getCurrencyCodeAttribute(): string
    {
        $productCurrency = $this->products()->whereNotNull('currency')->value('currency');
        if ($productCurrency) {
            return $productCurrency;
        }

        $serviceCurrency = $this->services()->whereNotNull('currency')->value('currency');
        if ($serviceCurrency) {
            return $serviceCurrency;
        }

        return 'NGN';
    }

    /**
     * Get the rating distribution for this business.
     */
    public function getRatingDistributionAttribute(): array
    {
        return $this->reviews()
            ->selectRaw('rating, count(*) as count')
            ->groupBy('rating')
            ->pluck('count', 'rating')
            ->toArray();
    }

    /**
     * Update the rating cache for this business.
     */
    public function updateRatingCache(): void
    {
        $this->update([
            'average_rating' => $this->reviews()->avg('rating'),
            'review_count' => $this->reviews()->count(),
        ]);
    }

    /**
     * Get the indexable data array for the model.
     */
    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description ?? '',
            'about' => $this->about ?? '',
            'type' => $this->type,
            'city' => $this->city ?? '',
            'state' => $this->state ?? '',
            'country' => $this->country ?? '',
            'category_id' => $this->category_id,
            'is_verified' => $this->is_verified,
            'is_active' => $this->is_active,
            'is_discoverable' => $this->is_discoverable,
            'average_rating' => $this->average_rating ?? 0,
            'review_count' => $this->review_count ?? 0,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'tags' => $this->tags ?? [],
            'search_boost' => $this->search_boost ?? 0,
        ];
    }

    /**
     * Get the index name for the model.
     */
    public function searchableAs(): string
    {
        return 'businesses';
    }
}
