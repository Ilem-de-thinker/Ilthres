# Database Schema

> Generated via `php artisan make:model <Name> -m`. All migrations are created through artisan commands.

---

## Tenant Isolation

Every tenant-scoped table includes:

```php
$table->foreignId('business_id')
      ->constrained()
      ->cascadeOnDelete()
      ->index();
```

---

## Business Types

A business is one of two types, set at creation and immutable after:

| Type | Constant | Behavior |
|------|----------|----------|
| `product_store` | `Business::TYPE_PRODUCT_STORE` | Has products, no services |
| `service_business` | `Business::TYPE_SERVICE_BUSINESS` | Has services, no products |

The dashboard and public page adapt based on type. Products and services tables are mutually exclusive per business.

---

## Core Tables (Phase 1)

### Users
```php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->timestamp('email_verified_at')->nullable();
    $table->string('password');
    $table->rememberToken();
    $table->timestamps();
});
```

### Businesses
```php
Schema::create('businesses', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->foreignId('category_id')->nullable()->constrained();
    $table->string('name');
    $table->string('slug')->unique();
    $table->enum('type', ['product_store', 'service_business']); // <-- business type
    $table->text('description')->nullable();
    $table->string('email')->nullable();
    $table->string('phone')->nullable();
    $table->string('whatsapp')->nullable();
    $table->string('website')->nullable();
    $table->string('address')->nullable();
    $table->string('city')->nullable();
    $table->string('state')->nullable();
    $table->string('country')->nullable();
    $table->string('logo')->nullable();
    $table->string('cover')->nullable();
    $table->boolean('is_verified')->default(false);
    $table->boolean('is_active')->default(true);
    $table->json('social_links')->nullable();
    $table->json('seo')->nullable();
    $table->timestamps();
    $table->softDeletes();
});
```

### Categories
```php
Schema::create('categories', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('slug')->unique();
    $table->text('description')->nullable();
    $table->string('icon')->nullable();
    $table->foreignId('parent_id')->nullable()->constrained('categories');
    $table->integer('sort_order')->default(0);
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

### Products
```php
Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->foreignId('business_id')->constrained()->cascadeOnDelete();
    $table->foreignId('category_id')->nullable()->constrained();
    $table->string('name');
    $table->string('slug');
    $table->text('description')->nullable();
    $table->decimal('price', 12, 2)->default(0);
    $table->decimal('compare_price', 12, 2)->nullable();
    $table->string('currency', 3)->default('NGN');
    $table->json('images')->nullable();
    $table->json('variants')->nullable();
    $table->boolean('is_active')->default(true);
    $table->boolean('is_featured')->default(false);
    $table->integer('sort_order')->default(0);
    $table->timestamps();
    $table->softDeletes();

    $table->unique(['business_id', 'slug']);
    $table->index(['business_id', 'is_active']);
    $table->index(['business_id', 'category_id']);
});
```

### Services
```php
Schema::create('services', function (Blueprint $table) {
    $table->id();
    $table->foreignId('business_id')->constrained()->cascadeOnDelete();
    $table->foreignId('category_id')->nullable()->constrained();
    $table->string('name');
    $table->string('slug');
    $table->text('description')->nullable();
    $table->decimal('price', 12, 2)->default(0);
    $table->string('currency', 3)->default('NGN');
    $table->integer('duration_minutes')->nullable();
    $table->json('images')->nullable();
    $table->boolean('is_active')->default(true);
    $table->integer('sort_order')->default(0);
    $table->timestamps();
    $table->softDeletes();

    $table->unique(['business_id', 'slug']);
    $table->index(['business_id', 'is_active']);
});
```

### Media
```php
Schema::create('media', function (Blueprint $table) {
    $table->id();
    $table->foreignId('business_id')->constrained()->cascadeOnDelete();
    $table->string('filename');
    $table->string('original_name');
    $table->string('mime_type');
    $table->integer('size');
    $table->string('disk')->default('s3');
    $table->string('path');
    $table->json('variants')->nullable(); // thumbnails, responsive sizes
    $table->string('collection')->nullable(); // logo, cover, product, service, gallery
    $table->json('metadata')->nullable();
    $table->timestamps();
    $table->softDeletes();

    $table->index(['business_id', 'collection']);
});
```

### Themes
```php
Schema::create('themes', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('slug')->unique();
    $table->text('description')->nullable();
    $table->string('thumbnail')->nullable();
    $table->json('config')->nullable(); // default colors, fonts, etc.
    $table->boolean('is_premium')->default(false);
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});

Schema::create('business_theme', function (Blueprint $table) {
    $table->id();
    $table->foreignId('business_id')->constrained()->cascadeOnDelete();
    $table->foreignId('theme_id')->constrained();
    $table->json('customization')->nullable(); // user overrides
    $table->boolean('is_active')->default(false);
    $table->timestamps();

    $table->unique(['business_id', 'theme_id']);
});
```

### Plans
```php
Schema::create('plans', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('slug')->unique();
    $table->text('description')->nullable();
    $table->decimal('price', 12, 2)->default(0);
    $table->string('currency', 3)->default('NGN');
    $table->string('interval')->default('monthly'); // monthly, yearly
    $table->json('features')->nullable();
    $table->integer('sort_order')->default(0);
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

### Subscriptions
```php
Schema::create('subscriptions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('business_id')->constrained()->cascadeOnDelete();
    $table->foreignId('plan_id')->constrained();
    $table->string('status'); // trial, active, expired, cancelled, past_due, suspended
    $table->timestamp('trial_ends_at')->nullable();
    $table->timestamp('starts_at');
    $table->timestamp('ends_at')->nullable();
    $table->timestamp('cancelled_at')->nullable();
    $table->string('gateway')->nullable(); // paystack, flutterwave
    $table->string('gateway_reference')->nullable();
    $table->json('gateway_data')->nullable();
    $table->timestamps();

    $table->index(['business_id', 'status']);
});
```

### Analytics Events
```php
Schema::create('analytics_events', function (Blueprint $table) {
    $table->id();
    $table->foreignId('business_id')->constrained()->cascadeOnDelete();
    $table->foreignId('user_id')->nullable()->constrained();
    $table->string('type'); // page_view, product_view, service_view, whatsapp_click, phone_click
    $table->string('source')->nullable(); // direct, search, social, qr
    $table->string('url')->nullable();
    $table->string('ip')->nullable();
    $table->string('user_agent')->nullable();
    $table->json('metadata')->nullable();
    $table->timestamp('created_at')->index();

    $table->index(['business_id', 'type', 'created_at']);
});
```

---

## Phase 2 Tables

### Reviews
```php
Schema::create('reviews', function (Blueprint $table) {
    $table->id();
    $table->foreignId('business_id')->constrained()->cascadeOnDelete();
    $table->foreignId('user_id')->constrained();
    $table->tinyInteger('rating'); // 1-5
    $table->text('content')->nullable();
    $table->boolean('is_verified')->default(false);
    $table->string('status')->default('pending'); // pending, approved, rejected
    $table->timestamps();
    $table->softDeletes();

    $table->unique(['business_id', 'user_id']);
});
```

### Saved Businesses
```php
Schema::create('saved_businesses', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->foreignId('business_id')->constrained()->cascadeOnDelete();
    $table->timestamps();

    $table->unique(['user_id', 'business_id']);
});
```

### Marketing Links
```php
Schema::create('marketing_links', function (Blueprint $table) {
    $table->id();
    $table->foreignId('business_id')->constrained()->cascadeOnDelete();
    $table->string('name');
    $table->string('slug')->unique();
    $table->string('url');
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});

Schema::create('link_clicks', function (Blueprint $table) {
    $table->id();
    $table->foreignId('marketing_link_id')->constrained()->cascadeOnDelete();
    $table->string('ip')->nullable();
    $table->string('user_agent')->nullable();
    $table->string('referer')->nullable();
    $table->timestamp('created_at');
});
```

---

## Phase 3 Tables

### Appointments
```php
Schema::create('appointments', function (Blueprint $table) {
    $table->id();
    $table->foreignId('business_id')->constrained()->cascadeOnDelete();
    $table->foreignId('service_id')->nullable()->constrained();
    $table->string('customer_name');
    $table->string('customer_email');
    $table->string('customer_phone')->nullable();
    $table->timestamp('start_time');
    $table->timestamp('end_time');
    $table->string('status'); // pending, confirmed, completed, cancelled, no_show
    $table->text('notes')->nullable();
    $table->timestamps();

    $table->index(['business_id', 'start_time']);
    $table->index(['business_id', 'status']);
});
```

### Team Members
```php
Schema::create('team_members', function (Blueprint $table) {
    $table->id();
    $table->foreignId('business_id')->constrained()->cascadeOnDelete();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->string('role'); // owner, admin, staff
    $table->json('permissions')->nullable();
    $table->boolean('is_active')->default(true);
    $table->timestamps();

    $table->unique(['business_id', 'user_id']);
});
```

---

## Indexing Strategy

| Table | Key Indexes | Purpose |
|-------|-------------|---------|
| `businesses` | `slug`, `type`, `category_id`, `city`, `is_verified` | Search & filtering by type |
| `products` | `(business_id, is_active)`, `category_id` | Product listing |
| `services` | `(business_id, is_active)`, `category_id` | Service listing |
| `analytics_events` | `(business_id, type, created_at)` | Report generation |
| `subscriptions` | `(business_id, status)` | Active sub check |
| `appointments` | `(business_id, start_time)`, `(business_id, status)` | Calendar view |

---

## Future: Meilisearch

```php
// Phase 4: Replace database search with Meilisearch
// Indexed models:
// - Business (name, description, city, category, type)
// - Product (name, description, price range)
// - Service (name, description, price range)
```

Migration commands:
```bash
composer require meilisearch/msearch
php artisan meilisearch:import "App\Models\Business"
php artisan meilisearch:import "App\Models\Product"
php artisan meilisearch:import "App\Models\Service"
```
