<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnalyticsEvent extends Model
{
    use HasFactory;

    const TYPE_PAGE_VIEW = 'page_view';

    const TYPE_PRODUCT_VIEW = 'product_view';

    const TYPE_SERVICE_VIEW = 'service_view';

    const TYPE_WHATSAPP_CLICK = 'whatsapp_click';

    const TYPE_PHONE_CLICK = 'phone_click';

    public $timestamps = false;

    protected $fillable = [
        'business_id', 'user_id', 'type', 'source',
        'url', 'ip', 'user_agent', 'metadata', 'created_at',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'created_at' => 'datetime',
        ];
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }
}
