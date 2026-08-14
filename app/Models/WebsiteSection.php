<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Represents a content section within a website page.
 *
 * @property int $id
 * @property int $page_id
 * @property string $type Section type identifier (e.g., "hero", "products", "text")
 * @property string $title
 * @property int $sort_order
 * @property bool $is_active
 * @property array|null $data Section-specific content data
 * @property array|null $settings Section display settings
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class WebsiteSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_id', 'type', 'title', 'sort_order', 'is_active', 'data', 'settings',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'data' => 'array',
            'settings' => 'array',
        ];
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(WebsitePage::class, 'page_id');
    }
}
