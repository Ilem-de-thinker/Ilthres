<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LinkClick extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'marketing_link_id', 'ip', 'user_agent', 'referer', 'created_at',
    ];

    public function marketingLink(): BelongsTo
    {
        return $this->belongsTo(MarketingLink::class);
    }
}
