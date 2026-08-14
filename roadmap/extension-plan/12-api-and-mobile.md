# 12 — API & Mobile App (future)

> REST API for future .NET MAUI mobile app. Uses Laravel Sanctum for auth.

## What Already Exists

- No API routes exist (`routes/api.php` does not exist)
- No Sanctum installed
- All logic is in Livewire components (web-only)

## What This Phase Adds

### 1. Installation

```bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
```

### 2. API Routes

```php
// routes/api.php

Route::prefix('v1')->group(function () {

    // ─── Public ───────────────────────────────
    Route::get('/businesses', [BusinessController::class, 'index']);
    Route::get('/businesses/{slug}', [BusinessController::class, 'show']);
    Route::get('/businesses/{slug}/products', [BusinessController::class, 'products']);
    Route::get('/businesses/{slug}/services', [BusinessController::class, 'services']);
    Route::get('/businesses/{slug}/reviews', [BusinessController::class, 'reviews']);

    Route::get('/search', [SearchController::class, 'search']);
    Route::get('/categories', [SearchController::class, 'categories']);
    Route::get('/nearby', [SearchController::class, 'nearby']);

    // ─── Auth ─────────────────────────────────
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);

    // ─── Protected ────────────────────────────
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', [AuthController::class, 'user']);
        Route::post('/logout', [AuthController::class, 'logout']);

        // Business owner
        Route::middleware('business.owner')->group(function () {
            Route::apiResource('my-businesses', BusinessController::class);
            Route::post('/businesses/{id}/products', [BusinessController::class, 'storeProduct']);
            Route::post('/businesses/{id}/services', [BusinessController::class, 'storeService']);
            Route::get('/businesses/{id}/analytics', [BusinessController::class, 'analytics']);
            Route::get('/businesses/{id}/bookings', [BookingController::class, 'index']);
            Route::get('/businesses/{id}/orders', [OrderController::class, 'index']);
        });

        // Customer
        Route::post('/bookings', [BookingController::class, 'store']);
        Route::get('/my-bookings', [BookingController::class, 'myBookings']);
        Route::post('/orders', [OrderController::class, 'store']);
        Route::get('/my-orders', [OrderController::class, 'myOrders']);
        Route::post('/reviews', [ReviewController::class, 'store']);
        Route::post('/messages', [MessageController::class, 'store']);
        Route::get('/conversations', [MessageController::class, 'conversations']);
    });
});
```

### 3. API Controllers

```
app/Http/Controllers/Api/
├── AuthController.php
├── BusinessController.php
├── SearchController.php
├── BookingController.php
├── OrderController.php
├── ReviewController.php
└── MessageController.php
```

### 4. API Resources

```
app/Http/Resources/
├── BusinessResource.php
├── ProductResource.php
├── ServiceResource.php
├── ReviewResource.php
├── BookingResource.php
├── OrderResource.php
├── MessageResource.php
├── SearchResultResource.php
└── CategoryResource.php
```

### 5. Rate Limiting

```php
// bootstrap/app.php or RouteServiceProvider
Route::middleware('throttle:60,1')->group(function () {
    // Public API routes
});

Route::middleware('throttle:120,1')->group(function () {
    // Authenticated API routes
});
```

### 6. Business Owner Middleware

```php
// app/Http/Middleware/EnsureUserIsBusinessOwner.php
// Checks that authenticated user owns at least one business
```

Register in `bootstrap/app.php`:
```php
'business.owner' => \App\Http\Middleware\EnsureUserIsBusinessOwner::class,
```

### 7. Config

Add to `config/sanctum.php`:
```php
'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', sprintf(
    '%s%s',
    'localhost,localhost:3000,127.0.0.1,127.0.0.1:8000,::1',
    env('APP_URL') ? ','.parse_url(env('APP_URL'), PHP_URL_HOST) : ''
))),
```

---

## Checklist

- [ ] Install Laravel Sanctum
- [ ] Create `routes/api.php`
- [ ] API Controllers: Auth, Business, Search, Booking, Order, Review, Message
- [ ] API Resources for all models
- [ ] Rate limiting configuration
- [ ] Business owner middleware
- [ ] Sanctum stateful domains config
- [ ] Test all endpoints with Postman/cURL
- [ ] API documentation (optional: Scribe or Swagger)
