<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * Represents a media file (image, document, etc.) uploaded by a business.
 *
 * @property int $id
 * @property int $business_id
 * @property string $filename
 * @property string $original_name
 * @property string $mime_type
 * @property int $size Size in bytes
 * @property string $disk Storage disk (local, s3, etc.)
 * @property string $path
 * @property array|null $variants Generated image variants (thumbnails, etc.)
 * @property string|null $collection Logical grouping (logo, gallery, etc.)
 * @property array|null $metadata
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
class Media extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'business_id', 'filename', 'original_name', 'mime_type',
        'size', 'disk', 'path', 'variants', 'collection', 'metadata',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'variants' => 'array',
            'metadata' => 'array',
        ];
    }

    /**
     * The business that owns this media file.
     */
    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }
}
