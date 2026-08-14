# 01 — Websites & Domain Resolution

> Extends the existing theme system with domain config, subdomain access, and custom domain support.

## What Already Exists

- `PublicProfile` Livewire renders business at `/{business:slug}`
- `PublicPageController` handles `/portfolio`, `/about`, `/contact` sub-pages
- `BusinessTheme` pivot stores active theme + customization colors
- Theme blade files in `resources/views/themes/{slug}/{store|service}/`

## What This Phase Adds

### 1. Websites Table

Domain configuration only. NOT the website itself — themes are the website.

```sql
CREATE TABLE websites (
    id              BIGSERIAL PRIMARY KEY,
    business_id     BIGINT REFERENCES businesses(id) ON DELETE CASCADE,
    theme_id        BIGINT REFERENCES themes(id),

    -- Domain
    subdomain       VARCHAR(100) UNIQUE,       -- "johns-bakery"
    custom_domain   VARCHAR(255) UNIQUE,       -- "johnsbakery.com"
    domain_status   ENUM('pending','active','expired','suspended') DEFAULT 'pending',
    ssl_enabled     BOOLEAN DEFAULT FALSE,

    -- Publishing
    is_published    BOOLEAN DEFAULT FALSE,
    published_at    TIMESTAMP,

    -- SEO
    seo_title       VARCHAR(255),
    seo_description TEXT,
    seo_keywords    TEXT[],
    og_image        VARCHAR(255),

    -- Analytics
    visits_count    BIGINT DEFAULT 0,
    unique_visitors BIGINT DEFAULT 0,

    created_at      TIMESTAMP DEFAULT NOW(),
    updated_at      TIMESTAMP DEFAULT NOW()
);
```

**Files to create:**
- `database/migrations/xxxx_create_websites_table.php`
- `app/Models/Website.php` — belongsTo Business, belongsTo Theme, hasMany Pages
- `app/Domains/Website/DTOs/WebsiteData.php`
- `app/Domains/Website/Events/WebsiteCreated.php`
- `app/Domains/Website/Events/WebsitePublished.php`
- `app/Domains/Website/Policies/WebsitePolicy.php`

### 2. Auto-Generate on Business Creation

Fills the empty `InitializeBusinessSettings` listener.

**Modify:** `app/Domains/Business/Listeners/InitializeBusinessSettings.php`
```php
public function handle(BusinessCreated $event): void
{
    $business = $event->business;

    // Create website record
    $website = Website::create([
        'business_id' => $business->id,
        'theme_id' => $business->themes()->wherePivot('is_active', true)->first()?->id,
        'subdomain' => Str::slug($business->name),
        'is_published' => true,
        'published_at' => now(),
        'domain_status' => 'active',
    ]);
}
```

**Register:** `EventServiceProvider` — `BusinessCreated` → `InitializeBusinessSettings`

### 3. Domain Resolution Middleware

```php
// app/Http/Middleware/ResolveBusinessDomain.php
```

Resolves:
- `{subdomain}.ilthres.com` → `websites.subdomain` → Business
- `custom.com` → `websites.custom_domain` → Business
- `ilthres.com/b/{slug}` → `businesses.slug` → Business (fallback)

Shares via `app()->instance('current_business', ...)` and request attributes.

**Helper functions:** `current_business()`, `current_website()`, `is_custom_domain()`

**Files to create:**
- `app/Http/Middleware/ResolveBusinessDomain.php`
- `app/Support/Helpers/business.php`
- `routes/website.php` — public website routes

**Register:** `bootstrap/app.php` as global middleware

### 4. Integration with Existing Routes

Path-based access `/{slug}` continues working unchanged via existing `web.php` routes.

Subdomain/custom domain access uses `routes/website.php` resolved by the middleware.

`PublicProfile` Livewire optionally reads website from middleware:
```php
// In mount(), if website is resolved via middleware, use it
$website = request()->attributes->get('website');
if ($website) {
    // Use website's theme instead of business pivot lookup
}
```

### 5. Config

Add to `config/app.php`:
```php
'base_domain' => env('APP_BASE_DOMAIN', 'ilthres.com'),
```

Add to `.env`:
```
APP_BASE_DOMAIN=ilthres.com
```

---

## Checklist

- [ ] Migration: `websites` table
- [ ] Model: `Website`
- [ ] DTO: `WebsiteData`
- [ ] Events: `WebsiteCreated`, `WebsitePublished`
- [ ] Policy: `WebsitePolicy`
- [ ] Listener: Update `InitializeBusinessSettings` to create website on business creation
- [ ] Register event in `EventServiceProvider`
- [ ] Middleware: `ResolveBusinessDomain`
- [ ] Helpers: `current_business()`, `current_website()`, `is_custom_domain()`
- [ ] Routes: `routes/website.php`
- [ ] Config: `base_domain` in `config/app.php`
- [ ] Update `PublicProfile` to optionally use website from middleware
