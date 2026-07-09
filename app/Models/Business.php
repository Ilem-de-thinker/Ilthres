<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 */
class Business extends Model
{
    use HasFactory, SoftDeletes;

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
        'user_id', 'category_id', 'name', 'slug', 'type',
        'description', 'about', 'email', 'phone', 'whatsapp',
        'address', 'city', 'state', 'country', 'latitude', 'longitude',
        'logo', 'cover', 'is_verified', 'is_active', 'is_discoverable',
        'social_links', 'seo',
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
     * The themes available to this business via the pivot table.
     */
    public function themes(): BelongsToMany
    {
        return $this->belongsToMany(Theme::class)
            ->withPivot(['customization', 'is_active'])
            ->withTimestamps();
    }

    /**
     * The latest active subscription for this business.
     */
    public function subscription()
    {
        return $this->hasOne(Subscription::class)->latest();
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
}
