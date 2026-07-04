# Phase 1 — MVP

> **Goal:** Get merchants online with a professional business profile in under 5 minutes.  
> **Duration:** 8–12 weeks  
> **Livewire Components:** ~30  
> **Database Tables:** ~15  
> **Artisan Commands Used:** `make:livewire`, `make:model -m`, `make:action`, `make:event`, `make:listener`, `make:policy`, `make:request`, `make:job`, `make:notification`, `make:test`

---

## Business Types

A business is one of two types:

| Type | Description | Example |
|------|-------------|---------|
| `product_store` | Sells physical or digital products | Fashion store, grocery, electronics shop |
| `service_business` | Offers services | Salon, clinic, plumber, consultant |

The business type is set at creation and determines:
- Which domain the merchant uses (Product or Service)
- How the public profile renders ("Products" vs "Services" section)
- Which dashboard navigation items appear
- Available features (Booking only for service businesses)

---

## Features

### 1. Authentication

Login, registration, email verification, password reset, session management.

**Livewire Components:**
| Component | Description | Command |
|-----------|-------------|---------|
| `Auth\Login` | Login form with validation | `php artisan make:livewire Auth\\Login` |
| `Auth\Register` | Registration form | `php artisan make:livewire Auth\\Register` |
| `Auth\ForgotPassword` | Password reset request | `php artisan make:livewire Auth\\ForgotPassword` |
| `Auth\ResetPassword` | Password reset form | `php artisan make:livewire Auth\\ResetPassword` |
| `Auth\VerifyEmail` | Email verification notice | `php artisan make:livewire Auth\\VerifyEmail` |

**Models:** `User` (with `--migration`)

**Actions:**
- `Auth\RegisterUser` — handles registration logic, fires `UserRegistered`
- `Auth\AuthenticateSession` — handles login
- `Auth\SendPasswordResetLink`
- `Auth\ResetUserPassword`

**Events:** `UserRegistered`, `UserLoggedIn`, `UserLoggedOut`, `PasswordReset`

**Policies:** `UserPolicy`

---

### 2. Business Profiles

Merchants create and manage their business. Type is chosen during onboarding.

**Livewire Components:**
| Component | Description | Command |
|-----------|-------------|---------|
| `Business\CreateBusiness` | Onboarding wizard (includes type selection) | `php artisan make:livewire Business\\CreateBusiness` |
| `Business\EditBusiness` | Edit business details | `php artisan make:livewire Business\\EditBusiness` |
| `Business\BusinessSettings` | Full settings page | `php artisan make:livewire Business\\BusinessSettings` |
| `Business\PublicProfile` | Public-facing business page | `php artisan make:livewire Business\\PublicProfile` |

**Models:** `Business` (with `--migration`)

**Actions:**
- `Business\CreateBusiness`
- `Business\UpdateBusiness`
- `Business\DeleteBusiness`
- `Business\UploadLogo`
- `Business\UploadCover`

**Events:** `BusinessCreated`, `BusinessUpdated`, `BusinessDeleted`, `BusinessViewed`

**Policies:** `BusinessPolicy` — owner can edit, everyone can view

---

### 3. Products (Product Stores only)

Merchants with `product_store` type add products with images, descriptions, pricing, and categories.

**Livewire Components:**
| Component | Description | Command |
|-----------|-------------|---------|
| `Product\ProductList` | Product table/grid | `php artisan make:livewire Product\\ProductList` |
| `Product\CreateProduct` | Create product form | `php artisan make:livewire Product\\CreateProduct` |
| `Product\EditProduct` | Edit product form | `php artisan make:livewire Product\\EditProduct` |
| `Product\ProductCard` | Reusable product card | `php artisan make:livewire Product\\ProductCard` |
| `Product\QuickEdit` | Inline quick edit | `php artisan make:livewire Product\\QuickEdit` |

**Models:** `Product`, `Category` (both with `--migration`)

**Actions:**
- `Product\CreateProduct`
- `Product\UpdateProduct`
- `Product\DeleteProduct`
- `Product\ToggleStatus`
- `Category\CreateCategory`
- `Category\UpdateCategory`
- `Category\DeleteCategory`

**Events:** `ProductCreated`, `ProductUpdated`, `ProductDeleted`, `ProductViewed`

**Policies:** `ProductPolicy` — scoped to business owner

---

### 4. Services (Service Businesses only)

Merchants with `service_business` type add services with duration, pricing, and availability.

**Livewire Components:**
| Component | Description | Command |
|-----------|-------------|---------|
| `Service\ServiceList` | Services list | `php artisan make:livewire Service\\ServiceList` |
| `Service\CreateService` | Create service form | `php artisan make:livewire Service\\CreateService` |
| `Service\EditService` | Edit service form | `php artisan make:livewire Service\\EditService` |
| `Service\ServiceCard` | Reusable service card | `php artisan make:livewire Service\\ServiceCard` |

**Models:** `Service`, `Category` (both with `--migration`)

**Actions:**
- `Service\CreateService`
- `Service\UpdateService`
- `Service\DeleteService`
- `Service\ToggleStatus`

**Events:** `ServiceCreated`, `ServiceUpdated`, `ServiceDeleted`, `ServiceViewed`

**Policies:** `ServicePolicy` — scoped to business owner

---

### 5. Media Library

Upload, organize, preview, and reuse images across products, services, and business profile.

**Livewire Components:**
| Component | Description | Command |
|-----------|-------------|---------|
| `Media\MediaLibrary` | Grid view with upload | `php artisan make:livewire Media\\MediaLibrary` |
| `Media\UploadMedia` | Drag and drop upload | `php artisan make:livewire Media\\UploadMedia` |
| `Media\MediaPreview` | Full preview modal | `php artisan make:livewire Media\\MediaPreview` |
| `Media\MediaPicker` | Reusable picker for forms | `php artisan make:livewire Media\\MediaPicker` |

**Models:** `Media` (with `--migration`)

**Actions:**
- `Media\UploadMedia` — handles file validation, optimization, storage
- `Media\DeleteMedia`
- `Media\OrganizeMedia`

**Events:** `MediaUploaded`, `MediaDeleted`

**Policies:** `MediaPolicy`

---

### 6. Themes

Merchants select and customize their public page appearance — colors, typography, layout.

**Livewire Components:**
| Component | Description | Command |
|-----------|-------------|---------|
| `Theme\ThemeList` | Available themes grid | `php artisan make:livewire Theme\\ThemeList` |
| `Theme\ThemePreview` | Live preview in modal | `php artisan make:livewire Theme\\ThemePreview` |
| `Theme\ThemeCustomizer` | Color, font, radius controls | `php artisan make:livewire Theme\\ThemeCustomizer` |
| `Theme\ThemeRenderer` | Renders active theme on public page | `php artisan make:livewire Theme\\ThemeRenderer` |

**Models:** `Theme` (with `--migration`), `BusinessTheme` pivot

**Actions:**
- `Theme\ActivateTheme`
- `Theme\CustomizeTheme`

**Events:** `ThemeActivated`, `ThemeCustomized`, `ThemeChanged`

**Policies:** `ThemePolicy`

---

### 7. Explore & Search

Customers discover businesses through search, categories, and filters. Results show business type badge (Store / Service).

**Livewire Components:**
| Component | Description | Command |
|-----------|-------------|---------|
| `Explore\ExploreSearch` | Search bar with autocomplete | `php artisan make:livewire Explore\\ExploreSearch` |
| `Explore\ExploreResults` | Search results grid | `php artisan make:livewire Explore\\ExploreResults` |
| `Explore\FilterSidebar` | Category and filter sidebar | `php artisan make:livewire Explore\\FilterSidebar` |
| `Explore\FilterDrawer` | Mobile filter drawer | `php artisan make:livewire Explore\\FilterDrawer` |
| `Explore\QuickFilters` | Verified, premium, nearby badges | `php artisan make:livewire Explore\\QuickFilters` |

**Actions:**
- `Search\SearchBusinesses`
- `Search\FilterResults`

**Events:** `SearchPerformed`, `BusinessListed`

---

### 8. Analytics (Basic)

Track page views, visitor counts, WhatsApp/phone clicks. Product stores see product-specific analytics; service businesses see service-specific analytics.

**Livewire Components:**
| Component | Description | Command |
|-----------|-------------|---------|
| `Analytics\AnalyticsDashboard` | Overview cards + chart | `php artisan make:livewire Analytics\\AnalyticsDashboard` |
| `Analytics\VisitorsChart` | Visitors over time | `php artisan make:livewire Analytics\\VisitorsChart` |
| `Analytics\TopItems` | Most viewed products or services | `php artisan make:livewire Analytics\\TopItems` |

**Models:** `AnalyticsEvent` (with `--migration`)

**Actions:**
- `Analytics\RecordEvent`
- `Analytics\GenerateReport`

**Events:** `BusinessViewed`, `ProductViewed`, `ServiceViewed`, `WhatsAppClicked`, `PhoneClicked`

**Jobs:** `ProcessAnalytics` — queues analytics writes to avoid slowing requests

---

### 9. WhatsApp Integration

One-click WhatsApp chat buttons on public business pages.

**Actions:**
- `Business\GenerateWhatsAppLink`

No separate component — rendered as a button in `Business\PublicProfile`.

---

### 10. Subscriptions (Freemium)

Free plan always available. Professional and Business plans via payment.

**Livewire Components:**
| Component | Description | Command |
|-----------|-------------|---------|
| `Subscription\PricingPage` | Plan comparison | `php artisan make:livewire Subscription\\PricingPage` |
| `Subscription\Checkout` | Payment form | `php artisan make:livewire Subscription\\Checkout` |
| `Subscription\SubscriptionStatus` | Current plan badge | `php artisan make:livewire Subscription\\SubscriptionStatus` |

**Models:** `Subscription`, `Plan` (with `--migration`)

**Actions:**
- `Subscription\CreateSubscription`
- `Subscription\CancelSubscription`
- `Subscription\UpgradePlan`
- `Subscription\DowngradePlan`

**Events:** `SubscriptionStarted`, `SubscriptionExpired`, `SubscriptionCancelled`, `PaymentReceived`, `PaymentFailed`

**Jobs:** `HandlePaymentWebhook` — processes Paystack/Flutterwave webhooks
**Jobs:** `RetryFailedPayment` — retries failed subscription payments

**Policies:** `SubscriptionPolicy`

---

### 11. Landing Website

Marketing site with hero, featured businesses, categories, pricing, FAQ.

**Livewire Components:**
| Component | Description | Command |
|-----------|-------------|---------|
| `Landing\Hero` | Hero section | `php artisan make:livewire Landing\\Hero` |
| `Landing\FeaturedBusinesses` | Featured grid | `php artisan make:livewire Landing\\FeaturedBusinesses` |
| `Landing\PopularCategories` | Category cards | `php artisan make:livewire Landing\\PopularCategories` |
| `Landing\PricingSection` | Pricing table | `php artisan make:livewire Landing\\PricingSection` |
| `Landing\Testimonials` | Testimonial carousel | `php artisan make:livewire Landing\\Testimonials` |
| `Landing\FaqSection` | Accordion FAQ | `php artisan make:livewire Landing\\FaqSection` |
| `Landing\NewsletterSignup` | Email signup form | `php artisan make:livewire Landing\\NewsletterSignup` |

---

## Database Tables (Phase 1)

| Table | Purpose |
|-------|---------|
| `users` | User accounts |
| `businesses` | Business profiles (includes `type`: `product_store` or `service_business`) |
| `categories` | Business/product/service categories |
| `products` | Merchant products (product_store only) |
| `services` | Merchant services (service_business only) |
| `media` | Uploaded files |
| `themes` | Available themes |
| `business_theme` | Pivot: business active theme + customization |
| `subscriptions` | Merchant subscriptions |
| `plans` | Subscription plans |
| `analytics_events` | All analytics events |
| `sessions` | User sessions |
| `password_reset_tokens` | Password resets |
| `personal_access_tokens` | API tokens (Sanctum) |
| `failed_jobs` | Failed queue jobs |
| `job_batches` | Job batch tracking |
| `notifications` | Database notifications |

---

## Queue Jobs (Phase 1)

| Job | Queue | Purpose |
|-----|-------|---------|
| `ProcessAnalytics` | `analytics` | Batch-write analytics events |
| `HandlePaymentWebhook` | `payments` | Process payment gateway callbacks |
| `RetryFailedPayment` | `payments` | Retry failed subscription charges |
| `GenerateSitemap` | `default` | Generate SEO sitemap |
| `SendWelcomeEmail` | `emails` | Welcome email after registration |
| `SendSubscriptionReceipt` | `emails` | Payment receipt email |

---

## Routes (Phase 1)

### Web (Public)
| Path | Component | Description |
|------|-----------|-------------|
| `/` | `Landing\Hero` | Landing page |
| `/explore` | `Explore\ExploreResults` | Marketplace browse |
| `/business/{business}` | `Business\PublicProfile` | Public business page |
| `/login` | `Auth\Login` | Login |
| `/register` | `Auth\Register` | Register |
| `/forgot-password` | `Auth\ForgotPassword` | Password reset |
| `/pricing` | `Subscription\PricingPage` | Plans |

### Web (Merchant — `/dashboard`)
| Path | Component | Description |
|------|-----------|-------------|
| `/dashboard` | `Analytics\AnalyticsDashboard` | Dashboard home |
| `/dashboard/business` | `Business\BusinessSettings` | Edit business |
| `/dashboard/products` | `Product\ProductList` | Manage products (store only) |
| `/dashboard/products/create` | `Product\CreateProduct` | Add product (store only) |
| `/dashboard/products/{product}/edit` | `Product\EditProduct` | Edit product (store only) |
| `/dashboard/services` | `Service\ServiceList` | Manage services (service only) |
| `/dashboard/services/create` | `Service\CreateService` | Add service (service only) |
| `/dashboard/services/{service}/edit` | `Service\EditService` | Edit service (service only) |
| `/dashboard/media` | `Media\MediaLibrary` | Media library |
| `/dashboard/analytics` | `Analytics\AnalyticsDashboard` | Detailed analytics |
| `/dashboard/appearance` | `Theme\ThemeList` | Theme management |
| `/dashboard/subscription` | `Subscription\SubscriptionStatus` | Plan & billing |

---

## Testing Strategy

| Test Type | Framework | Coverage |
|-----------|-----------|----------|
| Feature Tests | Pest | All Livewire component interactions |
| Unit Tests | Pest | All Actions, Services, DTOs |
| Policy Tests | Pest | All authorization rules |
| Event Tests | Pest | Events fire and listeners attach |

**Command:** `php artisan make:test` for each domain module.
