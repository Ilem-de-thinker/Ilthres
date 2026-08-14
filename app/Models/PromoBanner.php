<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class PromoBanner extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id', 'message', 'image', 'position', 'style', 'sort_order',
        'cta_text', 'cta_url', 'starts_at', 'ends_at', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'starts_at' => 'date',
            'ends_at' => 'date',
            'sort_order' => 'integer',
        ];
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function products(): MorphToMany
    {
        return $this->morphToMany(Product::class, 'bannerable', 'bannerables', 'promo_banner_id', 'bannerable_id');
    }

    public function services(): MorphToMany
    {
        return $this->morphToMany(Service::class, 'bannerable', 'bannerables', 'promo_banner_id', 'bannerable_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            });
    }

    public function scopeHero($query)
    {
        return $query->where('position', 'hero');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }
}
