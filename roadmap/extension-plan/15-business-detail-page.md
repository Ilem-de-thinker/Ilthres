# 15 — Business Detail Page

> Separate the business detail/discovery view from the themed website. The detail page is where customers learn about the business. The themed website is what they visit next.

## Current State

- `/{business:slug}` → `PublicProfile` Livewire renders the themed website directly
- Explore page lists businesses with basic cards
- No dedicated detail page showing reviews, verification, ratings, etc.

## What This Phase Adds

### Flow

```
/explore (discover page)
  └─ lists categories, businesses
      └─ click business → /{slug} (detail page)
          ├─ Business info, KYC badge, reviews, rating
          ├─ Products/Services preview
          ├─ Contact info, map
          └─ [View Site] button
              ├─ Our hosted site → /{slug}/site (themed website)
              └─ External site → redirects to their URL
```

### 1. Business Detail Page (`/{slug}`)

New Livewire component or controller view showing:

- **Header**: Business name, logo, cover image, KYC verified badge, category, city/state
- **About**: Business description / about section
- **Rating & Reviews**: Star rating, review count, latest reviews
- **Products/Services**: Preview grid (first 4-6 items with "See all" link)
- **Contact**: Phone, WhatsApp, email, address with map
- **Social links**: Instagram, Facebook, Twitter, etc.
- **View Site CTA**: Prominent button → themed site or external URL

### 2. Themed Website Route (`/{slug}/site`)

Move current `PublicProfile` themed rendering from `/{slug}` to `/{slug}/site`.

```php
// routes/web.php
Route::get('/{business:slug}/site', PublicProfile::class)->name('business.site');
```

### 3. Business Detail Route (`/{slug}`)

Replace current `/{slug}` route with the new detail page.

```php
Route::get('/{business:slug}', BusinessDetail::class)->name('business.profile');
```

### 4. Explore Page Update

Update business cards in the explore page to link to the detail page instead of the themed website.

### 5. External Website Support

Add `website_url` field to businesses table. If set, "View Site" button redirects to external URL instead of `/{slug}/site`.

```sql
ALTER TABLE businesses ADD COLUMN website_url VARCHAR(255) NULL;
```

### 6. Sub-domain Routes

Keep existing subdomain routes (`{subdomain}.ilthres.com`) rendering the themed website directly — those are the "View Site" destination.

---

## Files to Create

- `app/Domains/Business/Livewire/BusinessDetail.php`
- `resources/views/livewire/business/business-detail.blade.php`
- `resources/views/pages/business-detail.blade.php`

## Files to Modify

- `routes/web.php` — change `/{slug}` route, add `/{slug}/site`
- `app/Domains/Business/Livewire/PublicProfile.php` — rename/move to `/{slug}/site`
- `app/Domains/Search/Livewire/ExploreSearch.php` — update card links
- `resources/views/livewire/search/explore-search.blade.php` — update card links
- `database/migrations/xxxx_add_website_url_to_businesses_table.php` — add `website_url` field
- `app/Models/Business.php` — add `website_url` to fillable

## Checklist

- [x] Migration: add `website_url` to businesses table
- [x] Update Business model fillable
- [x] Create `BusinessDetail` Livewire component
- [x] Create business-detail.blade.php view
- [x] Create business-detail page view
- [x] Move `PublicProfile` route from `/{slug}` to `/{slug}/site`
- [x] Update `/{slug}` to point to `BusinessDetail`
- [x] Update explore page card links
- [x] Add "View Site" button logic (our site vs external URL)
- [x] Test all public routes still work
