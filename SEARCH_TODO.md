# TODO: AI Search, Business Search & Product Discoverability

## Architecture Decisions

- **Search Engine:** Laravel Scout `database` driver (SQLite → MySQL, shared hosting compatible)
- **AI Intelligence:** Rule-based — expanded keywords, synonyms, intent detection, Pidgin support
- **Discoverability:** Strictly respect `is_discoverable` in ALL search modes

---

## Phase 1: Search Infrastructure

- [ ] Install `laravel/scout` via composer
- [ ] Publish Scout config (`php artisan vendor:publish --provider="Laravel\Scout\ScoutServiceProvider"`)
- [ ] Configure `config/scout.php` — set driver to `database`, configure queue connection
- [ ] Create `config/search.php` — keyword mappings, category maps, ranking weights, synonyms
- [ ] Add `Searchable` trait + `toSearchableArray()` to `Business` model
- [ ] Add `Searchable` trait + `toSearchableArray()` to `Product` model
- [ ] Add `Searchable` trait + `toSearchableArray()` to `Service` model
- [ ] Run `php artisan scout:import` to verify indexing works

### Files

| File | Action |
|------|--------|
| `composer.json` | Add `laravel/scout` |
| `config/scout.php` | New (published) |
| `config/search.php` | New |
| `app/Models/Business.php` | Add `Searchable` trait |
| `app/Models/Product.php` | Add `Searchable` trait |
| `app/Models/Service.php` | Add `Searchable` trait |

---

## Phase 2: Search Indexing & Events

- [ ] Fill in `UpdateProductSearchIndex` listener (currently empty placeholder)
- [ ] Create `app/Domains/Service/Listeners/UpdateServiceSearchIndex.php`
- [ ] Create `app/Domains/Business/Listeners/UpdateBusinessSearchIndex.php`
- [ ] Wire listeners to existing events in `EventServiceProvider`
- [ ] Create `app/Console/Commands/ReindexSearchCommand.php` — full reindex artisan command
- [ ] Verify `ProductCreated/Updated/Deleted` events trigger index updates
- [ ] Verify `ServiceCreated/Updated/Deleted` events trigger index updates
- [ ] Verify `BusinessCreated/Updated/Deleted` events trigger index updates

### Files

| File | Action |
|------|--------|
| `app/Domains/Product/Listeners/UpdateProductSearchIndex.php` | Fill in |
| `app/Domains/Service/Listeners/UpdateServiceSearchIndex.php` | New |
| `app/Domains/Business/Listeners/UpdateBusinessSearchIndex.php` | New |
| `app/Providers/EventServiceProvider.php` | Add listener bindings |
| `app/Console/Commands/ReindexSearchCommand.php` | New |

---

## Phase 3: AI Search Overhaul

- [ ] Rewrite `AISearchService` to use `::search()` (Scout) instead of raw LIKE queries
- [ ] Fix `is_discoverable` filter — apply in AI search mode (currently missing)
- [ ] Enhance `SearchParser` — intent detection (buying vs browsing vs comparing)
- [ ] Enhance `SearchParser` — price range extraction ("under 5000", "between 10k and 20k")
- [ ] Enhance `SearchParser` — synonym expansion (auto-add related keywords from config)
- [ ] Enhance `SearchParser` — Nigerian Pidgin/colloquial support
- [ ] Enhance `SearchParser` — expand service keywords to 50+ entries
- [ ] Enhance `SearchParser` — expand product keywords to 50+ entries
- [ ] Rewrite `RankingService` — fix N+1 queries (use `withCount` eager loading)
- [ ] Rewrite `RankingService` — add search-relevance score from Scout
- [ ] Rewrite `RankingService` — add freshness score (recently indexed/updated)
- [ ] Rewrite `RankingService` — add completeness score (logo, description, images)
- [ ] Rewrite `RankingService` — make weights configurable via `config/search.php`
- [ ] Create `SuggestService` — autocomplete/typeahead (recent + popular + fuzzy)

### Files

| File | Action |
|------|--------|
| `app/Domains/Search/Services/AISearchService.php` | Rewrite |
| `app/Domains/Search/Services/SearchParser.php` | Enhance |
| `app/Domains/Search/Services/RankingService.php` | Rewrite |
| `app/Domains/Search/Services/SuggestService.php` | New |
| `config/search.php` | Update with expanded mappings |

---

## Phase 4: ExploreSearch Component

- [ ] Rewrite `ExploreSearch` Livewire component — use Scout for all search modes
- [ ] Apply `is_discoverable` filter consistently (AI + traditional)
- [ ] Add sort options: Relevance, Newest, Popular, Rating, Distance
- [ ] Add "Trending" tab — shows popular items when no search query
- [ ] Add "Browse" tab — category-based browsing
- [ ] Cache categories query (avoid per-render DB hit)
- [ ] Add search history tracking (store in session)
- [ ] Create `SearchSuggestions` Livewire component for real-time typeahead
- [ ] Update `explore-search.blade.php` — autocomplete dropdown, sort, trending
- [ ] Create `search-suggestions.blade.php` view
- [ ] Add debounced input for suggestions (wire:model.live.debounce)

### Files

| File | Action |
|------|--------|
| `app/Domains/Search/Livewire/ExploreSearch.php` | Rewrite |
| `app/Domains/Search/Livewire/SearchSuggestions.php` | New |
| `resources/views/livewire/search/explore-search.blade.php` | Rewrite |
| `resources/views/livewire/search/search-suggestions.blade.php` | New |

---

## Phase 5: Discoverability

- [ ] Migration: add `tags` (JSON) + `search_boost` (integer) + `last_indexed_at` (timestamp) to `businesses`
- [ ] Migration: add `tags` (JSON) + `last_indexed_at` (timestamp) to `products`
- [ ] Migration: add `tags` (JSON) + `last_indexed_at` (timestamp) to `services`
- [ ] Update `Business` model — add `tags`, `search_boost`, `last_indexed_at` to fillable/casts
- [ ] Update `Product` model — add `tags`, `last_indexed_at` to fillable/casts
- [ ] Update `Service` model — add `tags`, `last_indexed_at` to fillable/casts
- [ ] Create `DiscoverService` — trending items (views + orders, last 7 days)
- [ ] Create `DiscoverService` — related items (same category, similar tags)
- [ ] Create `DiscoverService` — featured/spotlight (admin-curated or subscription-tier)
- [ ] Create `DiscoverPage` Livewire component (browse landing before search)
- [ ] Create `discover-page.blade.php` view
- [ ] Update `ProductForm` — add tags field (JSON input or tag chips)
- [ ] Update `BusinessSettings` — add discoverability settings (visibility toggles)
- [ ] Update `ExploreSearch` — integrate DiscoverService for trending/browse

### Files

| File | Action |
|------|--------|
| `database/migrations/xxxx_add_search_fields.php` | New migration |
| `app/Models/Business.php` | Update fillable/casts |
| `app/Models/Product.php` | Update fillable/casts |
| `app/Models/Service.php` | Update fillable/casts |
| `app/Domains/Search/Services/DiscoverService.php` | New |
| `app/Domains/Search/Livewire/DiscoverPage.php` | New |
| `resources/views/livewire/search/discover-page.blade.php` | New |
| `app/Domains/Product/Livewire/ProductForm.php` | Add tags |
| `app/Domains/Business/Livewire/BusinessSettings.php` | Add discoverability |

---

## Phase 6: Performance & Tests

- [ ] Add rate limiting on `ExploreSearch` (throttle: 60 requests/min)
- [ ] Cache popular searches (top 100 queries, 1 hour TTL)
- [ ] Cache category list in `ExploreSearch` (Cache::remember)
- [ ] Track search analytics (log to `analytics_events` for trending calc)
- [ ] Create `TrackSearchAction` — log search queries
- [ ] Create `SearchController` — API endpoints (parse, suggestions)
- [ ] Add search routes to `routes/web.php`
- [ ] Write Pest test: `SearchParser` parsing logic
- [ ] Write Pest test: `RankingService` scoring
- [ ] Write Pest test: `AISearchService` integration
- [ ] Write Pest test: `ExploreSearch` component
- [ ] Write Pest test: Search index sync (create/update/delete)
- [ ] Write Pest test: `DiscoverService` trending/related
- [ ] Run `php artisan pint` (code style)
- [ ] Run `php artisan test` (all tests pass)

### Files

| File | Action |
|------|--------|
| `app/Domains/Search/Actions/TrackSearchAction.php` | New |
| `app/Domains/Search/Http/SearchController.php` | New |
| `routes/web.php` | Add search routes |
| `tests/Feature/Search/SearchParserTest.php` | New |
| `tests/Feature/Search/RankingServiceTest.php` | New |
| `tests/Feature/Search/AISearchServiceTest.php` | New |
| `tests/Feature/Search/ExploreSearchTest.php` | New |
| `tests/Feature/Search/SearchIndexSyncTest.php` | New |
| `tests/Feature/Search/DiscoverServiceTest.php` | New |

---

## Summary

| Phase | Files Changed | Files New | Complexity |
|-------|--------------|-----------|------------|
| 1. Infrastructure | 4 | 2 | Low |
| 2. Indexing & Events | 1 | 4 | Low |
| 3. AI Search | 3 | 1 | High |
| 4. ExploreSearch | 1 | 3 | High |
| 5. Discoverability | 4 | 3 | Medium |
| 6. Performance & Tests | 1 | 8 | Medium |
| **Total** | **14** | **21** | **35 files** |

---

## Completion Checklist

- [ ] All phases complete
- [ ] `php artisan pint` passes
- [ ] `php artisan test` passes
- [ ] Scout indexing works on SQLite
- [ ] Scout indexing works on MySQL (test after migration)
- [ ] `is_discoverable` respected in all search modes
- [ ] No N+1 queries in ranking
- [ ] Categories cached in ExploreSearch
- [ ] Search suggestions work (typeahead)
- [ ] Trending/Browse tabs functional
- [ ] Tags field in product/business forms
- [ ] Rate limiting on search endpoints
