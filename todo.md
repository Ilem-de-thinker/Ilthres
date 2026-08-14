# Ilthres Market — Feature Roadmap

> **Architecture:** Domain-Driven SaaS | Laravel 12 + Livewire 4 | TailwindCSS + Alpine.js
>
> **Philosophy:** Business is the primary object. Everything else is a view.
>
> **Last updated:** 2026-07-13

---

## How Integration Works

### Current State (What Already Exists)

The platform **already has a website system** — it's the theme engine:

```
User visits: ilthres.com/johns-bakery
                │
                ▼
    routes/web.php → PublicProfile Livewire
                │
                ▼
    Resolves active theme (from business_theme pivot)
                │
                ▼
    Renders: themes/{theme}/{store|service}/layout.blade.php
                │
                ▼
    Passes: business, products, services, colors, heroBanners
```

This **IS** the website. Themes render the business's public presence. No separate "website builder" needed.

### What proc.md / proc2.md Adds

The new features extend the existing system — they don't replace it:

```
1. WEBSITES TABLE ──── Domain config (subdomain, custom domain, SEO, analytics)
                       NOT the website itself. Just "where does it live and how to find it."

2. DOMAIN MIDDLEWARE ── Resolves {name}.ilthres.com or custom.com → Business
                       Then the EXISTING theme system renders it.

3. PAGES/SECTIONS ──── Structured content blocks (hero, about, services, FAQ, etc.)
                       Themes ALREADY render from business data.
                       Pages/sections give merchants CONTROL over what renders where.

4. EXTERNAL BUSINESSES ── Some businesses have their own website (not hosted by us).
                       They just store their URL. We don't generate anything for them.
                       They still get a discovery profile on ilthres.com.

5. AI GENERATION ────── Auto-creates website record + default pages/sections
                       from business data during onboarding. Extends existing flow.
```

### Two Types of Businesses

| Type | Website Hosted by Us | What We Provide |
|------|---------------------|-----------------|
| **Platform-hosted** | Yes | Subdomain (`name.ilthres.com`), theme, pages, sections, SEO |
| **External website** | No | Just discovery profile on `ilthres.com/{slug}` + their own URL stored |

Both types appear in search/discovery. Only platform-hosted businesses get the full website treatment.

### Integration Points

```
EXISTING: BusinessCreated event
    └── InitializeBusinessSettings listener (current: empty placeholder)
    └── NEW: CreateBusinessWebsite listener → creates Websites record + default pages

EXISTING: PublicProfile Livewire (renders theme at /{slug})
    └── Works unchanged for path-based access
    └── NEW: Domain middleware resolves subdomain/custom domain → injects business
    └── NEW: Pages/sections data passed to theme (themes can optionally use it)

EXISTING: Theme engine (BusinessTheme pivot, customization JSON)
    └── Works unchanged
    └── NEW: ThemeEngine service wraps it, adds pages/sections data layer

EXISTING: OnboardingWizard (creates business + attaches theme)
    └── Works unchanged
    └── NEW: After business creation, CreateBusinessWebsite auto-generates website record

EXISTING: EditBusiness (updates business fields)
    └── Works unchanged
    └── NEW: WebsiteSettings component (separate — manages domain, SEO, publish status)
```

---

## Current State

### Foundation (✅ Complete)

| # | Item | Status |
|---|------|--------|
| 1 | Database seeders (PlanSeeder, CategorySeeder, ThemeSeeder) | ✅ |
| 2 | Layouts (app, dashboard, admin, auth, theme) | ✅ |
| 3 | CSS (Tailwind v4) | ✅ |
| 4 | AppServiceProvider (policy registration, defaults) | ✅ |
| 5 | EventServiceProvider | ✅ |
| 6 | web.php (all routes) | ✅ |
| 7 | OnboardingMiddleware | ✅ |
| 8 | SubscriptionMiddleware | ✅ |
| 9 | AdminMiddleware | ✅ |

### Core Features (✅ Complete)

| # | Feature | Status |
|---|---------|--------|
| 1 | Authentication (Login, Register, Forgot/Reset Password) | ✅ |
| 2 | Landing / Welcome page | ✅ |
| 3 | Onboarding Wizard (4 steps: WhatsApp → Type → Pricing → Setup) | ✅ |
| 4 | Business Profiles (create, edit, settings, public profile) | ✅ |
| 5 | Media Library (upload, organize, delete) | ✅ |
| 6 | Products (CRUD, variants, status toggle) | ✅ |
| 7 | Services (CRUD, status toggle) | ✅ |
| 8 | Themes (list, preview, customize, activate) — **THIS IS THE WEBSITE ENGINE** | ✅ |
| 9 | Explore & Search (basic DB search, discoverability toggles) | ✅ |
| 10 | Analytics (dashboard, charts, top items) | ✅ |
| 11 | Subscriptions (pricing, create, cancel, change plan) | ✅ |
| 12 | Payments (Paystack integration, billing history) | ✅ |
| 13 | Marketing (links, QR codes, click tracking) | ✅ |
| 14 | Notifications (bell, list, preferences) | ✅ |
| 15 | Discounts & Lead Forms & Loyalty & Referrals & Promotions | ✅ |
| 16 | Admin Panel (users, businesses, payments, subscriptions, themes, categories) | ✅ |

---

## TODO: New Features

### Phase 1: Website Infrastructure

> Extend the existing theme system with domain config, structured content, and subdomain access.
> The theme system IS the website. This phase adds "how to find it" and "control what renders."

#### 1.1 Websites Table (Domain Configuration)

The `websites` table does NOT replace themes. It stores:
- **Domain config:** subdomain, custom_domain, domain_status, ssl
- **SEO metadata:** seo_title, seo_description, seo_keywords, og_image
- **Publish state:** is_published, published_at
- **Analytics:** visits_count, unique_visitors
- **Theme link:** theme_id (redundant with business_theme pivot, but needed for domain resolution)

- [ ] Migration: `websites` table
  ```sql
  websites (
      id, business_id FK, theme_id FK,
      subdomain VARCHAR(100) UNIQUE,        -- "johns-bakery"
      custom_domain VARCHAR(255) UNIQUE,    -- "johnsbakery.com"
      domain_status ENUM('pending','active','expired','suspended'),
      ssl_enabled BOOLEAN DEFAULT FALSE,
      is_published BOOLEAN DEFAULT FALSE,
      published_at TIMESTAMP,
      seo_title, seo_description, seo_keywords, og_image,
      visits_count, unique_visitors,
      created_at, updated_at
  )
  ```
- [ ] Model: `Website` — belongsTo Business, belongsTo Theme
- [ ] Policy: `WebsitePolicy`
- [ ] DTO: `WebsiteData`
- [ ] Events: `WebsiteCreated`, `WebsitePublished`

#### 1.2 Website Pages & Sections (Structured Content)

Pages/sections give merchants control over what content appears on their site.
Themes already render business data — pages/sections let merchants **reorder, hide, or customize** which sections appear and what they say.

- [ ] Migration: `website_pages` table
  ```sql
  website_pages (
      id, website_id FK,
      slug VARCHAR(100),        -- "home", "about", "services", "contact"
      title VARCHAR(255),
      meta_description TEXT,
      is_visible BOOLEAN DEFAULT TRUE,
      sort_order INTEGER DEFAULT 0,
      UNIQUE(website_id, slug)
  )
  ```
- [ ] Migration: `website_sections` table
  ```sql
  website_sections (
      id, page_id FK,
      type VARCHAR(50),         -- "hero", "about", "services", "products", "gallery",
                                -- "testimonials", "contact", "map", "faq", "cta"
      content JSONB,            -- structured data per section type
      sort_order INTEGER DEFAULT 0,
      is_visible BOOLEAN DEFAULT TRUE
  )
  ```
- [ ] Model: `WebsitePage` — belongsTo Website, hasMany Sections
- [ ] Model: `WebsiteSection` — belongsTo WebsitePage
- [ ] Actions: `CreatePage`, `UpdatePage`, `ReorderPages`, `CreateSection`, `UpdateSection`, `ReorderSections`
- [ ] Events: `PageCreated`, `SectionUpdated`

#### 1.3 Auto-Generate Website on Business Creation

Extends the existing `BusinessCreated` event flow. The current `InitializeBusinessSettings` listener is empty — this fills it.

- [ ] Listener: `CreateBusinessWebsite` (listens to `BusinessCreated`)
  - Creates `Websites` record with subdomain = `Str::slug($business->name)`
  - Sets `is_published = true`, `domain_status = 'active'`
  - Auto-creates default pages: Home, About, Contact, Services (or Products)
  - Auto-populates sections from business data (name, description, logo, phone, address)
  - Attaches the theme already selected during onboarding
- [ ] Register in `EventServiceProvider`: `BusinessCreated` → `CreateBusinessWebsite`
- [ ] Handle slug collision: `johns-bakery` → `johns-bakery-1` if exists

#### 1.4 Domain Resolution Middleware

Adds subdomain/custom domain access. **Path-based access (`/{slug}`) continues to work unchanged.**

- [ ] Middleware: `ResolveBusinessDomain`
  ```
  Request comes in:
    ├── {subdomain}.ilthres.com → lookup websites.subdomain → Business
    ├── custom.com             → lookup websites.custom_domain → Business
    └── ilthres.com/b/{slug}   → lookup businesses.slug → Business (fallback)
  ```
- [ ] Share resolved business/website via `app()->instance()` and request attributes
- [ ] Helper functions: `current_business()`, `current_website()`, `is_custom_domain()`
- [ ] Register in `bootstrap/app.php` as global middleware
- [ ] Create `routes/website.php` — public website routes resolved via middleware
- [ ] Update `PublicProfile` Livewire to accept website from middleware (optional — path-based still works)

#### 1.5 Website Dashboard (Merchant Side)

- [ ] Livewire: `WebsiteSettings` — subdomain config, custom domain, SEO, publish toggle
- [ ] Livewire: `PageManager` — list, create, edit, reorder pages
- [ ] Livewire: `SectionEditor` — add/edit/reorder sections per page, JSON content editing per type
- [ ] Views: `website-settings.blade.php`, `page-manager.blade.php`, `section-editor.blade.php`
- [ ] Routes: `/dashboard/website`, `/dashboard/website/pages`, `/dashboard/website/pages/{page}/edit`

#### 1.6 Theme Engine Enhancement

The existing `PublicProfile` Livewire already resolves themes and renders them.
This phase adds an optional data layer — themes CAN use pages/sections if they want finer control.

- [ ] Service: `ThemeEngine` — wraps existing theme resolution, adds pages/sections data
- [ ] Pass `$sections` collection to theme views (themes that don't use it simply ignore it)
- [ ] Update theme blade files to optionally render from `$sections` instead of hardcoded blocks
- [ ] Themes that don't use sections continue working as-is (backward compatible)

---

### Phase 2: Business Locations (Multi-Branch)

> Many businesses have branches. Support location-based discovery.

- [ ] Migration: `business_locations` table
  ```sql
  business_locations (
      id, business_id FK,
      country, state, city, address, postal_code,
      latitude DECIMAL(10,8), longitude DECIMAL(11,8), geohash VARCHAR(12),
      phone, email,
      opening_hours JSONB,       -- [{"day":"monday","open":"08:00","close":"18:00","closed":false}]
      is_primary BOOLEAN DEFAULT FALSE,
      is_headquarters BOOLEAN DEFAULT FALSE,
      created_at, updated_at
  )
  ```
- [ ] Geo index for "near me" search
- [ ] Model: `BusinessLocation` — belongsTo Business
- [ ] Add `locations()` HasMany to Business model
- [ ] Add `primaryLocation()` convenience method
- [ ] Actions: `CreateLocation`, `UpdateLocation`, `DeleteLocation`, `SetPrimaryLocation`
- [ ] DTO: `LocationData`
- [ ] Events: `LocationCreated`, `LocationUpdated`, `LocationDeleted`
- [ ] Policy: `LocationPolicy`
- [ ] Livewire: `LocationManager` — CRUD, set primary
- [ ] Views: `location-manager.blade.php`
- [ ] Routes: `/dashboard/locations`

---

### Phase 3: Reviews & Reputation

> Reviews build trust. Verified reviews (from bookings) are worth more.

- [ ] Migration: `reviews` table
  ```sql
  reviews (
      id, business_id FK, user_id FK, booking_id FK,
      rating INTEGER CHECK (rating >= 1 AND rating <= 5),
      title, body TEXT,
      photos JSONB,
      business_response TEXT, responded_at,
      status ENUM('pending','approved','rejected','flagged'),
      helpful_count INTEGER DEFAULT 0,
      created_at, updated_at
  )
  ```
- [ ] Migration: `review_votes` table (review_id, user_id, is_helpful)
- [ ] Model: `Review`, `ReviewVote`
- [ ] Actions: `SubmitReview`, `RespondToReview`, `ModerateReview`, `VoteReview`
- [ ] DTO: `ReviewData`
- [ ] Events: `ReviewSubmitted`, `ReviewApproved`, `ReviewRejected`, `ReviewResponded`
- [ ] Listeners: `UpdateBusinessRating`, `NotifyBusinessOfReview`
- [ ] Policy: `ReviewPolicy`
- [ ] Livewire (merchant): `ReviewManager` — list, respond, moderate
- [ ] Livewire (customer): `WriteReview` — submit review form
- [ ] Views: `review-manager.blade.php`, `write-review.blade.php`
- [ ] Routes: `/dashboard/reviews`, `/{slug}/reviews` (public)
- [ ] Business trust score computation (based on reviews + verification + response rate)

---

### Phase 4: Bookings & Appointments

> Service businesses need appointment scheduling.

- [ ] Migration: `booking_slots` table (business_id, service_id, location_id, day_of_week, start_time, end_time, slot_duration, max_bookings_per_slot)
- [ ] Migration: `bookings` table
  ```sql
  bookings (
      id, business_id FK, service_id FK, location_id FK,
      customer_id FK, customer_name, customer_email, customer_phone,
      booking_date, start_time, end_time, timezone,
      status ENUM('pending','confirmed','completed','cancelled','no_show'),
      payment_status, payment_amount,
      customer_notes, business_notes, reminder_sent,
      created_at, updated_at
  )
  ```
- [ ] Model: `BookingSlot`, `Booking`
- [ ] Actions: `CreateBooking`, `ConfirmBooking`, `CancelBooking`, `CompleteBooking`, `CreateBookingSlots`
- [ ] DTO: `BookingData`, `BookingSlotData`
- [ ] Events: `BookingCreated`, `BookingConfirmed`, `BookingCancelled`, `BookingCompleted`
- [ ] Listeners: `NotifyBusinessOwner`, `SendCustomerConfirmation`, `UpdateBusinessAnalytics`
- [ ] Job: `SendBookingReminder` (queued)
- [ ] Policy: `BookingPolicy`
- [ ] Livewire (merchant): `BookingManager`
- [ ] Livewire (customer): `BookAppointment`
- [ ] Views: `booking-manager.blade.php`, `book-appointment.blade.php`
- [ ] Routes: `/dashboard/bookings`, `/{slug}/book` (public)

---

### Phase 5: Messaging & Communication

> Customers need to reach businesses. Businesses need to respond.

- [ ] Migration: `conversations` table (business_id, customer_id, source, status, priority, last_message_at)
- [ ] Migration: `messages` table (conversation_id, sender_id, sender_type, content, attachments JSONB, read_at, delivered_at)
- [ ] Model: `Conversation`, `Message`
- [ ] Actions: `StartConversation`, `SendMessage`, `CloseConversation`, `MarkAsRead`
- [ ] DTO: `MessageData`, `ConversationData`
- [ ] Events: `ConversationStarted`, `MessageSent`, `MessageRead`
- [ ] Listeners: `NotifyBusinessOfMessage`, `NotifyCustomerOfMessage`
- [ ] Policy: `ConversationPolicy`, `MessagePolicy`
- [ ] Livewire (merchant): `InboxManager`
- [ ] Livewire (customer): `ChatWidget`
- [ ] Views: `inbox-manager.blade.php`, `chat-widget.blade.php`
- [ ] Routes: `/dashboard/inbox`, `/{slug}/contact` (public chat)

---

### Phase 6: Orders & E-Commerce

> Product stores need order management.

- [ ] Migration: `orders` table (business_id, customer_id, order_number, status, subtotal, tax, shipping, discount, total, payment_method, payment_status, delivery_method, tracking_number)
- [ ] Migration: `order_items` table (order_id, product_id, product_name, product_price, quantity, total)
- [ ] Migration: `payments` table (order_id, business_id, amount, gateway, gateway_ref, status)
- [ ] Model: `Order`, `OrderItem`, `Payment`
- [ ] Actions: `PlaceOrder`, `UpdateOrderStatus`, `ProcessPayment`, `IssueRefund`
- [ ] DTO: `OrderData`, `OrderItemData`
- [ ] Events: `OrderPlaced`, `OrderShipped`, `OrderDelivered`, `OrderCancelled`, `PaymentReceived`
- [ ] Listeners: `NotifyBusinessOfOrder`, `SendCustomerOrderUpdate`, `UpdateBusinessAnalytics`
- [ ] Policy: `OrderPolicy`
- [ ] Livewire (merchant): `OrderManager`
- [ ] Livewire (customer): `CheckoutFlow`
- [ ] Views: `order-manager.blade.php`, `checkout.blade.php`
- [ ] Routes: `/dashboard/orders`, `/{slug}/checkout` (public)

---

### Phase 7: Advanced Search (Meilisearch)

> Search businesses, not websites. The website is only the presentation layer.

- [ ] Install Laravel Scout + Meilisearch driver
- [ ] Configure `config/scout.php`
- [ ] Business searchable array: name, slug, description, category, tags, services, products, city, state, country, location, rating, review_count, trust_score, is_verified
- [ ] `BusinessObserver` — sync search index on create/update/delete
- [ ] `ProductObserver`, `ServiceObserver` — update business index when items change
- [ ] Job: `SyncBusinessToSearchIndex` (bulk reindex)
- [ ] Faceted search: category, location, rating, verified, open_now
- [ ] Geo search: "near me" with radius
- [ ] Sort: relevance, distance, rating, newest, popular
- [ ] Upgrade `ExploreSearch` Livewire to use Meilisearch
- [ ] Update explore view with faceted filters, geo search, sort options

---

### Phase 8: AI Website Generation

> User signs up → AI generates complete website content in seconds.
> Extends existing onboarding: after business is created, AI fills in the pages/sections.

- [ ] Service: `AIWebsiteGenerator`
  - Selects theme based on business category
  - Generates subdomain from business name
  - Creates pages: Home, About, Contact, Services
  - Populates sections: hero, about, services, contact, FAQ
- [ ] Job: `GenerateAIWebsite` (queued — generates content async after onboarding)
- [ ] AI: Generate tagline, "About" paragraph, service descriptions, FAQ, SEO metadata
- [ ] Migration: `ai_generations` table (business_id, type, prompt, content, model_used, tokens_used, cost, status)
- [ ] Migration: `ai_prompts` table (name, type, template, variables JSONB)
- [ ] Seed: Default prompt templates
- [ ] Livewire: `AIContentEditor` — regenerate, edit, approve AI content
- [ ] Wire into `CreateBusinessWebsite` listener — after creating pages, dispatch `GenerateAIWebsite` job

---

### Phase 9: Portfolio & Gallery

> Businesses showcase their work visually.

- [ ] Migration: `portfolio` table (business_id, title, description, photos JSONB, sort_order)
- [ ] Migration: `gallery` table (business_id, image, caption, sort_order)
- [ ] Models: `Portfolio`, `Gallery`
- [ ] Actions: CRUD for both
- [ ] Livewire: `PortfolioManager`, `GalleryManager`
- [ ] Theme components: `portfolio.blade.php`, `gallery.blade.php` (already exist in themes)
- [ ] Routes: `/dashboard/portfolio`, `/dashboard/gallery`, `/{slug}/portfolio` (public)

---

### Phase 10: API & Mobile App

> API for future mobile app (.NET MAUI).

- [ ] Create `routes/api.php`
- [ ] Install Laravel Sanctum
- [ ] Public: `/api/v1/businesses`, `/api/v1/businesses/{slug}`, `/api/v1/search`, `/api/v1/categories`, `/api/v1/nearby`
- [ ] Auth: `/api/v1/register`, `/api/v1/login`, `/api/v1/logout`
- [ ] Protected: business CRUD, products, services, bookings, orders, reviews, messages
- [ ] Rate limiting: 60/min (public), 120/min (authenticated)
- [ ] API Resources for all models
- [ ] API Controllers: Business, Search, Auth, Booking, Order, Review, Message

---

### Phase 11: Queue Jobs & Background Processing

> Heavy work goes to the queue.

- [ ] `GenerateAIWebsite` — AI content generation
- [ ] `GenerateSEOReport` — SEO audit
- [ ] `SyncBusinessToSearchIndex` — Meilisearch sync
- [ ] `ProcessPayment` — Payment gateway
- [ ] `SendBookingReminder` — Appointment reminders
- [ ] `GenerateAIContent` — Specific AI content
- [ ] `SendEmailCampaign` — Marketing emails
- [ ] `GenerateAnalyticsReport` — Analytics aggregation
- [ ] Wire event→listener→job chains in EventServiceProvider

---

### Phase 12: Marketing Enhancement

> Extend existing marketing features.

- [ ] Migration: `campaigns` table (business_id, name, type, subject, body, target JSONB, scheduled_at, stats)
- [ ] Migration: `coupons` table (business_id, code, discount_type, discount_value, limits, validity)
- [ ] Models: `Campaign`, `Coupon`
- [ ] Actions: `CreateCampaign`, `ScheduleCampaign`, `SendCampaign`, `CreateCoupon`, `ValidateCoupon`
- [ ] Livewire: `CampaignManager`, `CouponManager`
- [ ] Routes: `/dashboard/campaigns`, `/dashboard/coupons`

---

## Implementation Order

```
Phase 1:  Website Infrastructure ─────────── (Extend theme system with domain config + pages/sections)
Phase 2:  Business Locations ─────────────── (Multi-branch support)
Phase 3:  Reviews & Reputation ───────────── (Trust building)
Phase 4:  Bookings & Appointments ────────── (Service scheduling)
Phase 5:  Messaging ──────────────────────── (Customer communication)
Phase 6:  Orders & E-Commerce ────────────── (Product transactions)
Phase 7:  Advanced Search ────────────────── (Meilisearch integration)
Phase 8:  AI Website Generation ──────────── (AI-powered content creation)
Phase 9:  Portfolio & Gallery ────────────── (Visual showcase)
Phase 10: API & Mobile App ───────────────── (Future mobile)
Phase 11: Queue Jobs ─────────────────────── (Background processing)
Phase 12: Marketing Enhancement ──────────── (Campaigns, coupons)
```

Each phase is completed end-to-end (domain files → Livewire → views → routes → tests) before moving to the next.

---

## Dependency Graph

```
Authentication ──────────────────────────────── (DONE)
Business Profiles ───────────────────────────── (DONE)
Media Library ───────────────────────────────── (DONE)
Products / Services ─────────────────────────── (DONE)
Themes ──────────────────────────────────────── (DONE) ← THIS IS THE WEBSITE ENGINE
Analytics ───────────────────────────────────── (DONE)
Subscriptions / Payments ────────────────────── (DONE)
Marketing Links ─────────────────────────────── (DONE)
Notifications ───────────────────────────────── (DONE)
         │
         ▼
    Phase 1: Website Infrastructure
    (adds domain config + pages/sections ON TOP of existing themes)
         │
    ┌────┴────┐
    ▼         ▼
 Phase 2   Phase 3
Locations  Reviews
    │         │
    └────┬────┘
         ▼
    Phase 4: Bookings
         │
         ▼
    Phase 5: Messaging
         │
         ▼
    Phase 6: Orders
         │
    ┌────┴────┐
    ▼         ▼
 Phase 7   Phase 8
Search     AI Generation
    │         │
    └────┬────┘
         ▼
    Phase 9: Portfolio/Gallery
         │
         ▼
    Phase 10: API
         │
         ▼
    Phase 11: Queue Jobs
         │
         ▼
    Phase 12: Marketing+
```
