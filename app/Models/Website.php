<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Represents a website owned by a business.
 *
 * Each business can have one website with a unique subdomain and optionally
 * a custom domain. The website is the entry point for domain resolution —
 * the ResolveBusinessDomain middleware uses it to identify which business
 * a request belongs to.
 *
 * @property int $id
 * @property int $business_id
 * @property int|null $theme_id
 * @property string $subdomain
 * @property string|null $custom_domain
 * @property bool $is_active
 * @property bool $ssl_enabled
 * @property array|null $dns_records
 * @property array|null $seo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Website extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'business_id', 'theme_id', 'subdomain', 'custom_domain',
        'is_active', 'ssl_enabled', 'dns_records', 'seo',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'ssl_enabled' => 'boolean',
            'dns_records' => 'array',
            'seo' => 'array',
        ];
    }

    /**
     * The business that owns this website.
     */
    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    /**
     * The theme applied to this website.
     */
    public function theme(): BelongsTo
    {
        return $this->belongsTo(Theme::class);
    }

    /**
     * The pages belonging to this website.
     */
    public function pages(): HasMany
    {
        return $this->hasMany(WebsitePage::class);
    }

    /**
     * Check if the given hostname matches this website.
     */
    public function matchesHost(string $host): bool
    {
        $baseDomain = config('app.base_domain');

        if ($this->subdomain.'.'.$baseDomain === $host) {
            return true;
        }

        if ($this->custom_domain && $this->custom_domain === $host) {
            return true;
        }

        return false;
    }

    /**
     * Get the full URL for this website.
     */
    public function getFullUrlAttribute(): string
    {
        $baseDomain = config('app.base_domain');
        $scheme = $this->ssl_enabled ? 'https' : 'http';

        return "{$scheme}://{$this->subdomain}.{$baseDomain}";
    }
}
