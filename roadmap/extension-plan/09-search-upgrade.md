# 09 — Search Upgrade (Meilisearch)

> Search businesses, not websites. The website is only the presentation layer.

## What Already Exists

- `ExploreSearch` Livewire component with basic DB search
- Business has `is_discoverable` flag
- Products/Services have `is_public` flag
- Categories exist with hierarchy
- Business has location fields (city, state, country, lat/lng)

## What This Phase Adds

### 1. Meilisearch Integration

Replace database search with Meilisearch for:
- Full-text search across business name, description, products, services
- Faceted filtering (category, location, rating, verified)
- Geo search ("near me" with radius)
- Typo tolerance and instant results

### 2. Installation

```bash
composer require laravel/scout
composer require meilisearch/meilisearch-php
```

Configure `config/scout.php`:
```php
'driver' => env('SCOUT_DRIVER', 'meilisearch'),
'meilisearch' => [
    'host' => env('MEILISEARCH_HOST', 'http://localhost:7700'),
    'key' => env('MEILISEARCH_KEY'),
],
```

### 3. Business Searchable Array

```php
// In Business model
use Laravel\Scout\Searchable;

public function toSearchableArray(): array
{
    $location = $this->primaryLocation;

    return [
        'id' => $this->id,
        'name' => $this->name,
        'slug' => $this->slug,
        'description' => $this->description,
        'category' => $this->category?->name,
        'tags' => $this->tags ?? [],
        'services' => $this->services->pluck('name')->toArray(),
        'products' => $this->products->pluck('name')->toArray(),
        'city' => $location?->city,
        'state' => $location?->state,
        'country' => $location?->country,
        'location' => [
            'lat' => (float) ($location?->latitude ?? $this->latitude),
            'lng' => (float) ($location?->longitude ?? $this->longitude),
        ],
        'rating' => (float) $this->averageRating(),
        'review_count' => $this->reviewCount(),
        'trust_score' => (float) $this->trustScore(),
        'is_verified' => $this->is_verified,
        'is_discoverable' => $this->is_discoverable,
        'created_at' => $this->created_at->timestamp,
    ];
}
```

### 4. Sync Observers

```php
// app/Observers/BusinessObserver.php
class BusinessObserver
{
    public function updated(Business $business): void
    {
        if ($business->isDirty(['name', 'description', 'is_discoverable'])) {
            $business->searchable();
        }
    }

    public function deleted(Business $business): void
    {
        $business->unsearchable();
    }
}
```

Register in `AppServiceProvider`:
```php
Model::observe(BusinessObserver::class);
```

Also observe Product and Service to re-index parent business.

### 5. Search Features

- **Faceted search:** category, location, rating, verified, open_now
- **Geo search:** `lat` + `lng` + `radius` filter
- **Sort:** relevance, distance, rating, newest, popular
- **Quick filters:** Verified, Premium, Nearby, Open Now, Trending
- **Typo tolerance:** handles misspellings

### 6. Upgrade ExploreSearch Livewire

Replace DB queries with Meilisearch:
```php
$results = Business::search($query)
    ->when($category, fn ($q) => $q->where('category', $category))
    ->when($verified, fn ($q) => $q->where('is_verified', true))
    ->when($lat && $lng, fn ($q) => $q->nearby($lat, $lng, $radius))
    ->paginate(20);
```

### 7. Queue Job

```php
// app/Jobs/SyncBusinessToSearchIndex.php
// For bulk reindexing: php artisan scout:reindex Business
```

---

## Checklist

- [ ] Install Laravel Scout + Meilisearch driver
- [ ] Configure `config/scout.php`
- [ ] Add `Searchable` trait to Business model
- [ ] Implement `toSearchableArray()` on Business
- [ ] Create BusinessObserver (sync on update/delete)
- [ ] Create ProductObserver, ServiceObserver (re-index parent business)
- [ ] Job: SyncBusinessToSearchIndex
- [ ] Upgrade ExploreSearch Livewire to use Meilisearch
- [ ] Add faceted filters to explore view (category, verified, rating)
- [ ] Add geo search ("near me")
- [ ] Add sort options (relevance, distance, rating, newest)
- [ ] Seed Meilisearch index: `php artisan scout:reindex Business`
