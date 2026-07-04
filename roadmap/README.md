# Ilthres Market — Development Roadmap

> **Framework:** Laravel 13 + Livewire 4 (Blaze) + Alpine.js + TailwindCSS  
> **Infrastructure:** MySQL + Redis + S3 Compatible Storage  
> **Code Generation:** All components, actions, models, migrations, and tests are created using `php artisan` commands — never manually.

---

## Roadmap Structure

| Phase | Focus | Status |
|-------|-------|--------|
| [Phase 1 — MVP](./phase-1-mvp.md) | Core business profiles, products, services, themes, explore, search, analytics, subscriptions | Pending |
| [Phase 2 — Engagement](./phase-2-engagement.md) | Reviews, saved businesses, premium themes, marketing links, QR codes, notifications | Pending |
| [Phase 3 — Advanced](./phase-3-advanced.md) | Appointments, lead management, teams, custom domains, invoices, advanced reports | Pending |
| [Phase 4 — Ecosystem](./phase-4-ecosystem.md) | AI assistant, AI SEO, recommendations, public API, mobile apps, partner integrations | Pending |

---

## Architecture Documents

| Document | Description |
|----------|-------------|
| [Code Structure](./code_structure.md) | Complete code organization, command reference, and conventions |
| [Domain Structure](./domain-structure.md) | Each domain module breakdown (Actions, DTOs, Events, Listeners, Components) |
| [Database Schema](./database-schema.md) | All tables, relationships, indexes, and tenant isolation strategy |
| [Theme Engine](./theme-engine.md) | Theme system architecture, rendering pipeline, customization API |
| [Data Ownership](./data-ownership.md) | User vs Business data model, multi-business, staff permissions |

---

## Code Generation Rules

All code is generated using artisan commands — never created manually:

```bash
# Livewire Components
php artisan make:livewire <Domain>\\<Name>

# Models with Migrations
php artisan make:model <Name> -m

# Actions (custom command)
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
php artisan make:notification <Domain>\\<NotificationName>

# Tests
php artisan make:test <Domain>\\<TestName>
```

---

## Business Types

A business is one of two types, set at creation and immutable:

| Type | Constant | Has Products | Has Services | Has Appointments |
|------|----------|-------------|-------------|------------------|
| Product Store | `Business::TYPE_PRODUCT_STORE` | Yes | No | No |
| Service Business | `Business::TYPE_SERVICE_BUSINESS` | No | Yes | Yes (Phase 3) |

The merchant dashboard, public profile, and available features all adapt based on the business type.

## Naming Conventions

- **Livewire Components:** `kebab-case` in routes, `PascalCase` in classes
- **Database Tables:** `snake_case` plural
- **Columns:** `snake_case`
- **Events:** Past tense (`BusinessCreated`, `ProductViewed`, `ServiceViewed`)
- **Actions:** Imperative (`CreateBusiness`, `UpdateProduct`, `CreateService`)
- **Jobs:** Descriptive (`ProcessAnalytics`, `GenerateSitemap`)
- **DTOs:** Descriptive (`BusinessData`, `ProductData`, `ServiceData`)
