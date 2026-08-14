<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * Represents a visual theme that can be applied to a business profile.
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property string|null $thumbnail
 * @property string|null $business_type null = global, product_store, service_business
 * @property array $config Theme configuration (colors, typography, radius, etc.)
 * @property bool $is_premium
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Theme extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'slug', 'description', 'thumbnail',
        'config', 'business_type', 'is_premium', 'is_active', 'sort_order',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'config' => 'array',
            'is_premium' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Businesses using this theme.
     */
    public function businesses(): BelongsToMany
    {
        return $this->belongsToMany(Business::class)
            ->using(BusinessTheme::class)
            ->withPivot(['customization', 'is_active'])
            ->withTimestamps();
    }

    /**
     * Scope: themes applicable to a given business type.
     *
     * Returns themes where business_type is null (global) or matches the given type.
     */
    public function scopeForBusinessType($query, ?string $type)
    {
        return $query->where(function ($q) use ($type) {
            $q->whereNull('business_type')
                ->orWhere('business_type', $type);
        });
    }
}
