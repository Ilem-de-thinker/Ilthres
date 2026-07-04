# Data Ownership Model

> Ilthres Market uses a **Business-centric** data model. The Business is the primary entity — not the User.

---

## Core Principle

```
┌─────────────────────────────────────────────┐
│                   User                       │
│  Owns: Account, Email, Password, Sessions    │
│  Manages: One or more Businesses             │
└────────────┬────────────────────────────────┘
             │ has / manages
    ┌────────┴────────┐
    ▼                 ▼
┌─────────────┐ ┌─────────────┐
│  Business 1 │ │  Business 2 │  (future: multi-business)
│  type: ...  │ │  type: ...  │
└─────────────┘ └─────────────┘
```

---

## What a Business Owns

### All Businesses (regardless of type)
- Analytics Events
- Media
- Theme + Customization
- SEO Settings
- Subscription
- Reviews (received)
- Marketing Links
- QR Codes

### Product Stores only (`type: product_store`)
- Products
- Categories (assigned to products)

### Service Businesses only (`type: service_business`)
- Services
- Appointments (Phase 3)
- Availability Slots (Phase 3)

---

## Why Business-Centric?

| Concept | Why Business Owns It |
|---------|---------------------|
| Products | A user manages products *for* a store. If they leave, products stay with the store. |
| Services | A user manages services *for* a business. If they leave, services stay. |
| Analytics | Analytics track *business* performance, not user behavior. |
| Media | Images belong to the business profile, not the user's personal account. |
| Theme | The business has a look and feel. Users merely choose it. |
| Reviews | Reviews are about the *business*, not the user who manages it. |

---

## Business Type Enforcement

The business `type` is set at creation and is immutable:

```php
class CreateBusiness
{
    public function __invoke(CreateBusinessData $data): Business
    {
        return DB::transaction(function () use ($data) {
            $business = Business::create([
                'user_id' => auth()->id(),
                'type' => $data->type, // 'product_store' | 'service_business'
                'name' => $data->name,
                // ...
            ]);

            BusinessCreated::dispatch($business);

            return $business;
        });
    }
}
```

Type determines which dashboard sections and public profile sections are available. A product store dashboard never shows "Services" and a service business dashboard never shows "Products".

---

## Current (Phase 1)

### User
- Has one business (Free plan limit)
- Owns nothing inside the business
- Can delete their account — business gets soft-deleted

### Business
- Owns everything: products or services, media, analytics, theme, subscription
- Is the tenant scope for all queries
- Can be transferred to another user (future)

---

## Future (Phase 3+: Teams)

### One User → Multiple Businesses
```php
// Pivot table: business_user
// Role: owner, admin, staff
// Permissions: granular CRUD per domain
```

### Multiple Staff Per Business
| Role | Permissions |
|------|------------|
| **Owner** | Full control, billing, team management, deletion |
| **Admin** | Full control except billing and team management |
| **Staff** | Limited to assigned domains (e.g., Products only) |

### Permission Model
```php
$permissions = [
    'product:create',
    'product:update',
    'product:delete',
    'service:create',
    'service:update',
    'service:delete',
    'media:upload',
    'media:delete',
    'analytics:view',
    'seo:update',
    'theme:change',
    'settings:update',
];
```

---

## Key Rules

1. **Every tenant-scoped table has `business_id`** — never query without it.
2. **`business_id` is set on creation** — never changes after.
3. **Soft deletes preserve data integrity** — a business owner can restore within 30 days.
4. **User deletion does not delete businesses** — ownership transfers to remaining team or admin.
5. **Analytics data is never deleted** — only anonymized if business is permanently removed.
