# 06 — Bookings & Appointments

> Service businesses need appointment scheduling. Customers book online, businesses manage calendars.

## What Already Exists

- Services have `duration_minutes` field (from existing migration)
- Services have `price` and `price_type` fields
- Paystack payment integration exists
- Notification system exists

## What This Phase Adds

### 1. Tables

```sql
CREATE TABLE booking_slots (
    id              BIGSERIAL PRIMARY KEY,
    business_id     BIGINT REFERENCES businesses(id) ON DELETE CASCADE,
    service_id      BIGINT REFERENCES services(id) ON DELETE CASCADE,
    location_id     BIGINT REFERENCES business_locations(id) ON DELETE SET NULL,

    day_of_week     INTEGER NOT NULL,          -- 0=Sunday, 1=Monday...
    start_time      TIME NOT NULL,
    end_time        TIME NOT NULL,
    slot_duration   INTEGER DEFAULT 30,         -- minutes
    max_bookings_per_slot INTEGER DEFAULT 1,

    is_active       BOOLEAN DEFAULT TRUE,
    created_at      TIMESTAMP DEFAULT NOW()
);

CREATE TABLE bookings (
    id              BIGSERIAL PRIMARY KEY,
    business_id     BIGINT REFERENCES businesses(id) ON DELETE CASCADE,
    service_id      BIGINT REFERENCES services(id) ON DELETE SET NULL,
    location_id     BIGINT REFERENCES business_locations(id) ON DELETE SET NULL,

    customer_id     BIGINT REFERENCES users(id) ON DELETE SET NULL,
    customer_name   VARCHAR(255) NOT NULL,
    customer_email  VARCHAR(255),
    customer_phone  VARCHAR(20) NOT NULL,

    booking_date    DATE NOT NULL,
    start_time      TIME NOT NULL,
    end_time        TIME NOT NULL,
    timezone        VARCHAR(50) DEFAULT 'Africa/Lagos',

    status          ENUM('pending','confirmed','completed','cancelled','no_show') DEFAULT 'pending',

    payment_status  ENUM('pending','paid','refunded','failed') DEFAULT 'pending',
    payment_amount  DECIMAL(12, 2),

    customer_notes  TEXT,
    business_notes  TEXT,

    reminder_sent   BOOLEAN DEFAULT FALSE,

    created_at      TIMESTAMP DEFAULT NOW(),
    updated_at      TIMESTAMP DEFAULT NOW()
);
```

### 2. Files to Create

- Migrations for both tables
- Models: BookingSlot, Booking
- Actions: CreateBooking, ConfirmBooking, CancelBooking, CompleteBooking, CreateBookingSlots
- DTOs: BookingData, BookingSlotData
- Events: BookingCreated, BookingConfirmed, BookingCancelled, BookingCompleted
- Listeners: NotifyBusinessOwner, SendCustomerConfirmation, UpdateBusinessAnalytics
- Job: SendBookingReminder (queued)
- Policy: BookingPolicy
- Livewire: BookingManager (merchant), BookAppointment (customer)
- Views: booking-manager.blade.php, book-appointment.blade.php

### 3. Event Wiring

```php
BookingCreated::class => [
    NotifyBusinessOwner::class,
    SendCustomerConfirmation::class,
    UpdateBusinessAnalytics::class,
],
```

### 4. Service Model Update

Add `requires_booking` and `booking_buffer` fields (from proc2.md schema):
```php
// Add to services table migration or via alter migration
$table->boolean('requires_booking')->default(false);
$table->integer('booking_buffer')->default(0); // minutes between appointments
```

### 5. Routes

- `/dashboard/bookings` — merchant booking management
- `/{slug}/book` — public booking page
- `/{slug}/book/{service}` — book specific service

---

## Checklist

- [ ] Migration: `booking_slots` table
- [ ] Migration: `bookings` table
- [ ] Alter services: add `requires_booking`, `booking_buffer`
- [ ] Models: BookingSlot, Booking
- [ ] Actions: CreateBooking, ConfirmBooking, CancelBooking, CompleteBooking, CreateBookingSlots
- [ ] DTOs: BookingData, BookingSlotData
- [ ] Events + Listeners (wired in EventServiceProvider)
- [ ] Job: SendBookingReminder
- [ ] Policy: BookingPolicy
- [ ] Livewire: BookingManager, BookAppointment
- [ ] Routes: `/dashboard/bookings`, `/{slug}/book`
