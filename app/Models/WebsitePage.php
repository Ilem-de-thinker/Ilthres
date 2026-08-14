<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Represents a page within a business website.
 *
 * @property int $id
 * @property int $website_id
 * @property string $title
 * @property string $slug
 * @property int $sort_order
 * @property bool $is_active
 * @property array|null $seo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class WebsitePage extends Model
{
    use HasFactory;

    protected $fillable = [
        'website_id', 'title', 'slug', 'sort_order', 'is_active', 'seo',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'seo' => 'array',
        ];
    }

    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class);
    }

    public function sections(): HasMany
    {
        return $this->hasMany(WebsiteSection::class, 'page_id');
    }
}
