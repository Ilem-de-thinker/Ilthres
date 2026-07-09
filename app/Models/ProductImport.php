<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImport extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id', 'filename', 'total_rows', 'processed_rows',
        'skipped_rows', 'error_count', 'status', 'errors',
    ];

    protected function casts(): array
    {
        return [
            'total_rows' => 'integer',
            'processed_rows' => 'integer',
            'skipped_rows' => 'integer',
            'error_count' => 'integer',
            'errors' => 'array',
        ];
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }
}
