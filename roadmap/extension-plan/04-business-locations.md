# 04 — Business Locations (Multi-Branch)

> Support multiple branches per business. Enable "near me" search.

## What Already Exists

- Business has `address`, `city`, `state`, `country`, `latitude`, `longitude` fields
- Single location only — no multi-branch support

## What This Phase Adds

### 1. Business Locations Table

```sql
CREATE TABLE business_locations (
    id              BIGSERIAL PRIMARY KEY,
    business_id     BIGINT REFERENCES businesses(id) ON DELETE CASCADE,

    country         VARCHAR(100) NOT NULL,
    state           VARCHAR(100) NOT NULL,
    city            VARCHAR(100) NOT NULL,
    address         TEXT NOT NULL,
    postal_code     VARCHAR(20),

    latitude        DECIMAL(10, 8) NOT NULL,
    longitude       DECIMAL(11, 8) NOT NULL,
    geohash         VARCHAR(12),

    phone           VARCHAR(20),
    email           VARCHAR(255),

    opening_hours   JSONB DEFAULT '[
        {"day":"monday","open":"08:00","close":"18:00","closed":false},
        {"day":"tuesday","open":"08:00","close":"18:00","closed":false},
        {"day":"wednesday","open":"08:00","close":"18:00","closed":false},
        {"day":"thursday","open":"08:00","close":"18:00","closed":false},
        {"day":"friday","open":"08:00","close":"18:00","closed":false},
        {"day":"saturday","open":"09:00","close":"14:00","closed":false},
        {"day":"sunday","open":"00:00","close":"00:00","closed":true}
    ]',

    is_primary      BOOLEAN DEFAULT FALSE,
    is_headquarters BOOLEAN DEFAULT FALSE,

    created_at      TIMESTAMP DEFAULT NOW(),
    updated_at      TIMESTAMP DEFAULT NOW()
);
```

### 2. Files to Create

- `database/migrations/xxxx_create_business_locations_table.php`
- `app/Models/BusinessLocation.php` — belongsTo Business
- `app/Domains/Business/Actions/CreateLocation.php`
- `app/Domains/Business/Actions/UpdateLocation.php`
- `app/Domains/Business/Actions/DeleteLocation.php`
- `app/Domains/Business/Actions/SetPrimaryLocation.php`
- `app/Domains/Business/DTOs/LocationData.php`
- `app/Domains/Business/Events/LocationCreated.php`
- `app/Domains/Business/Events/LocationUpdated.php`
- `app/Domains/Business/Events/LocationDeleted.php`
- `app/Domains/Business/Policies/LocationPolicy.php`
- `app/Domains/Business/Livewire/LocationManager.php`
- `resources/views/livewire/business/location-manager.blade.php`

### 3. Business Model Update

```php
// Add to Business.php
public function locations(): HasMany
{
    return $this->hasMany(BusinessLocation::class);
}

public function primaryLocation()
{
    return $this->hasOne(BusinessLocation::class)->where('is_primary', true);
}
```

### 4. Auto-Create Primary Location

In `InitializeBusinessSettings`, after creating website:
```php
// Create primary location from business address fields
if ($business->address || $business->city) {
    $business->locations()->create([
        'country' => $business->country ?? 'Nigeria',
        'state' => $business->state ?? '',
        'city' => $business->city ?? '',
        'address' => $business->address ?? '',
        'latitude' => $business->latitude ?? 0,
        'longitude' => $business->longitude ?? 0,
        'phone' => $business->phone,
        'email' => $business->email,
        'is_primary' => true,
        'is_headquarters' => true,
    ]);
}
```

### 5. Routes

- `/dashboard/locations` — manage locations
- `/{slug}/locations` — public locations list

---

## Checklist

- [ ] Migration: `business_locations` table
- [ ] Model: `BusinessLocation`
- [ ] Add `locations()` and `primaryLocation()` to Business model
- [ ] Actions: CreateLocation, UpdateLocation, DeleteLocation, SetPrimaryLocation
- [ ] DTO: LocationData
- [ ] Events: LocationCreated, LocationUpdated, LocationDeleted
- [ ] Policy: LocationPolicy
- [ ] Livewire: LocationManager
- [ ] View: location-manager.blade.php
- [ ] Routes: `/dashboard/locations`, `/{slug}/locations`
- [ ] Auto-create primary location in InitializeBusinessSettings
