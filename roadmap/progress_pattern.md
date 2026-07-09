# Progress Pattern

> **Principle:** Features are implemented one at a time, in dependency order. Each feature is fully completed (including tests) before the next begins.
>
> **Last updated:** 2026-07-06 — Features 1-14 (Authentication through Notifications) completed.

---

## How It Works

Every feature follows the same lifecycle:

```
Feature: [Name]
  ├── 1. Domain files  (Actions, DTOs, Events, Listeners, Policies, Services, Jobs, Rules)
  ├── 2. Livewire components  (with views)
  ├── 3. Blade page views  (layouts, pages)
  ├── 4. Routes  (web.php, admin.php)
  └── 5. Tests  (Feature + Unit)
```

We **never** create files for multiple features at once. Each feature is completed end-to-end before touching the next.

---

## Current State

### Foundation (seeders, layout, CSS, config)
| Item | Status |
|------|--------|
| Database seeders (PlanSeeder, CategorySeeder, ThemeSeeder) | ✅ Done |
| Layouts (app.blade.php, dashboard.blade.php) | ✅ Done |
| CSS (Tailwind v4) | ✅ Done |
| AppServiceProvider (policy registration, defaults) | ✅ Done |
| EventServiceProvider | ✅ Done |
| web.php (all routes) | ✅ Done |

### Models & Migrations (all created)
| Model | Migration | Status |
|-------|-----------|--------|
| User | ✅ | ✅ Done |
| Business | ✅ | ✅ Done |
| Product | ✅ | ✅ Done |
| Service | ✅ | ✅ Done |
| Category | ✅ | ✅ Done |
| Media | ✅ | ✅ Done |
| Theme | ✅ | ✅ Done |
| Subscription | ✅ | ✅ Done |
| Plan | ✅ | ✅ Done |
| business_theme (pivot) | ✅ | ✅ Done |

### Features
| # | Feature | Domain Files | Livewire | Views | Routes | Tests | Status |
|---|---------|-------------|----------|-------|--------|-------|--------|
| 1 | Authentication | ✅ All (actions, DTOs, events, listeners, services, jobs, rules, validation, policy) | ✅ Login, Register, ForgotPassword, ResetPassword | ✅ 4 page views | ✅ web.php | ❌ | ✅ Completed |
| 2 | Landing / Welcome | ⬜ Domain not extracted | ⬜ | ✅ welcome, explore, pricing pages | ✅ web.php | ❌ | ✅ Completed |
| 3 | Onboarding Wizard | ✅ CreateBusiness, BusinessData, BusinessCreated, InitializeSettings, BusinessPolicy | ✅ OnboardingWizard (4 steps: WhatsApp → Type → Pricing → Setup with theme + logo upload) | ✅ onboarding page + wizard partial | ✅ /onboarding | ❌ | ✅ Completed |
| 4 | Business Profiles | ✅ CreateBusiness, UpdateBusiness, DeleteBusiness, UploadLogo, UploadCover, BusinessCreated, BusinessUpdated, BusinessDeleted, BusinessPolicy | ✅ EditBusiness (unified: details, contact, address, logo/cover, theme), BusinessSettings, PublicProfile (with theme CSS vars) | ✅ business-edit, business-settings, create-business, public-profile | ✅ web.php | ❌ | ✅ Completed |
| 5 | Media Library | ✅ UploadMedia, DeleteMedia, OrganizeMedia, MediaData, MediaUploaded, MediaDeleted, MediaPolicy | ✅ MediaLibrary, UploadMedia | ✅ media-library, upload-media, media page | ✅ /dashboard/media | ❌ | ✅ Completed |
| 6 | Products (store) | ✅ CreateProduct, UpdateProduct, DeleteProduct, ToggleStatus, ProductData, ProductCreated, ProductUpdated, ProductDeleted, ProductViewed, UpdateProductSearchIndex, ProductPolicy | ✅ ProductList, ProductCreate, ProductEdit, ProductDetail, ProductForm, ProductVariants | ✅ products, product-create, product-edit, product-detail, product-variants | ✅ web.php | ❌ | ✅ Completed |
| 7 | Services (service) | ✅ CreateService, UpdateService, DeleteService, ToggleServiceStatus, ServiceData, ServiceCreated, ServiceUpdated, ServiceDeleted, ServicePolicy | ✅ ServiceList, ServiceCreate, ServiceEdit, ServiceDetail, ServiceForm | ✅ services, service-create, service-edit, service-detail | ✅ web.php | ❌ | ✅ Completed |
| 8 | Themes (engine) | ✅ ActivateTheme, CustomizeTheme, ThemeData, ThemeActivated, ThemeCustomized, ThemePolicy | ✅ ThemeList, ThemePreview, ThemeCustomizer, ThemeRenderer | ✅ theme-list, theme-preview, theme-customizer, theme-renderer, theme-settings page | ✅ /dashboard/theme | ❌ | ✅ Completed |
| 9 | Explore & Search | ✅ Actions, Events | ✅ ExploreSearch | ✅ explore.blade.php, explore-search, discoverability toggles in EditBusiness/ProductForm/ServiceForm | ✅ web.php | ❌ | ✅ Completed |
| 10 | Analytics | ✅ Actions, Events | ✅ AnalyticsDashboard, VisitorsChart, TopItems | ✅ analytics-dashboard, visitors-chart, top-items, analytics page, dashboard integration | ✅ web.php | ❌ | ✅ Completed |
| 11 | Subscriptions | ✅ Actions (Create, Cancel, ChangePlan), DTOs (SubscriptionData), Events (Created, Cancelled, PlanChanged), Policy | ✅ PricingPage, SubscriptionStatus | ✅ pricing-page, subscription-status, subscription page, dashboard integration | ✅ web.php | ❌ | ✅ Completed |
| 12 | Payments | ✅ Actions (RecordPayment), DTOs (TransactionData), Events (Received, Failed), Policy | ✅ BillingHistory | ✅ billing page + sidebar | ✅ web.php | ❌ | ✅ Completed |
| 13 | Marketing | ✅ Actions (CreateLink, DeleteLink, TrackClick), DTOs, Events (Created, Deleted, Clicked), Policy | ✅ MarketingLinks, CreateLink, LinkStats | ✅ marketing-links, create-link, link-stats, marketing pages | ✅ web.php | ❌ | ✅ Completed |
| 14 | Notifications | ✅ Actions (MarkAsRead, MarkAllAsRead, UpdatePreferences), Events (NotificationRead, NotificationSent), Laravel Notifications (ReviewReceived, SubscriptionExpiring, VerificationApproved) | ✅ NotificationBell, NotificationList, NotificationPreferences | ✅ notification-bell, notification-list, notification-preferences, notifications page, notification-preferences page, dashboard integration | ✅ web.php | ❌ | ✅ Completed |

### Infrastructure
| Item | Status |
|------|--------|
| OnboardingMiddleware (redirects to wizard step) | ✅ |
| Business switcher (multi-business dropdown) | ✅ |
| Separate dashboards (store vs service) | ✅ |
| Logo file upload (JPEG/PNG/WebP, 2MB max) | ✅ |
| Storage link (`php artisan storage:link`) | ✅ |
| Policy registration (AppServiceProvider) | ✅ |
| Login rate limiting (5 attempts/min) | ✅ |
| Password-reset rate limiting (3 attempts/min) | ✅ |
| Strong password enforcement (mixed case, numbers, symbols) | ✅ |
| Theme integration in onboarding + settings | ✅ |
| Theme engine (CSS variable injection, ActivateTheme, CustomizeTheme) | ✅ |
| Theme scoping by business type (product_store vs service_business) | ✅ |
| Paystack payment integration (onboarding) | ✅ |

---

## Feature Implementation Order

Features are built in dependency order. A feature's dependencies must be complete before it starts.

```
 1. Authentication ───────────────────────────── (no deps)
 2. Landing / Welcome page ───────────────────── (no deps)
 3. Onboarding Wizard ────────────────────────── (needs Auth)
 4. Business Profiles ────────────────────────── (needs Auth)
 5. Media Library ────────────────────────────── (needs Business)
 6. Products (product_store) ─────────────────── (needs Business, Media)
 7. Services (service_business) ──────────────── (needs Business, Media)
 8. Themes ───────────────────────────────────── (needs Business, Media)
 9. Explore & Search ─────────────────────────── (needs Business, Product, Service)
10. Analytics ────────────────────────────────── (needs Business)
11. Subscriptions / Pricing ──────────────────── (needs Business, Payments)
12. Payments / Invoicing ─────────────────────── (needs Business)
13. Marketing Links & QR Codes ───────────────── (needs Business, Analytics)
14. Notifications ────────────────────────────── (needs Auth)
```

---

## Per-Feature Checklist

When implementing a feature, follow these steps:

```
[ ] 1. Domain directory structure
[ ] 2. DTOs (immutable data objects)
[ ] 3. Events (past tense naming, self-documenting)
[ ] 4. Actions (invokable, single purpose)
[ ] 5. Listeners (handle events, call services/jobs)
[ ] 6. Policies (authorization)
[ ] 7. Services (business logic)
[ ] 8. Jobs (queueable work)
[ ] 9. Rules (custom validation)
[ ] 10. Validation (Form Requests)
[ ] 11. Livewire components (thin — mount, validate, call actions, dispatch)
[ ] 12. Blade views (layouts, pages, components)
[ ] 13. Routes (web.php or admin.php)
[ ] 14. Tests (Feature + Unit)
```

---

## Code Generation Rules

All files are created using `php artisan` commands — never manually:

```bash
# Livewire Components
php artisan make:livewire <Domain>\\<Name>

# Models with Migrations
php artisan make:model <Name> -m

# Actions (custom command — fallback: manual class)
php artisan make:action <Domain>\\<ActionName>

# Events & Listeners
php artisan make:event <Domain>\\<EventName>
php artisan make:listener <Domain>\\<ListenerName> --event=<Event>

# Jobs
php artisan make:job <Domain>\\<JobName>

# Policies
php artisan make:policy <Domain>\\<PolicyName> --model=<Model>

# Form Requests
php artisan make:request <Domain>\\<RequestName>

# Notifications
php artisan make:notification <NotificationName>

# Tests
php artisan make:test <Domain>\\<TestName>
```

---

## File Convention

| Type | Location | Naming |
|------|----------|--------|
| Domain classes | `app/Domains/<Domain>/` | PascalCase |
| Views | `resources/views/` | kebab-case |
| Livewire views | `resources/views/livewire/<domain>/` | kebab-case |
| Routes | `routes/` | snake_case |
| Config | `config/` | snake_case |
| Tests | `tests/Feature/<Domain>/` | PascalCase |

---

## Updating This File

After completing a feature:
1. Move its row in the Current State table from ⬜ to ✅
2. Update the date and notes at the top
3. Commit with message: `progress: complete [Feature Name]`
