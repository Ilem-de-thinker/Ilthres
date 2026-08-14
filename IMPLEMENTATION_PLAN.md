# Implementation Plan: Bookings, AI-Search, and Reviews

## Overview

Three interconnected features to complete the MVP core for Ilthres Market:

1. **Bookings** - Service booking system (mirrors Orders for products)
2. **AI-Powered Search** - Natural language + smart ranking (searches businesses, services, AND products)
3. **Reviews** - Post-transaction review system

---

## Phase 1: Bookings System

### Database Schema

#### `bookings` table

| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | auto-increment |
| business_id | bigint FK | references businesses |
| service_id | bigint FK | references services |
| user_id | bigint FK, nullable | null for guest bookings |
| customer_name | string | required |
| customer_phone | string | required |
| customer_email | string, nullable | optional |
| booking_date | date | required |
| booking_time | time | required |
| duration_minutes | integer | from service default |
| status | enum | pending, confirmed, in_progress, completed, cancelled |
| payment_required | boolean | default: false |
| payment_status | enum | unpaid, paid, refunded |
| amount | decimal(10,2) | from service price |
| currency | string | NGN default |
| notes | text, nullable | customer notes |
| timestamps | - | created_at, updated_at |
| soft_deletes | - | deleted_at |

**Indexes:**
- business_id + booking_date (composite)
- service_id + booking_date + booking_time (composite, unique per slot)
- user_id
- status

#### `service_availability` table

| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | auto-increment |
| business_id | bigint FK | references businesses |
| service_id | bigint FK | references services |
| day_of_week | enum | mon, tue, wed, thu, fri, sat, sun |
| start_time | time | e.g., 09:00 |
| end_time | time | e.g., 17:00 |
| max_bookings_per_slot | integer | default: 1 |
| slot_duration_minutes | integer | default: 60 |
| is_active | boolean | default: true |
| timestamps | - | - |

**Indexes:**
- business_id + service_id (composite)
- day_of_week

#### `booking_slots` table

| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | auto-increment |
| service_availability_id | bigint FK | references service_availability |
| date | date | the actual bookable date |
| start_time | time | slot start |
| end_time | time | slot end |
| current_bookings | integer | default: 0 |
| max_bookings | integer | from availability config |
| is_available | boolean | default: true |
| timestamps | - | - |

**Indexes:**
- service_availability_id + date (composite)
- date + start_time (composite)

### Models

#### Booking

```php
// app/Models/Booking.php

fillable: business_id, service_id, user_id, customer_name, customer_phone,
         customer_email, booking_date, booking_time, duration_minutes,
         status, payment_required, payment_status, amount, currency, notes

casts: booking_date => date, booking_time => datetime:H:i,
       payment_required => boolean, amount => decimal:2

relations:
  - business(): BelongsTo(Business)
  - service(): BelongsTo(Service)
  - user(): BelongsTo(User)

statuses: PENDING = 'pending', CONFIRMED = 'confirmed',
          IN_PROGRESS = 'in_progress', COMPLETED = 'completed',
          CANCELLED = 'cancelled'

payment_statuses: UNPAID = 'unpaid', PAID = 'paid', REFUNDED = 'refunded'

methods:
  - isPending(): bool
  - isConfirmed(): bool
  - isCompleted(): bool
  - canBeCancelled(): bool
  - getEndTimeAttribute(): Carbon (booking_time + duration_minutes)
```

#### ServiceAvailability

```php
// app/Models/ServiceAvailability.php

fillable: business_id, service_id, day_of_week, start_time, end_time,
         max_bookings_per_slot, slot_duration_minutes, is_active

casts: start_time => datetime:H:i, end_time => datetime:H:i,
       is_active => boolean

relations:
  - business(): BelongsTo(Business)
  - service(): BelongsTo(Service)
  - slots(): HasMany(BookingSlot)
```

#### BookingSlot

```php
// app/Models/BookingSlot.php

fillable: service_availability_id, date, start_time, end_time,
         current_bookings, max_bookings, is_available

casts: date => date, start_time => datetime:H:i, end_time => datetime:H:i,
       is_available => boolean

relations:
  - availability(): BelongsTo(ServiceAvailability)

methods:
  - isFullyBooked(): bool
  - getRemainingSlotsAttribute(): int
  - book(): bool (increment current_bookings, check capacity)
  - cancel(): bool (decrement current_bookings)
```

### Business Logic

#### Service Availability Management

1. Seller navigates to service edit page
2. Sets available hours per day of week
3. System generates bookable slots for next 30 days
4. Seller can override specific dates (holidays, special hours)

#### Slot Generation Algorithm

```
For each service_availability record:
  1. Get all dates in next 30 days matching day_of_week
  2. For each date:
     a. Calculate number of slots: (end_time - start_time) / slot_duration
     b. Create BookingSlot for each time window
     c. Set max_bookings from availability config
```

#### Booking Flow

```
Customer Action:
  1. Click "Book Now" on service detail
  2. Select date → AJAX loads available slots
  3. Select time slot
  4. Fill customer info form
  5. Submit → create Booking record

Payment Handling:
  IF payment_required = true:
    1. Create booking with status = 'pending', payment_status = 'unpaid'
    2. Redirect to Paystack checkout
    3. On callback: verify payment, update status to 'confirmed'
  ELSE:
    1. Create booking with status = 'pending', payment_status = 'unpaid'
    2. Send notification to seller
    3. Seller confirms/rejects

Seller Action:
  1. View booking in dashboard
  2. Confirm → status = 'confirmed'
  3. During appointment → status = 'in_progress'
  4. After completion → status = 'completed'
  5. Cancel → status = 'cancelled' (with reason)
```

### Livewire Components

#### BookAppointment (Customer-facing)

```
Location: app/Domains/Booking/Livewire/BookAppointment.php

Properties:
  - $business (Business model)
  - $service (Service model)
  - $selectedDate
  - $selectedSlot (BookingSlot)
  - $customerName
  - $customerPhone
  - $customerEmail
  - $notes

Methods:
  - mount($businessId, $serviceId)
  - selectDate($date) → load available slots
  - selectSlot($slotId)
  - submit() → create booking, redirect to payment if needed

View: resources/views/livewire/booking/book-appointment.blade.php
```

#### BookingManager (Seller Dashboard)

```
Location: app/Domains/Booking/Livewire/BookingManager.php

Properties:
  - $business (Business model)
  - $statusFilter
  - $dateFilter
  - $searchQuery

Methods:
  - mount()
  - getBookingsProperty() → filtered collection
  - updateStatus($bookingId, $status)
  - cancelBooking($bookingId, $reason)

View: resources/views/livewire/booking/booking-manager.blade.php
```

#### AvailabilityManager (Seller Dashboard)

```
Location: app/Domains/Booking/Livewire/AvailabilityManager.php

Properties:
  - $business (Business model)
  - $service (Service model)
  - $availability (collection)

Methods:
  - mount($serviceId)
  - saveAvailability($data)
  - toggleDay($day)
  - generateSlots()

View: resources/views/livewire/booking/availability-manager.blade.php
```

### Theme Integration

Update service detail views in all 4 themes:

**default, bold, heritage, modern:**

```blade
{{-- Add booking section for services with availability --}}
@if($service->business->isServiceBusiness() && $service->hasAvailability())
  <div class="booking-section">
    <livewire:booking.book-appointment
      :business="$service->business"
      :service="$service"
      :key="$service->id"
    />
  </div>
@else
  {{-- Keep existing inquiry/WhatsApp buttons --}}
@endif
```

### Routes

```php
// routes/web.php additions

// Customer booking flow
Route::get('/{business:slug}/book/{service}', [BookingController::class, 'show'])
    ->name('booking.show');
Route::post('/{business:slug}/book/{service}', [BookingController::class, 'store'])
    ->name('booking.store');
Route::get('/{business:slug}/booking/{booking}/confirm', [BookingController::class, 'confirm'])
    ->name('booking.confirm');

// Seller booking management
Route::get('/dashboard/bookings', BookingManager::class)
    ->name('dashboard.bookings');
Route::get('/dashboard/services/{service}/availability', AvailabilityManager::class)
    ->name('dashboard.availability');
```

---

## Phase 2: AI-Powered Search

### Natural Language Processing

#### Search Scope

AI search covers **all entities**:
- **Businesses** - company names, descriptions, categories
- **Services** - service names, descriptions, pricing
- **Products** - product names, descriptions, pricing, categories

#### Input Parsing

```
User Input: "I need a plumber near me"
            ↓
Parsed Output:
  - query_type: "service" (plumber = service keyword)
  - service_type: "plumber" → maps to category_id or service keyword
  - location: "near me" → use geolocation
  - modifiers: none
  - searches: businesses + services

User Input: "Best restaurants in Lagos open now"
            ↓
Parsed Output:
  - query_type: "service" (restaurants = service keyword)
  - service_type: "restaurants" → maps to category_id
  - location: "Lagos" → use coordinates
  - modifiers: ["best", "open now"]
  - searches: businesses + services

User Input: "iPhone 14 case"
            ↓
Parsed Output:
  - query_type: "product" (iPhone case = product keyword)
  - product_type: "phone case"
  - location: null
  - modifiers: none
  - searches: businesses + products

User Input: "Cheap web designer in Abuja"
            ↓
Parsed Output:
  - query_type: "service" (web designer = service keyword)
  - service_type: "web designer"
  - location: "Abuja" → use coordinates
  - modifiers: ["cheap"]
  - searches: businesses + services + products
```

#### Keyword Mapping

```php
// config/search.php

'service_keywords' => [
    'plumber' => ['plumbing', 'pipe', 'leak', 'water'],
    'electrician' => ['electrical', 'wiring', 'power', 'light'],
    'hairdresser' => ['salon', 'hair', 'barber', 'stylist'],
    'restaurant' => ['food', 'eat', 'dining', 'meal'],
    'designer' => ['web', 'graphic', 'logo', 'brand'],
    // ... more mappings
],

'product_keywords' => [
    'phone' => ['iphone', 'android', 'mobile', 'case', 'charger'],
    'clothes' => ['shirt', 'pants', 'dress', 'fashion', 'wear'],
    'electronics' => ['laptop', 'computer', 'tv', 'headphone'],
    'food' => ['rice', 'beans', 'oil', 'spice', 'organic'],
    // ... more mappings
],

'location_keywords' => [
    'near me' => 'geolocation',
    'in {city}' => 'city_lookup',
    'around {area}' => 'area_lookup',
],

'modifier_keywords' => [
    'best' => 'rating',
    'cheap' => 'price_low',
    'affordable' => 'price_low',
    'premium' => 'price_high',
    'open now' => 'availability',
    'verified' => 'is_verified',
    'nearby' => 'proximity',
]
```

### Geolocation Integration

#### Browser Geolocation API

```javascript
// resources/js/geolocation.js

export function getCurrentPosition() {
    return new Promise((resolve, reject) => {
        if (!navigator.geolocation) {
            reject(new Error('Geolocation not supported'));
        }

        navigator.geolocation.getCurrentPosition(
            (position) => {
                resolve({
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                });
            },
            (error) => {
                reject(error);
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 300000 // 5 minutes cache
            }
        );
    });
}
```

#### Session Storage

```php
// Store in session after first geolocation request
session(['user_location' => ['lat' => $lat, 'lng' => $lng]]);
```

### Smart Ranking Algorithm

#### Scoring Formula

```php
// app/Domains/Search/Services/RankingService.php

public function calculateScore(Business $business, ?array $userLocation): float
{
    $score = 0;

    // Verified boost: +30 points
    if ($business->is_verified) {
        $score += 30;
    }

    // Rating boost: +25 points (scaled 0-5)
    $score += ($business->average_rating / 5) * 25;

    // Activity boost: +20 points (recent posts in 30 days)
    $recentProducts = $business->products()
        ->where('created_at', '>=', now()->subDays(30))
        ->count();
    $recentServices = $business->services()
        ->where('created_at', '>=', now()->subDays(30))
        ->count();
    $activityScore = min(($recentProducts + $recentServices) / 5, 1) * 20;
    $score += $activityScore;

    // Proximity boost: +15 points (inverse distance)
    if ($userLocation && $business->latitude && $business->longitude) {
        $distance = $this->calculateDistance(
            $userLocation['lat'], $userLocation['lng'],
            $business->latitude, $business->longitude
        );
        // Max 50km for full points
        $proximityScore = max(0, 1 - ($distance / 50)) * 15;
        $score += $proximityScore;
    }

    // Affordability boost: +10 points (price range match)
    // This would be based on user's price filter or historical preference
    $score += 5; // Default middle score

    return $score;
}

private function calculateDistance(float $lat1, float $lng1, float $lat2, float $lng2): float
{
    // Haversine formula
    $earthRadius = 6371; // km

    $dLat = deg2rad($lat2 - $lat1);
    $dLng = deg2rad($lng2 - $lng1);

    $a = sin($dLat / 2) * sin($dLat / 2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($dLng / 2) * sin($dLng / 2);

    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

    return $earthRadius * $c;
}
```

### Laravel Scout Setup

#### Configuration

```php
// config/scout.php

'driver' => env('SCOUT_DRIVER', 'database'),

'queue' => [
    'connection' => 'redis',
],

'prefixes' => [
    'businesses' => 'biz',
    'services' => 'svc',
    'products' => 'prd',
],
```

#### Model Configuration

```php
// app/Models/Business.php additions

use Laravel\Scout\Searchable;

// In class:
use Searchable;

// Method:
public function toSearchableArray(): array
{
    return [
        'id' => $this->id,
        'name' => $this->name,
        'description' => $this->description,
        'type' => $this->type,
        'city' => $this->city,
        'state' => $this->state,
        'is_verified' => $this->is_verified,
        'average_rating' => $this->average_rating,
        'category_id' => $this->category_id,
    ];
}
```

```php
// app/Models/Service.php additions

use Laravel\Scout\Searchable;

// In class:
use Searchable;

// Method:
public function toSearchableArray(): array
{
    return [
        'id' => $this->id,
        'business_id' => $this->business_id,
        'name' => $this->name,
        'description' => $this->description,
        'price' => $this->price,
        'category_id' => $this->category_id,
        'duration_minutes' => $this->duration_minutes,
    ];
}
```

```php
// app/Models/Product.php additions

use Laravel\Scout\Searchable;

// In class:
use Searchable;

// Method:
public function toSearchableArray(): array
{
    return [
        'id' => $this->id,
        'business_id' => $this->business_id,
        'name' => $this->name,
        'description' => $this->description,
        'price' => $this->price,
        'category_id' => $this->category_id,
        'sku' => $this->sku,
    ];
}
```

### Enhanced ExploreSearch

#### Natural Language Parser

```php
// app/Domains/Search/Services/SearchParser.php

public function parse(string $query): array
{
    $result = [
        'query_type' => null, // 'service', 'product', or 'general'
        'service_type' => null,
        'product_type' => null,
        'location' => null,
        'modifiers' => [],
        'raw_query' => $query,
    ];

    // Extract location patterns
    $locationPatterns = [
        '/near me/i' => 'geolocation',
        '/in\s+(\w+)/i' => 'city',
        '/around\s+(\w+)/i' => 'area',
    ];

    foreach ($locationPatterns as $pattern => $type) {
        if (preg_match($pattern, $query, $matches)) {
            $result['location'] = [
                'type' => $type,
                'value' => $matches[1] ?? null,
            ];
            break;
        }
    }

    // Extract modifiers
    $modifierKeywords = ['best', 'cheap', 'affordable', 'premium', 'open', 'verified', 'nearby'];
    foreach ($modifierKeywords as $keyword) {
        if (str_contains(strtolower($query), $keyword)) {
            $result['modifiers'][] = $keyword;
        }
    }

    // Extract search terms (remaining query after removing location and modifiers)
    $searchQuery = $query;
    foreach ($result['modifiers'] as $modifier) {
        $searchQuery = str_ireplace($modifier, '', $searchQuery);
    }
    if ($result['location']) {
        $searchQuery = preg_replace('/near me|in\s+\w+|around\s+\w+/', '', $searchQuery);
    }
    $searchQuery = trim($searchQuery);

    // Determine query type based on keywords
    $serviceKeywords = ['plumber', 'electrician', 'hairdresser', 'salon', 'restaurant', 'designer', 'cleaning', 'repair'];
    $productKeywords = ['phone', 'case', 'charger', 'laptop', 'tv', 'shirt', 'dress', 'shoes', 'bag', 'watch'];

    $isServiceQuery = false;
    $isProductQuery = false;

    foreach ($serviceKeywords as $keyword) {
        if (str_contains(strtolower($searchQuery), $keyword)) {
            $isServiceQuery = true;
            break;
        }
    }

    foreach ($productKeywords as $keyword) {
        if (str_contains(strtolower($searchQuery), $keyword)) {
            $isProductQuery = true;
            break;
        }
    }

    if ($isServiceQuery) {
        $result['query_type'] = 'service';
        $result['service_type'] = $searchQuery;
    } elseif ($isProductQuery) {
        $result['query_type'] = 'product';
        $result['product_type'] = $searchQuery;
    } else {
        $result['query_type'] = 'general';
        $result['service_type'] = $searchQuery;
        $result['product_type'] = $searchQuery;
    }

    return $result;
}
```

#### Search Execution

```php
// app/Domains/Search/Services/AISearchService.php

public function search(string $query, ?array $userLocation = null): Collection
{
    $parsed = $this->parser->parse($query);

    // Determine what to search based on query type
    $searchQuery = $parsed['service_type'] ?? $parsed['product_type'] ?? $query;

    // Search businesses
    $businessResults = Business::search($searchQuery)
        ->when($parsed['location']['type'] === 'city', function ($q) use ($parsed) {
            $q->where('city', $parsed['location']['value']);
        })
        ->get();

    // Search services
    $serviceResults = Service::search($searchQuery)
        ->when($parsed['location']['type'] === 'city', function ($q) use ($parsed) {
            $q->whereHas('business', function ($bq) use ($parsed) {
                $bq->where('city', $parsed['location']['value']);
            });
        })
        ->get()
        ->pluck('business')
        ->unique('id');

    // Search products
    $productResults = Product::search($searchQuery)
        ->when($parsed['location']['type'] === 'city', function ($q) use ($parsed) {
            $q->whereHas('business', function ($bq) use ($parsed) {
                $bq->where('city', $parsed['location']['value']);
            });
        })
        ->get()
        ->pluck('business')
        ->unique('id');

    // Merge all results
    $results = $businessResults->merge($serviceResults)->merge($productResults)->unique('id');

    // Apply modifiers
    if (in_array('verified', $parsed['modifiers'])) {
        $results = $results->where('is_verified', true);
    }

    if (in_array('open', $parsed['modifiers'])) {
        $results = $results->filter(function ($business) {
            return $this->isBusinessOpen($business);
        });
    }

    // Calculate ranking scores
    $scoredResults = $results->map(function ($business) use ($userLocation) {
        return [
            'business' => $business,
            'score' => $this->ranking->calculateScore($business, $userLocation),
        ];
    });

    // Sort by score
    return $scoredResults->sortByDesc('score')->pluck('business');
}
```

### Routes & Livewire

```php
// routes/web.php additions

// AI Search endpoint
Route::post('/api/search/parse', [SearchController::class, 'parse'])
    ->name('api.search.parse');
Route::post('/api/search/execute', [SearchController::class, 'execute'])
    ->name('api.search.execute');
Route::post('/api/location/store', [LocationController::class, 'store'])
    ->name('api.location.store');
```

#### Enhanced ExploreSearch Component

```php
// app/Domains/Search/Livewire/ExploreSearch.php additions

public string $naturalQuery = '';
public bool $useAISearch = false;
public ?array $userLocation = null;

public function updatedNaturalQuery(): void
{
    if (strlen($this->naturalQuery) > 10) {
        $this->useAISearch = true;
        $this->performSearch();
    }
}

public function storeUserLocation(float $lat, float $lng): void
{
    $this->userLocation = ['lat' => $lat, 'lng' => $lng];
    session(['user_location' => $this->userLocation]);
}
```

---

## Phase 3: Reviews System

### Database Schema

#### `reviews` table

| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | auto-increment |
| business_id | bigint FK | references businesses |
| order_id | bigint FK, nullable | for product purchases |
| booking_id | bigint FK, nullable | for service bookings |
| user_id | bigint FK, nullable | null for guest reviews |
| reviewer_name | string | display name |
| rating | tinyint | 1-5 stars |
| comment | text, nullable | review text |
| seller_response | text, nullable | business owner response |
| is_active | boolean | default: true |
| timestamps | - | - |
| soft_deletes | - | - |

**Indexes:**
- business_id + is_active (composite)
- order_id (unique, one review per order)
- booking_id (unique, one review per booking)
- user_id

#### Add to `businesses` table

| Column | Type | Notes |
|--------|------|-------|
| average_rating | decimal(3,2) | default: 0.00 |
| review_count | integer | default: 0 |

### Models

#### Review

```php
// app/Models/Review.php

fillable: business_id, order_id, booking_id, user_id, reviewer_name,
         rating, comment, seller_response, is_active

casts: rating => integer, is_active => boolean

relations:
  - business(): BelongsTo(Business)
  - order(): BelongsTo(Order)
  - booking(): BelongsTo(Booking)
  - user(): BelongsTo(User)

validation:
  - rating: required|integer|in:1,2,3,4,5
  - comment: nullable|string|max:1000
  - reviewer_name: required|string|max:255

methods:
  - canBeEditedBy(User $user): bool (owner within 24 hours)
  - edit(array $data): bool
  - respond(string $response): bool (seller response)
  - flag(): bool (report inappropriate)
```

#### Business (additions)

```php
// Add to existing Business model

public function reviews(): HasMany
{
    return $this->hasMany(Review::class)->where('is_active', true);
}

public function getAverageRatingAttribute(): float
{
    return $this->reviews()->avg('rating') ?? 0;
}

public function getReviewCountAttribute(): int
{
    return $this->reviews()->count();
}

public function getRatingDistributionAttribute(): array
{
    return $this->reviews()
        ->selectRaw('rating, count(*) as count')
        ->groupBy('rating')
        ->pluck('count', 'rating')
        ->toArray();
}

// Update rating cache when review saved
public function updateRatingCache(): void
{
    $this->update([
        'average_rating' => $this->reviews()->avg('rating'),
        'review_count' => $this->reviews()->count(),
    ]);
}
```

### Review Eligibility Rules

```php
// app/Domains/Review/Policies/ReviewPolicy.php

public function create(User $user, Business $business): bool
{
    // Check if user has completed order with this business
    $hasCompletedOrder = Order::where('business_id', $business->id)
        ->where('user_id', $user->id)
        ->where('status', 'completed')
        ->exists();

    // Check if user has completed booking with this business
    $hasCompletedBooking = Booking::where('business_id', $business->id)
        ->where('user_id', $user->id)
        ->where('status', 'completed')
        ->exists();

    // Check if user already reviewed this order/booking
    $alreadyReviewed = Review::where('business_id', $business->id)
        ->where('user_id', $user->id)
        ->exists();

    return ($hasCompletedOrder || $hasCompletedBooking) && !$alreadyReviewed;
}

public function edit(User $user, Review $review): bool
{
    // Owner can edit within 24 hours
    return $review->user_id === $user->id
        && $review->created_at->diffInHours(now()) < 24;
}
```

### Review Flow

```
Customer Journey:
  1. Complete order/booking → status = 'completed'
  2. Receive notification (email + in-app)
     "How was your experience with [Business Name]?"
  3. Click "Leave a Review" link
  4. Rate (1-5 stars) + write comment
  5. Submit → review appears on business profile

Seller Journey:
  1. Receive notification "New review received"
  2. View review in dashboard
  3. Option to respond (public response)
  4. Option to flag inappropriate review
```

### Livewire Components

#### LeaveReview (Customer-facing)

```
Location: app/Domains/Review/Livewire/LeaveReview.php

Properties:
  - $business (Business model)
  - $order (Order model, nullable)
  - $booking (Booking model, nullable)
  - $rating (1-5)
  - $comment
  - $reviewerName

Methods:
  - mount($businessId, $orderId = null, $bookingId = null)
  - setRating($rating)
  - submit() → create review, update business rating cache

View: resources/views/livewire/review/leave-review.blade.php
```

#### ReviewManager (Seller Dashboard)

```
Location: app/Domains/Review/Livewire/ReviewManager.php

Properties:
  - $business (Business model)
  - $ratingFilter
  - $searchQuery

Methods:
  - mount()
  - getReviewsProperty() → filtered collection
  - respondToReview($reviewId, $response)
  - flagReview($reviewId, $reason)

View: resources/views/livewire/review/review-manager.blade.php
```

#### ReviewDisplay (Public)

```
Location: app/Domains/Review/Livewire/ReviewDisplay.php

Properties:
  - $business (Business model)
  - $limit (default: 5)

Methods:
  - mount($businessId)
  - getReviewsProperty()

View: resources/views/livewire/review/review-display.blade.php
```

### Theme Integration

Update business profile views in all 4 themes:

```blade
{{-- Add reviews section to business profiles --}}

{{-- Rating Badge --}}
<div class="rating-badge">
  <span class="stars">{{ $business->average_rating }}</span>
  <span class="count">({{ $business->review_count }} reviews)</span>
</div>

{{-- Reviews Section --}}
<section class="reviews-section">
  <h3>Customer Reviews</h3>

  {{-- Rating Distribution --}}
  <div class="rating-distribution">
    @for($i = 5; $i >= 1; $i--)
      <div class="rating-bar">
        <span>{{ $i }} stars</span>
        <div class="bar" style="width: {{ $business->ratingDistribution[$i] ?? 0 }}%"></div>
        <span>{{ $business->ratingDistribution[$i] ?? 0 }}</span>
      </div>
    @endfor
  </div>

  {{-- Reviews List --}}
  <livewire:review.review-display
    :business="$business"
    :key="$business->id"
  />

  {{-- Leave Review Button (if eligible) --}}
  @auth
    @if(auth()->user()->canReview($business))
      <a href="{{ route('review.create', $business) }}">
        Write a Review
      </a>
    @endif
  @endauth
</section>
```

### Routes

```php
// routes/web.php additions

// Customer review flow
Route::get('/{business:slug}/review', [ReviewController::class, 'create'])
    ->name('review.create');
Route::post('/{business:slug}/review', [ReviewController::class, 'store'])
    ->name('review.store');
Route::put('/review/{review}', [ReviewController::class, 'update'])
    ->name('review.update');

// Seller review management
Route::get('/dashboard/reviews', ReviewManager::class)
    ->name('dashboard.reviews');
```

---

## Database Migrations

### Migration 1: Create Bookings Tables

```php
// database/migrations/2026_07_15_020000_create_bookings_tables.php

Schema::create('bookings', function (Blueprint $table) {
    $table->id();
    $table->foreignId('business_id')->constrained()->cascadeOnDelete();
    $table->foreignId('service_id')->constrained()->cascadeOnDelete();
    $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
    $table->string('customer_name');
    $table->string('customer_phone');
    $table->string('customer_email')->nullable();
    $table->date('booking_date');
    $table->time('booking_time');
    $table->integer('duration_minutes')->default(60);
    $table->enum('status', ['pending', 'confirmed', 'in_progress', 'completed', 'cancelled'])->default('pending');
    $table->boolean('payment_required')->default(false);
    $table->enum('payment_status', ['unpaid', 'paid', 'refunded'])->default('unpaid');
    $table->decimal('amount', 10, 2)->default(0);
    $table->string('currency', 3)->default('NGN');
    $table->text('notes')->nullable();
    $table->timestamps();
    $table->softDeletes();

    $table->index(['business_id', 'booking_date']);
    $table->index(['service_id', 'booking_date', 'booking_time']);
    $table->index('user_id');
    $table->index('status');
});

Schema::create('service_availability', function (Blueprint $table) {
    $table->id();
    $table->foreignId('business_id')->constrained()->cascadeOnDelete();
    $table->foreignId('service_id')->constrained()->cascadeOnDelete();
    $table->enum('day_of_week', ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun']);
    $table->time('start_time');
    $table->time('end_time');
    $table->integer('max_bookings_per_slot')->default(1);
    $table->integer('slot_duration_minutes')->default(60);
    $table->boolean('is_active')->default(true);
    $table->timestamps();

    $table->index(['business_id', 'service_id']);
    $table->index('day_of_week');
});

Schema::create('booking_slots', function (Blueprint $table) {
    $table->id();
    $table->foreignId('service_availability_id')->constrained()->cascadeOnDelete();
    $table->date('date');
    $table->time('start_time');
    $table->time('end_time');
    $table->integer('current_bookings')->default(0);
    $table->integer('max_bookings')->default(1);
    $table->boolean('is_available')->default(true);
    $table->timestamps();

    $table->index(['service_availability_id', 'date']);
    $table->index(['date', 'start_time']);
});
```

### Migration 2: Create Reviews Table

```php
// database/migrations/2026_07_15_020001_create_reviews_table.php

Schema::create('reviews', function (Blueprint $table) {
    $table->id();
    $table->foreignId('business_id')->constrained()->cascadeOnDelete();
    $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
    $table->foreignId('booking_id')->nullable()->constrained()->nullOnDelete();
    $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
    $table->string('reviewer_name');
    $table->tinyInteger('rating');
    $table->text('comment')->nullable();
    $table->text('seller_response')->nullable();
    $table->boolean('is_active')->default(true);
    $table->timestamps();
    $table->softDeletes();

    $table->index(['business_id', 'is_active']);
    $table->unique('order_id');
    $table->unique('booking_id');
    $table->index('user_id');
});

Schema::table('businesses', function (Blueprint $table) {
    $table->decimal('average_rating', 3, 2)->default(0)->after('is_active');
    $table->integer('review_count')->default(0)->after('average_rating');
});
```

---

## Implementation Checklist

### Phase 1: Bookings

- [ ] Create migration for bookings tables
- [ ] Create Booking model
- [ ] Create ServiceAvailability model
- [ ] Create BookingSlot model
- [ ] Create BookingController
- [ ] Create BookAppointment Livewire component
- [ ] Create BookingManager Livewire component
- [ ] Create AvailabilityManager Livewire component
- [ ] Create blade views for booking components
- [ ] Add booking routes
- [ ] Update service detail views (4 themes)
- [ ] Add "Bookings" to seller dashboard navigation
- [ ] Write tests for booking flow

### Phase 2: AI-Search

- [ ] Create SearchParser service
- [ ] Create RankingService
- [ ] Create AISearchService
- [ ] Configure Laravel Scout
- [ ] Add Searchable trait to models
- [ ] Create geolocation JavaScript module
- [ ] Create LocationController
- [ ] Update ExploreSearch Livewire component
- [ ] Add natural language search UI
- [ ] Add search API endpoints
- [ ] Write tests for search parsing
- [ ] Write tests for ranking algorithm

### Phase 3: Reviews

- [ ] Create migration for reviews table
- [ ] Create migration to add rating columns to businesses
- [ ] Create Review model
- [ ] Update Business model with review relationships
- [ ] Create ReviewPolicy
- [ ] Create LeaveReview Livewire component
- [ ] Create ReviewManager Livewire component
- [ ] Create ReviewDisplay Livewire component
- [ ] Create blade views for review components
- [ ] Add review routes
- [ ] Update business profile views (4 themes)
- [ ] Add "Reviews" to seller dashboard navigation
- [ ] Create ReviewReceived notification
- [ ] Write tests for review flow

---

## Dependencies

### External Services

- **Geolocation**: Browser Geolocation API (no external service)
- **Search Engine**: Laravel Scout with database driver (no external service)
- **Payment**: Paystack (already integrated)

### Laravel Packages

- `laravel/scout` (already installed)

---

## Testing Strategy

### Unit Tests

- Booking model methods
- Review model methods
- SearchParser parsing logic
- RankingService scoring

### Feature Tests

- Booking creation flow
- Booking status updates
- Review creation with eligibility check
- Search with various queries

### Integration Tests

- Full booking flow with payment
- Review submission after order completion
- Search results ranking verification

---

## Success Metrics

### Bookings

- Bookings can be created for services
- Availability slots are properly generated
- Double-booking is prevented
- Payment integration works for paid bookings

### AI-Search

- Natural language queries return relevant results
- Geolocation works for "near me" queries
- Ranking algorithm prioritizes verified, high-rated businesses
- Search performance is acceptable (< 500ms)

### Reviews

- Only eligible customers can leave reviews
- Reviews appear on business profiles
- Rating cache is updated correctly
- Seller can respond to reviews

---

## Notes

- All features follow existing domain structure
- Livewire 4 patterns are used throughout
- Theme views are updated for all 4 themes
- Mobile-first responsive design
- Security: authorization checks, input validation, CSRF protection
