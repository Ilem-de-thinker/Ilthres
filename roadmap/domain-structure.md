# Domain Structure Reference

> Every feature belongs to a domain. Domains are self-contained modules that communicate only through events.

---

## Business Types

A business is either a **Product Store** (`product_store`) or a **Service Business** (`service_business`). This is set at creation and determines which domains are active for that merchant.

| Type | Active Domains | Inactive Domains |
|------|----------------|------------------|
| `product_store` | Business, Product, Media, Theme, Search, Analytics, Subscription | Service, Appointment |
| `service_business` | Business, Service, Media, Theme, Search, Analytics, Subscription | Product |

The dashboard nav and public profile adapt accordingly — a product store never sees "Services" and a service business never sees "Products".

---

## Domain Directory Contents

Each domain directory follows this structure:

```
DomainName/
├── Actions/          # Single-purpose invokable classes
├── DTOs/             # Immutable data transfer objects
├── Events/           # Event classes (past tense naming)
├── Listeners/        # Event listeners
├── Policies/         # Laravel authorization policies
├── Services/         # Business logic services
├── Jobs/             # Queueable jobs
├── Rules/            # Custom validation rules
├── Validation/       # Form request classes
└── Livewire/         # Livewire components
```

---

## Domain Map

| Domain | Purpose | Phase | Business Type | Dependencies |
|--------|---------|-------|---------------|-------------|
| `Authentication` | User login, registration, password management | 1 | Both | None |
| `Business` | Business profiles, settings, public page | 1 | Both | Authentication |
| `Product` | Product CRUD, categories, pricing | 1 | `product_store` only | Business, Media |
| `Service` | Service CRUD, duration, availability | 1 | `service_business` only | Business, Media |
| `Media` | File upload, storage, optimization, reuse | 1 | Both | Business |
| `Theme` | Theme selection, customization, rendering | 1 | Both | Business |
| `Search` | Explore page, filters, search queries | 1 | Both | Business, Product, Service |
| `Analytics` | Event tracking, reports, charts, benchmarks (pixels: post-MVP) | 1, Ext | Both | Business |
| `Subscription` | Plans, billing, payment processing | 1 | Both | Business, Payments |
| `Payments` | Payment gateway integration, invoices | 1, 3 | Both | Business |
| `Marketing` | Marketing links, QR codes, promos, lead forms | 2 | Both | Business, Analytics |
| `Notifications` | In-app, email, push notifications | 2 | Both | Business |
| `Reviews` | Customer reviews, ratings, merchant replies | 2 | Both | Business |
| `WhatsApp` | WhatsApp catalog sync, auto-reply templates | Ext | Both | Business, Product, Service |
| `Location` | Multi-location branch management | Ext | Both | Business |
| `Loyalty` | Referral programs, loyalty points | Ext | Both | Business |
| `Appointment` | Booking system, availability, calendar | 3 | `service_business` only | Business, Service |
| `Verification` | Business verification, KYC, document upload | 2, Ext | Both | Business |
| `Testimonial` | Curated customer testimonials on profiles | Ext | Both | Business, Reviews |
| `Administration` | Admin panel, moderation, system config | 1 | Both | All (read-only) |

---

## Cross-Domain Communication

Domains communicate exclusively through **Events**. No direct method calls between domains.

```
Product Store:
  Business Domain → Product Domain (via BusinessCreated with type=product_store)
  Product Domain → Search Domain (via ProductCreated → index product)

Service Business:
  Business Domain → Service Domain (via BusinessCreated with type=service_business)
  Service Domain → Search Domain (via ServiceCreated → index service)

Both Types:
  Business Domain → Analytics Domain (via BusinessViewed → track visit)
  Business Domain → Subscription Domain (via SubscriptionStarted → enable features)
```

---

## Type-Based Routing

The merchant dashboard checks business type to show/hide nav items:

```php
// In dashboard layout
@if ($business->type === 'product_store')
    <a href="/dashboard/products">Products</a>
@elseif ($business->type === 'service_business')
    <a href="/dashboard/services">Services</a>
@endif
```

Public profile checks type to render the correct section:

```php
// In PublicProfile Livewire component
public function render()
{
    return view('livewire.business.public-profile', [
        'business' => $this->business,
        'items' => $this->business->type === 'product_store'
            ? $this->business->products()->active()->get()
            : $this->business->services()->active()->get(),
    ]);
}
```

---

## Domain Service Providers

```php
class BusinessDomainProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(CreateBusinessAction::class, function ($app) {
            return new CreateBusinessAction(
                $app->make(BusinessService::class)
            );
        });
    }

    public function boot(): void
    {
        Event::listen(
            BusinessCreated::class,
            InitializeBusinessSettings::class,
        );
    }
}
```
