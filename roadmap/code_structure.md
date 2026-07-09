# Code Structure

> **Code generation principle:** Every file is generated using `php artisan` commands. Files are never created manually or copied.
>
> **Implementation pattern:** Features are built one at a time in dependency order. See [progress_pattern.md](./progress_pattern.md).

---

## Directory Tree

```
app/
├── Domains/                              # Business domain modules
│   ├── Authentication/
│   │   ├── Actions/
│   │   │   ├── RegisterUser.php            ⬜
│   │   │   ├── AuthenticateSession.php     ⬜
│   │   │   └── SendPasswordResetLink.php   ⬜
│   │   ├── DTOs/
│   │   │   └── UserData.php                ⬜
│   │   ├── Events/
│   │   │   ├── UserRegistered.php          ⬜
│   │   │   ├── UserLoggedIn.php            ⬜
│   │   │   └── UserLoggedOut.php           ⬜
│   │   ├── Listeners/
│   │   │   └── SendWelcomeNotification.php ⬜
│   │   ├── Policies/
│   │   │   └── UserPolicy.php              ⬜
│   │   ├── Services/
│   │   │   └── AuthenticationService.php   ⬜
│   │   ├── Jobs/
│   │   │   └── SendWelcomeEmail.php        ⬜
│   │   ├── Rules/
│   │   │   └── StrongPassword.php          ⬜
│   │   ├── Validation/
│   │   │   └── LoginRequest.php            ⬜
│   │   └── Livewire/
│   │       ├── Login.php                   ✅
│   │       ├── Register.php                ⬜
│   │       ├── ForgotPassword.php          ⬜
│   │       └── ResetPassword.php           ⬜
│   │
│   ├── Business/                         # Business profiles domain
│   │   ├── Actions/
│   │   │   ├── CreateBusiness.php          ⬜
│   │   │   ├── UpdateBusiness.php          ⬜
│   │   │   ├── DeleteBusiness.php          ⬜
│   │   │   ├── UploadLogo.php              ⬜
│   │   │   └── UploadCover.php             ⬜
│   │   ├── DTOs/
│   │   │   └── BusinessData.php            ⬜
│   │   ├── Events/
│   │   │   ├── BusinessCreated.php         ⬜
│   │   │   ├── BusinessUpdated.php         ⬜
│   │   │   └── BusinessDeleted.php         ⬜
│   │   ├── Listeners/
│   │   │   └── InitializeBusinessSettings.php ⬜
│   │   ├── Policies/
│   │   │   └── BusinessPolicy.php          ⬜
│   │   ├── Jobs/
│   │   │   └── GenerateBusinessSitemap.php ⬜
│   │   ├── Livewire/
│   │   │   ├── CreateBusiness.php          ⬜
│   │   │   ├── EditBusiness.php            ⬜
│   │   │   ├── BusinessSettings.php        ⬜
│   │   │   └── PublicProfile.php           ⬜
│   │   └── Services/
│   │       └── BusinessService.php         ⬜
│   │
│   ├── Product/                          # Product Stores only
│   │   ├── Actions/
│   │   │   ├── CreateProduct.php           ✅
│   │   │   ├── UpdateProduct.php           ✅
│   │   │   ├── DeleteProduct.php           ✅
│   │   │   └── ToggleStatus.php            ✅
│   │   ├── DTOs/
│   │   │   └── ProductData.php             ✅
│   │   ├── Events/
│   │   │   ├── ProductCreated.php          ✅
│   │   │   ├── ProductUpdated.php          ✅
│   │   │   ├── ProductDeleted.php          ✅
│   │   │   └── ProductViewed.php           ✅
│   │   ├── Listeners/
│   │   │   └── UpdateProductSearchIndex.php ✅
│   │   ├── Policies/
│   │   │   └── ProductPolicy.php           ✅
│   │   ├── Livewire/
│   │   │   ├── ProductList.php             ⬜
│   │   │   ├── CreateProduct.php           ⬜
│   │   │   ├── EditProduct.php             ⬜
│   │   │   ├── ProductCard.php             ⬜
│   │   │   └── QuickEdit.php               ⬜
│   │   └── Services/
│   │       └── ProductService.php          ⬜
│   │
│   ├── Service/                          # Service Businesses only
│   │   ├── Actions/
│   │   │   ├── CreateService.php           ⬜
│   │   │   ├── UpdateService.php           ⬜
│   │   │   ├── DeleteService.php           ⬜
│   │   │   └── ToggleStatus.php            ⬜
│   │   ├── DTOs/
│   │   │   └── ServiceData.php             ⬜
│   │   ├── Events/
│   │   │   ├── ServiceCreated.php          ⬜
│   │   │   ├── ServiceUpdated.php          ⬜
│   │   │   ├── ServiceDeleted.php          ⬜
│   │   │   └── ServiceViewed.php           ⬜
│   │   ├── Listeners/
│   │   │   └── UpdateServiceSearchIndex.php ⬜
│   │   ├── Policies/
│   │   │   └── ServicePolicy.php           ⬜
│   │   ├── Livewire/
│   │   │   ├── ServiceList.php             ⬜
│   │   │   ├── CreateService.php           ⬜
│   │   │   ├── EditService.php             ⬜
│   │   │   └── ServiceCard.php             ⬜
│   │   └── Services/
│   │       └── ServiceService.php          ⬜
│   │
│   ├── Media/
│   │   ├── Actions/
│   │   │   ├── UploadMedia.php             ⬜
│   │   │   ├── DeleteMedia.php             ⬜
│   │   │   └── OrganizeMedia.php           ⬜
│   │   ├── DTOs/
│   │   │   └── MediaData.php               ⬜
│   │   ├── Events/
│   │   │   ├── MediaUploaded.php           ⬜
│   │   │   └── MediaDeleted.php            ⬜
│   │   ├── Policies/
│   │   │   └── MediaPolicy.php             ⬜
│   │   └── Livewire/
│   │       ├── MediaLibrary.php            ⬜
│   │       ├── UploadMedia.php             ⬜
│   │       ├── MediaPreview.php            ⬜
│   │       └── MediaPicker.php             ⬜
│   │
│   ├── Theme/
│   │   ├── Actions/
│   │   │   ├── ActivateTheme.php           ⬜
│   │   │   └── CustomizeTheme.php          ⬜
│   │   ├── DTOs/
│   │   │   └── ThemeData.php               ⬜
│   │   ├── Events/
│   │   │   ├── ThemeActivated.php          ⬜
│   │   │   └── ThemeCustomized.php         ⬜
│   │   ├── Policies/
│   │   │   └── ThemePolicy.php             ⬜
│   │   └── Livewire/
│   │       ├── ThemeList.php               ⬜
│   │       ├── ThemePreview.php            ⬜
│   │       ├── ThemeCustomizer.php         ⬜
│   │       └── ThemeRenderer.php           ⬜
│   │
│   ├── Search/
│   │   ├── Actions/
│   │   │   ├── SearchBusinesses.php        ⬜
│   │   │   └── FilterResults.php           ⬜
│   │   └── Livewire/
│   │       ├── ExploreSearch.php           ⬜
│   │       ├── ExploreResults.php          ⬜
│   │       ├── FilterSidebar.php           ⬜
│   │       ├── FilterDrawer.php            ⬜
│   │       └── QuickFilters.php            ⬜
│   │
│   ├── Analytics/
│   │   ├── Actions/
│   │   │   ├── RecordEvent.php             ⬜
│   │   │   └── GenerateReport.php          ⬜
│   │   ├── Events/
│   │   │   └── BusinessViewed.php          ⬜
│   │   ├── Jobs/
│   │   │   └── ProcessAnalytics.php        ⬜
│   │   └── Livewire/
│   │       ├── AnalyticsDashboard.php      ⬜
│   │       ├── VisitorsChart.php           ⬜
│   │       └── TopItems.php                ⬜
│   │
│   ├── Subscription/
│   │   ├── Actions/
│   │   │   ├── CreateSubscription.php      ⬜
│   │   │   ├── CancelSubscription.php      ⬜
│   │   │   ├── UpgradePlan.php             ⬜
│   │   │   └── DowngradePlan.php           ⬜
│   │   ├── DTOs/
│   │   │   └── SubscriptionData.php        ⬜
│   │   ├── Events/
│   │   │   ├── SubscriptionStarted.php     ⬜
│   │   │   ├── SubscriptionExpired.php     ⬜
│   │   │   ├── SubscriptionCancelled.php   ⬜
│   │   │   ├── PaymentReceived.php         ⬜
│   │   │   └── PaymentFailed.php           ⬜
│   │   ├── Jobs/
│   │   │   ├── HandlePaymentWebhook.php    ⬜
│   │   │   └── RetryFailedPayment.php      ⬜
│   │   ├── Policies/
│   │   │   └── SubscriptionPolicy.php      ⬜
│   │   └── Livewire/
│   │       ├── PricingPage.php             ⬜
│   │       ├── Checkout.php                ⬜
│   │       └── SubscriptionStatus.php      ⬜
│   │
│   ├── Payments/
│   │   ├── Actions/
│   │   │   ├── CreateInvoice.php           ⬜
│   │   │   ├── SendInvoice.php             ⬜
│   │   │   └── RecordPayment.php           ⬜
│   │   ├── DTOs/
│   │   │   └── PaymentData.php             ⬜
│   │   ├── Events/
│   │   │   ├── InvoiceCreated.php          ⬜
│   │   │   └── InvoicePaid.php             ⬜
│   │   └── Livewire/
│   │       ├── InvoiceList.php             ⬜
│   │       ├── CreateInvoice.php           ⬜
│   │       └── InvoiceDetail.php           ⬜
│   │
│   ├── Marketing/
│   │   ├── Actions/
│   │   │   ├── CreateLink.php              ⬜
│   │   │   ├── DeleteLink.php              ⬜
│   │   │   ├── TrackClick.php              ⬜
│   │   │   ├── GenerateQrCode.php          ⬜
│   │   │   └── DeleteQrCode.php            ⬜
│   │   ├── DTOs/
│   │   │   └── MarketingLinkData.php       ⬜
│   │   ├── Events/
│   │   │   ├── LinkClicked.php             ⬜
│   │   │   └── QrCodeGenerated.php         ⬜
│   │   ├── Jobs/
│   │   │   └── ProcessLinkClick.php        ⬜
│   │   └── Livewire/
│   │       ├── MarketingLinks.php          ⬜
│   │       ├── CreateLink.php              ⬜
│   │       ├── LinkStats.php               ⬜
│   │       ├── QrCodeGenerator.php         ⬜
│   │       └── QrCodeList.php              ⬜
│   │
│   └── Notifications/
│       ├── Actions/
│       │   ├── MarkAsRead.php              ⬜
│       │   └── UpdatePreferences.php       ⬜
│       ├── Livewire/
│       │   ├── NotificationBell.php        ⬜
│       │   ├── NotificationList.php        ⬜
│       │   └── NotificationPreferences.php ⬜
│       └── Notifications/
│           ├── ReviewReceived.php          ⬜
│           ├── SubscriptionExpiring.php    ⬜
│           └── VerificationApproved.php    ⬜
│
├── Http/
│   ├── Controllers/
│   │   ├── Controller.php                  ✅
│   │   └── Api/
│   │       ├── BusinessController.php      ⬜
│   │       ├── ProductController.php       ⬜
│   │       ├── ServiceController.php       ⬜
│   │       ├── SearchController.php        ⬜
│   │       └── AnalyticsController.php     ⬜
│   ├── Middleware/
│   │   ├── TenantScope.php                 ⬜
│   │   ├── SubscriptionMiddleware.php      ⬜
│   │   └── BusinessOwnership.php           ⬜
│   └── Requests/                           ⬜
│
├── Models/
│   ├── User.php                            ✅
│   ├── Business.php                        ✅
│   ├── Product.php                         ✅
│   ├── Service.php                         ✅
│   ├── Category.php                        ✅
│   ├── Media.php                           ✅
│   ├── Theme.php                           ✅
│   ├── Subscription.php                    ✅
│   ├── Plan.php                            ✅
│   ├── AnalyticsEvent.php                  ⬜
│   ├── Review.php                          ⬜ Phase 2
│   ├── ReviewReply.php                     ⬜ Ext
│   ├── SavedBusiness.php                   ⬜ Phase 2
│   ├── MarketingLink.php                   ⬜ Phase 2
│   ├── QrCode.php                          ⬜ Phase 2
│   ├── QrCodeScan.php                      ⬜ Ext
│   ├── Discount.php                        ⬜ Ext
│   ├── DiscountRedemption.php              ⬜ Ext
│   ├── PromoBanner.php                     ⬜ Ext
│   ├── LeadForm.php                        ⬜ Ext
│   ├── LeadFormField.php                   ⬜ Ext
│   ├── LeadSubmission.php                  ⬜ Ext
│   ├── LoyaltyProgram.php                  ⬜ Ext
│   ├── LoyaltyPointsTransaction.php        ⬜ Ext
│   ├── Referral.php                        ⬜ Ext
│   ├── Appointment.php                     ⬜ Phase 3
│   ├── AvailabilitySlot.php                ⬜ Phase 3
│   ├── BookingSchedule.php                 ⬜ Phase 3
│   ├── BookingBlock.php                    ⬜ Ext
│   ├── WhatsAppSetting.php                 ⬜ Ext
│   ├── WhatsAppTemplate.php                ⬜ Ext
│   ├── WhatsAppCatalogItem.php             ⬜ Ext
│   ├── Location.php                        ⬜ Ext
│   ├── Lead.php                            ⬜ Phase 3
│   ├── TeamMember.php                      ⬜ Phase 3
│   ├── CustomDomain.php                    ⬜ Phase 3
│   ├── Invoice.php                         ⬜ Phase 3
│   ├── Verification.php                    ⬜ Phase 2
│   ├── VerificationDocument.php            ⬜ Ext
│   ├── Testimonial.php                     ⬜ Ext
│   ├── SavedReport.php                     ⬜ Ext
│   ├── ScheduledReport.php                 ⬜ Ext
│   ├── TrackingPixel.php                   ⬜ Ext
│   └── Integration.php                     ⬜ Phase 4
│
└── Providers/
    ├── AppServiceProvider.php              ✅
    └── EventServiceProvider.php            ⬜

resources/
├── views/
│   ├── layouts/
│   │   ├── app.blade.php                   ✅
│   │   ├── dashboard.blade.php             ✅
│   │   └── admin.blade.php                 ⬜
│   ├── components/                         ⬜
│   ├── livewire/                           ⬜
│   └── pages/                              ⬜

routes/
├── web.php                                 ✅ (basic)
├── api.php                                 ⬜ Phase 4
└── admin.php                               ⬜

config/
├── livewire.php                            ⬜
├── theme.php                               ⬜
├── subscription.php                        ⬜
├── analytics.php                           ⬜
├── search.php                              ⬜
├── media.php                               ⬜
└── features.php                            ⬜

database/
├── migrations/
│   ├── 0001_01_01_000000_create_users_table.php          ✅
│   ├── 0001_01_01_000001_create_cache_table.php           ✅
│   ├── 0001_01_01_000002_create_jobs_table.php            ✅
│   ├── 2026_07_04_194149_create_businesses_table.php      ✅
│   ├── 2026_07_04_194150_create_categories_table.php      ✅
│   ├── 2026_07_04_194150_create_products_table.php        ✅
│   ├── 2026_07_04_194151_create_media_table.php           ✅
│   ├── 2026_07_04_194151_create_services_table.php        ✅
│   ├── 2026_07_04_194151_create_plans_table.php           ✅
│   ├── 2026_07_04_194151_create_subscriptions_table.php   ✅
│   ├── 2026_07_04_194151_create_themes_table.php          ✅
│   └── (business_theme pivot included in themes migration) ✅
└── seeders/
    ├── DatabaseSeeder.php                  ✅
    ├── PlanSeeder.php                      ✅
    ├── CategorySeeder.php                  ✅
    └── ThemeSeeder.php                     ✅

tests/
├── Feature/                                ⬜
│   ├── Authentication/                     ⬜
│   ├── Business/                           ⬜
│   ├── Product/                            ⬜
│   ├── Service/                            ⬜
│   ├── Theme/                              ⬜
│   ├── Search/                             ⬜
│   ├── Analytics/                          ⬜
│   ├── Subscription/                       ⬜
│   ├── Media/                              ⬜
│   └── Marketing/                          ⬜
├── Unit/                                   ⬜
│   ├── Actions/                            ⬜
│   ├── Services/                           ⬜
│   └── DTOs/                               ⬜
└── Pest.php                                ✅
```

---

## Legend

| Symbol | Meaning |
|--------|---------|
| ✅ | File exists and is implemented |
| ⬜ | Planned — not yet implemented |
| ⬜ Phase N | Planned for a later phase |

---

## Implementation Order

Features are built in dependency order, one at a time:

| # | Feature | Depends On | Status |
|---|---------|------------|--------|
| 1 | Authentication | — | ✅ Completed |
| 2 | Landing Page | — | ✅ Completed |
| 3 | Business Profiles | Authentication | ⬜ **Next** |
| 4 | Products | Business, Media | ✅ Partial (domain) |
| 5 | Services | Business, Media | ⬜ |
| 6 | Media Library | Business | ⬜ |
| 7 | Themes | Business | ⬜ |
| 8 | Explore & Search | Business, Product, Service | ⬜ |
| 9 | Analytics | Business | ⬜ |
| 10 | Subscriptions | Business, Payments | ⬜ |
| 11 | Payments | Business | ⬜ |
| 12 | Marketing | Business | ⬜ |
| 13 | Notifications | Authentication | ⬜ |
| 14 | Reviews & Ratings | Business | ⬜ Phase 2 |
| 15 | WhatsApp Catalog | Business, Product, Service | ⬜ Ext |
| 16 | Multi-Location | Business | ⬜ Ext |
| 17 | Discounts & Promos | Business, Marketing | ⬜ Ext |
| 18 | Lead Forms | Business | ⬜ Ext |
| 19 | Referral & Loyalty | Business | ⬜ Ext |
| 20 | Bulk CSV Import | Product | ⬜ Ext |
| 21 | Per-Item Analytics | Analytics | ⬜ Ext |
| 22 | Benchmarking | Analytics, Search | ⬜ Ext |
| 23 | Tracking Pixels (Post-MVP) | Analytics | ⬜ Future |
| 24 | Business Verification | Business, Admin | ⬜ Phase 2 |
| 25 | Testimonials | Business, Reviews | ⬜ Ext |

See [progress_pattern.md](./progress_pattern.md) for detailed tracking.

---

## Compulsory Conventions

### Docblocks Are Mandatory

Every class file **must** have a docblock that explains what it does and why. No exceptions.

| Element | What to document |
|---------|-----------------|
| **Class** | What this class does, when it fires / is used, what it returns |
| **Public methods** | What the method does, parameters, return value (if not obvious from signature) |
| **Properties** | What the property stores, especially nullable or complex types |
| **Constructor params** | Each constructor param gets an inline docblock if it's a DTO or configuration |

```php
/**
 * Registers a new user account.
 *
 * Creates the User model from the provided DTO, fires UserRegistered
 * so that downstream listeners (welcome email, default setup) can react,
 * and returns the fresh user instance.
 */
class RegisterUser
{
    public function __invoke(UserData $data): User
    {
        // ...
    }
}
```

### Inline Comments Are Mandatory

Use inline `//` comments to explain:
- **Why** something is done a certain way (not what — the code shows what)
- **Non-obvious** business rules or edge cases
- **TODOs** for future improvements or phase-gated features
- **Sections** in long views or methods

```php
// Business type is set at creation and cannot be changed.
// This determines whether the merchant dashboard shows Products or Services.
if ($business->isProductStore()) {
    // ...
}
```

```blade
{{-- Hero section — main CTA for new visitors. --}}
```

### View Files Get Header Comments

Every Blade view file **must** start with a `{{-- ... --}}` comment describing the view's purpose and what Livewire component it wraps (if any).

```blade
{{-- Login page — wraps the Auth\Login Livewire component. --}}
```

---

## Key Conventions

### Actions Are Invokable

```php
class CreateBusiness
{
    public function __invoke(CreateBusinessData $data): Business
    {
        //
    }
}
```

Used via dependency injection:
```php
$business = $createBusinessAction($data);
```

### Livewire Components Are Thin

Livewire components only handle:
- Mounting / initializing
- Validation
- Calling Actions
- Dispatching events to Alpine

No business logic lives in Livewire components.

### DTOs Are Immutable

```php
class BusinessData
{
    public function __construct(
        public readonly string $name,
        public readonly string $type,        // product_store | service_business
        public readonly string $slug,
        public readonly ?string $description,
        public readonly ?string $email,
        public readonly ?string $phone,
        public readonly ?string $address,
        public readonly ?string $city,
        public readonly ?string $state,
        public readonly ?string $country,
        public readonly ?string $website,
        public readonly ?int $categoryId,
    ) {}
}
```

### Events Are Self-Documenting

```php
class BusinessCreated
{
    public function __construct(
        public Business $business
    ) {}
}
```

### Models Use Global Scopes for Tenant Isolation

```php
class TenantScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $businessId = request()->route('business') ?? auth()->user()?->business_id;

        if ($businessId) {
            $builder->where('business_id', $businessId);
        }
    }
}
```

### Separate Dashboards by Business Type

Product stores and service businesses have **completely isolated dashboards**.
The dashboard view (`resources/views/pages/dashboard.blade.php`) checks `$business->type`
and renders entirely different content sections — no shared functionality.

```
Product Store Dashboard:       Service Business Dashboard:
┌─────────────────────────┐   ┌───────────────────────────┐
│ Products count           │   │ Services count            │
│ Product inventory        │   │ Service portfolio         │
│ Orders / sales           │   │ Appointment bookings      │
│ Product categories       │   │ Service categories        │
│ Discount management      │   │ Availability settings     │
└─────────────────────────┘   └───────────────────────────┘
```

The sidebar navigation also adapts — a product store never sees "Services" in the nav,
and a service business never sees "Products". This isolation extends to all future
domains (appointments only for service, inventory only for stores).

### Business Switcher (Multi-Business)

Users with more than one business see a **dropdown switcher** at the top of the
dashboard sidebar. The active business is stored in `session('current_business_id')`.

All dashboard operations are scoped to the session's current business.
Switching businesses changes the entire dashboard context (nav items, stats, content).

```php
// In dashboard layout — sets the active business context.
session(['current_business_id' => $business->id]);
```

### File Uploads Over URLs

File uploads always use **direct file input** (`<input type="file">`) via Livewire's
`WithFileUploads` trait. URL-based logo/banner fields do not exist.

```php
use Livewire\WithFileUploads;

class OnboardingWizard extends Component
{
    use WithFileUploads;

    public $logo; // Livewire\TemporaryUploadedFile instance

    public function complete(): void
    {
        $path = $this->logo->store('logos', 'public');
    }
}
```

Accepted formats: JPEG, PNG, WebP. Max file size: 2MB per upload.

---

## Artisan Command Reference

| Command | Purpose | Example |
|---------|---------|---------|
| `make:livewire` | Create Livewire component | `php artisan make:livewire Business\\CreateBusiness` |
| `make:model -m` | Create model + migration | `php artisan make:model Business -m` |
| `make:action` | Create invokable action | `php artisan make:action Business\\CreateBusiness` |
| `make:event` | Create event class | `php artisan make:event Business\\BusinessCreated` |
| `make:listener` | Create event listener | `php artisan make:listener Business\\InitializeBusinessSettings --event=Business\\BusinessCreated` |
| `make:job` | Create queue job | `php artisan make:job Analytics\\ProcessAnalytics` |
| `make:policy` | Create authorization policy | `php artisan make:policy Business\\BusinessPolicy --model=Business` |
| `make:request` | Create form request | `php artisan make:request Business\\StoreBusinessRequest` |
| `make:notification` | Create notification | `php artisan make:notification ReviewReceived` |
| `make:test` | Create test | `php artisan make:test Business\\CreateBusinessTest` |
| `make:seeder` | Create database seeder | `php artisan make:seeder PlanSeeder` |
| `make:middleware` | Create middleware | `php artisan make:middleware TenantScope` |
| `make:rule` | Create validation rule | `php artisan make:rule StrongPassword` |
| `make:provider` | Create service provider | `php artisan make:provider DomainServiceProvider` |

---

## Bootstrapping Order

```
 1. composer create-project laravel/laravel .       ✅
 2. composer require livewire/livewire                ✅
 3. php artisan make:model User -m                    ✅
 4. php artisan make:model Business -m                 ✅
 5. php artisan make:model Product -m                  ✅
 6. php artisan make:model Service -m                  ✅
 7. php artisan make:model Category -m                 ✅
 8. php artisan make:model Media -m                    ✅
 9. php artisan make:model Theme -m                    ✅
10. php artisan make:model Subscription -m             ✅
11. php artisan make:model Plan -m                     ✅
12. php artisan make:seeder PlanSeeder                 ✅
13. php artisan make:seeder ThemeSeeder                ✅
14. php artisan make:seeder CategorySeeder             ✅
15. php artisan migrate --seed                         ✅
16. php artisan make:livewire Auth\Login               ✅
17. php artisan make:action Product\CreateProduct       ✅
18. ...
```
