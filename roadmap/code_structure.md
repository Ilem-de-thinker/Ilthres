# Code Structure

> **Code generation principle:** Every file is generated using `php artisan` commands. Files are never created manually or copied.

---

## Directory Tree

```
app/
├── Domains/                              # Business domain modules
│   ├── Authentication/
│   │   ├── Actions/
│   │   │   ├── RegisterUser.php
│   │   │   ├── AuthenticateSession.php
│   │   │   └── SendPasswordResetLink.php
│   │   ├── DTOs/
│   │   │   └── UserData.php
│   │   ├── Events/
│   │   │   ├── UserRegistered.php
│   │   │   ├── UserLoggedIn.php
│   │   │   └── UserLoggedOut.php
│   │   ├── Listeners/
│   │   │   └── SendWelcomeNotification.php
│   │   ├── Policies/
│   │   │   └── UserPolicy.php
│   │   ├── Services/
│   │   │   └── AuthenticationService.php
│   │   ├── Jobs/
│   │   │   └── SendWelcomeEmail.php
│   │   ├── Rules/
│   │   │   └── StrongPassword.php
│   │   ├── Validation/
│   │   │   └── LoginRequest.php
│   │   └── Livewire/
│   │       ├── Login.php
│   │       ├── Register.php
│   │       ├── ForgotPassword.php
│   │       └── ResetPassword.php
│   │
│   ├── Business/                         # Business profiles domain
│   │   ├── Actions/
│   │   │   ├── CreateBusiness.php
│   │   │   ├── UpdateBusiness.php
│   │   │   ├── DeleteBusiness.php
│   │   │   ├── UploadLogo.php
│   │   │   └── UploadCover.php
│   │   ├── DTOs/
│   │   │   └── BusinessData.php
│   │   ├── Events/
│   │   │   ├── BusinessCreated.php
│   │   │   ├── BusinessUpdated.php
│   │   │   └── BusinessDeleted.php
│   │   ├── Listeners/
│   │   │   └── InitializeBusinessSettings.php
│   │   ├── Policies/
│   │   │   └── BusinessPolicy.php
│   │   ├── Jobs/
│   │   │   └── GenerateBusinessSitemap.php
│   │   ├── Livewire/
│   │   │   ├── CreateBusiness.php        # Includes type selection (product_store or service_business)
│   │   │   ├── EditBusiness.php
│   │   │   ├── BusinessSettings.php
│   │   │   └── PublicProfile.php         # Adapts based on business type
│   │   └── Services/
│   │       └── BusinessService.php
│   │
│   ├── Product/                          # Product Stores only (type: product_store)
│   │   ├── Actions/
│   │   │   ├── CreateProduct.php
│   │   │   ├── UpdateProduct.php
│   │   │   ├── DeleteProduct.php
│   │   │   └── ToggleStatus.php
│   │   ├── DTOs/
│   │   │   └── ProductData.php
│   │   ├── Events/
│   │   │   ├── ProductCreated.php
│   │   │   ├── ProductUpdated.php
│   │   │   ├── ProductDeleted.php
│   │   │   └── ProductViewed.php
│   │   ├── Listeners/
│   │   │   └── UpdateProductSearchIndex.php
│   │   ├── Policies/
│   │   │   └── ProductPolicy.php
│   │   ├── Livewire/
│   │   │   ├── ProductList.php
│   │   │   ├── CreateProduct.php
│   │   │   ├── EditProduct.php
│   │   │   ├── ProductCard.php
│   │   │   └── QuickEdit.php
│   │   └── Services/
│   │       └── ProductService.php
│   │
│   ├── Service/                          # Service Businesses only (type: service_business)
│   │   ├── Actions/
│   │   │   ├── CreateService.php
│   │   │   ├── UpdateService.php
│   │   │   ├── DeleteService.php
│   │   │   └── ToggleStatus.php
│   │   ├── DTOs/
│   │   │   └── ServiceData.php
│   │   ├── Events/
│   │   │   ├── ServiceCreated.php
│   │   │   ├── ServiceUpdated.php
│   │   │   ├── ServiceDeleted.php
│   │   │   └── ServiceViewed.php
│   │   ├── Listeners/
│   │   │   └── UpdateServiceSearchIndex.php
│   │   ├── Policies/
│   │   │   └── ServicePolicy.php
│   │   ├── Livewire/
│   │   │   ├── ServiceList.php
│   │   │   ├── CreateService.php
│   │   │   ├── EditService.php
│   │   │   └── ServiceCard.php
│   │   └── Services/
│   │       └── ServiceService.php
│   │
│   ├── Media/
│   │   ├── Actions/
│   │   │   ├── UploadMedia.php
│   │   │   ├── DeleteMedia.php
│   │   │   └── OrganizeMedia.php
│   │   ├── DTOs/
│   │   │   └── MediaData.php
│   │   ├── Events/
│   │   │   ├── MediaUploaded.php
│   │   │   └── MediaDeleted.php
│   │   ├── Policies/
│   │   │   └── MediaPolicy.php
│   │   └── Livewire/
│   │       ├── MediaLibrary.php
│   │       ├── UploadMedia.php
│   │       ├── MediaPreview.php
│   │       └── MediaPicker.php
│   │
│   ├── Theme/
│   │   ├── Actions/
│   │   │   ├── ActivateTheme.php
│   │   │   └── CustomizeTheme.php
│   │   ├── DTOs/
│   │   │   └── ThemeData.php
│   │   ├── Events/
│   │   │   ├── ThemeActivated.php
│   │   │   └── ThemeCustomized.php
│   │   ├── Policies/
│   │   │   └── ThemePolicy.php
│   │   └── Livewire/
│   │       ├── ThemeList.php
│   │       ├── ThemePreview.php
│   │       ├── ThemeCustomizer.php
│   │       └── ThemeRenderer.php
│   │
│   ├── Search/
│   │   ├── Actions/
│   │   │   ├── SearchBusinesses.php
│   │   │   └── FilterResults.php
│   │   └── Livewire/
│   │       ├── ExploreSearch.php
│   │       ├── ExploreResults.php
│   │       ├── FilterSidebar.php
│   │       ├── FilterDrawer.php
│   │       └── QuickFilters.php
│   │
│   ├── Analytics/
│   │   ├── Actions/
│   │   │   ├── RecordEvent.php
│   │   │   └── GenerateReport.php
│   │   ├── Events/
│   │   │   └── BusinessViewed.php
│   │   ├── Jobs/
│   │   │   └── ProcessAnalytics.php
│   │   └── Livewire/
│   │       ├── AnalyticsDashboard.php
│   │       ├── VisitorsChart.php
│   │       └── TopItems.php
│   │
│   ├── Subscription/
│   │   ├── Actions/
│   │   │   ├── CreateSubscription.php
│   │   │   ├── CancelSubscription.php
│   │   │   ├── UpgradePlan.php
│   │   │   └── DowngradePlan.php
│   │   ├── DTOs/
│   │   │   └── SubscriptionData.php
│   │   ├── Events/
│   │   │   ├── SubscriptionStarted.php
│   │   │   ├── SubscriptionExpired.php
│   │   │   ├── SubscriptionCancelled.php
│   │   │   ├── PaymentReceived.php
│   │   │   └── PaymentFailed.php
│   │   ├── Jobs/
│   │   │   ├── HandlePaymentWebhook.php
│   │   │   └── RetryFailedPayment.php
│   │   ├── Policies/
│   │   │   └── SubscriptionPolicy.php
│   │   └── Livewire/
│   │       ├── PricingPage.php
│   │       ├── Checkout.php
│   │       └── SubscriptionStatus.php
│   │
│   ├── Payments/
│   │   ├── Actions/
│   │   │   ├── CreateInvoice.php
│   │   │   ├── SendInvoice.php
│   │   │   └── RecordPayment.php
│   │   ├── DTOs/
│   │   │   └── PaymentData.php
│   │   ├── Events/
│   │   │   ├── InvoiceCreated.php
│   │   │   └── InvoicePaid.php
│   │   └── Livewire/
│   │       ├── InvoiceList.php
│   │       ├── CreateInvoice.php
│   │       └── InvoiceDetail.php
│   │
│   ├── Marketing/
│   │   ├── Actions/
│   │   │   ├── CreateLink.php
│   │   │   ├── DeleteLink.php
│   │   │   ├── TrackClick.php
│   │   │   ├── GenerateQrCode.php
│   │   │   └── DeleteQrCode.php
│   │   ├── DTOs/
│   │   │   └── MarketingLinkData.php
│   │   ├── Events/
│   │   │   ├── LinkClicked.php
│   │   │   └── QrCodeGenerated.php
│   │   ├── Jobs/
│   │   │   └── ProcessLinkClick.php
│   │   └── Livewire/
│   │       ├── MarketingLinks.php
│   │       ├── CreateLink.php
│   │       ├── LinkStats.php
│   │       ├── QrCodeGenerator.php
│   │       └── QrCodeList.php
│   │
│   └── Notifications/
│       ├── Actions/
│       │   ├── MarkAsRead.php
│       │   └── UpdatePreferences.php
│       ├── Livewire/
│       │   ├── NotificationBell.php
│       │   ├── NotificationList.php
│       │   └── NotificationPreferences.php
│       └── Notification Classes
│           ├── ReviewReceived.php
│           ├── SubscriptionExpiring.php
│           └── VerificationApproved.php
│
├── Http/
│   ├── Controllers/
│   │   └── Api/
│   │       ├── BusinessController.php
│   │       ├── ProductController.php
│   │       ├── ServiceController.php
│   │       ├── SearchController.php
│   │       └── AnalyticsController.php
│   ├── Middleware/
│   │   ├── TenantScope.php
│   │   ├── SubscriptionMiddleware.php
│   │   └── BusinessOwnership.php
│   └── Requests/
│       └── ...
│
├── Models/
│   ├── User.php
│   ├── Business.php                      # Has type enum: product_store | service_business
│   ├── Product.php                       # Only for product_store businesses
│   ├── Service.php                       # Only for service_business businesses
│   ├── Category.php
│   ├── Media.php
│   ├── Theme.php
│   ├── Subscription.php
│   ├── Plan.php
│   ├── AnalyticsEvent.php
│   ├── Review.php                        # Phase 2
│   ├── SavedBusiness.php                 # Phase 2
│   ├── MarketingLink.php                 # Phase 2
│   ├── QrCode.php                        # Phase 2
│   ├── Appointment.php                   # Phase 3 (service businesses only)
│   ├── Lead.php                          # Phase 3
│   ├── TeamMember.php                    # Phase 3
│   ├── CustomDomain.php                  # Phase 3
│   ├── Invoice.php                       # Phase 3
│   └── Integration.php                   # Phase 4
│
└── Providers/
    ├── AppServiceProvider.php
    └── EventServiceProvider.php

resources/
├── views/
│   ├── layouts/
│   │   ├── app.blade.php
│   │   ├── dashboard.blade.php
│   │   └── admin.blade.php
│   ├── components/
│   │   ├── header.blade.php
│   │   ├── footer.blade.php
│   │   └── navigation.blade.php
│   └── livewire/
│       └── ...

routes/
├── web.php
├── api.php                               # Phase 4
└── admin.php

config/
├── livewire.php
├── theme.php
├── subscription.php
├── analytics.php
├── search.php
├── media.php
└── features.php

database/
├── migrations/
└── seeders/
    ├── PlanSeeder.php
    ├── ThemeSeeder.php
    └── CategorySeeder.php

tests/
├── Feature/
│   ├── Authentication/
│   ├── Business/
│   ├── Product/
│   ├── Service/
│   ├── Theme/
│   ├── Search/
│   ├── Analytics/
│   ├── Subscription/
│   ├── Media/
│   └── Marketing/
├── Unit/
│   ├── Actions/
│   ├── Services/
│   └── DTOs/
└── Pest.php
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
1. composer create-project laravel/laravel .
2. composer require livewire/livewire
3. php artisan make:middleware TenantScope
4. php artisan make:provider DomainServiceProvider
5. php artisan make:seeder PlanSeeder
6. php artisan make:seeder ThemeSeeder
7. php artisan make:seeder CategorySeeder
8. php artisan migrate --seed
9. php artisan make:livewire Auth\Login
10. php artisan make:livewire Auth\Register
11. php artisan make:model Business -m
12. php artisan make:action Business\CreateBusiness
13. php artisan make:event Business\BusinessCreated
14. php artisan make:listener Business\InitializeBusinessSettings --event=Business\BusinessCreated
15. php artisan make:policy Business\BusinessPolicy --model=Business
...
```
