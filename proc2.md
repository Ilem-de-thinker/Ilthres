
# Create the comprehensive backend architecture document
backend_doc = """# OmniBusiness — Backend Architecture & Database Schema

## Philosophy

**Business is the primary object. Everything else is a view.**

The website, discovery profile, mobile app, and API are all different presentations of the same business data. Do not duplicate data between "website" and "business profile." Store data once, render it everywhere.

---

## Core Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                        CUSTOMERS                              │
│  (Search, Discover, Book, Review, Message, Buy)               │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                    DISCOVERY PLATFORM                         │
│  (Web: Laravel + Livewire + Tailwind + Alpine)              │
│  (Mobile: .NET MAUI consuming API)                          │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                      LARAVEL BACKEND                          │
│  ┌─────────┐  ┌─────────┐  ┌─────────┐  ┌─────────┐       │
│  │ Search  │  │  AI     │  │Payments │  │Marketing│       │
│  │Engine   │  │Services │  │Gateway  │  │Engine   │       │
│  └────┬────┘  └────┬────┘  └────┬────┘  └────┬────┘       │
│       └─────────────┴─────────────┴─────────────┘           │
│                          │                                  │
│                    ┌─────┴─────┐                          │
│                    │  Domain   │                          │
│                    │Middleware │                          │
│                    └─────┬─────┘                          │
└──────────────────────────┼────────────────────────────────┘
                           │
              ┌────────────┼────────────┐
              ▼            ▼            ▼
        ┌─────────┐  ┌─────────┐  ┌─────────┐
        │PostgreSQL│  │  Redis  │  │Meilisearch│
        │(Primary)│  │ (Cache) │  │ (Search)  │
        └─────────┘  └─────────┘  └─────────┘
              │            │            │
              └────────────┴────────────┘
                           │
                           ▼
              ┌─────────────────────────┐
              │    S3 / Cloud Storage   │
              │   (Images, Assets, AI   │
              │    Generated Content)   │
              └─────────────────────────┘
```

---

## Database Schema

### 1. USERS & AUTHENTICATION

```sql
-- users (Platform users: customers + business owners)
CREATE TABLE users (
    id              BIGSERIAL PRIMARY KEY,
    name            VARCHAR(255) NOT NULL,
    email           VARCHAR(255) UNIQUE NOT NULL,
    phone           VARCHAR(20) UNIQUE,
    password        VARCHAR(255) NOT NULL,
    avatar          VARCHAR(255),
    email_verified_at TIMESTAMP,
    phone_verified_at TIMESTAMP,
    role            ENUM('customer', 'business_owner', 'admin', 'moderator') DEFAULT 'customer',
    status          ENUM('active', 'suspended', 'banned') DEFAULT 'active',
    created_at      TIMESTAMP DEFAULT NOW(),
    updated_at      TIMESTAMP DEFAULT NOW()
);

-- user_sessions (For security & analytics)
CREATE TABLE user_sessions (
    id              BIGSERIAL PRIMARY KEY,
    user_id         BIGINT REFERENCES users(id) ON DELETE CASCADE,
    ip_address      INET,
    user_agent      TEXT,
    device_type     VARCHAR(50),
    location        VARCHAR(255),
    last_active     TIMESTAMP DEFAULT NOW(),
    created_at      TIMESTAMP DEFAULT NOW()
);
```

---

### 2. BUSINESSES (The Heart of the System)

```sql
-- businesses
CREATE TABLE businesses (
    id              BIGSERIAL PRIMARY KEY,
    owner_id        BIGINT REFERENCES users(id) ON DELETE CASCADE,
    
    -- Identity
    name            VARCHAR(255) NOT NULL,
    slug            VARCHAR(255) UNIQUE NOT NULL,  -- URL-friendly: johns-bakery
    tagline         VARCHAR(255),
    description     TEXT,
    
    -- Branding
    logo            VARCHAR(255),
    cover_image     VARCHAR(255),
    favicon         VARCHAR(255),
    brand_colors    JSONB,  -- {"primary": "#FF6B35", "secondary": "#004E89"}
    
    -- Contact
    email           VARCHAR(255),
    phone           VARCHAR(20),
    whatsapp        VARCHAR(20),
    website_url     VARCHAR(255),  -- External website if they have one
    
    -- Categorization
    category_id     BIGINT REFERENCES business_categories(id),
    subcategory_id  BIGINT REFERENCES business_subcategories(id),
    tags            TEXT[],  -- Array of tags for search
    
    -- Verification & Status
    verified        BOOLEAN DEFAULT FALSE,
    verification_doc VARCHAR(255),  -- CAC, business license, etc.
    status          ENUM('draft', 'pending', 'active', 'suspended', 'inactive') DEFAULT 'draft',
    
    -- AI-Generated Content
    ai_summary      TEXT,  -- AI-written business description
    seo_keywords    TEXT[],
    
    -- Trust & Performance
    trust_score     DECIMAL(3,2) DEFAULT 0.00,  -- 0.00 to 5.00
    response_rate   DECIMAL(5,2) DEFAULT 0.00,  -- Percentage
    response_time   INTEGER DEFAULT 0,  -- Average minutes
    
    -- Plan & Billing
    plan_id         BIGINT REFERENCES plans(id),
    plan_expires_at TIMESTAMP,
    
    -- Analytics
    views_count     BIGINT DEFAULT 0,
    leads_count     BIGINT DEFAULT 0,
    bookings_count  BIGINT DEFAULT 0,
    orders_count    BIGINT DEFAULT 0,
    
    created_at      TIMESTAMP DEFAULT NOW(),
    updated_at      TIMESTAMP DEFAULT NOW()
);

-- business_categories (Restaurant, Hotel, etc.)
CREATE TABLE business_categories (
    id              BIGSERIAL PRIMARY KEY,
    name            VARCHAR(100) NOT NULL,
    slug            VARCHAR(100) UNIQUE NOT NULL,
    icon            VARCHAR(100),
    description     TEXT,
    parent_id       BIGINT REFERENCES business_categories(id),  -- For nested categories
    is_active       BOOLEAN DEFAULT TRUE,
    sort_order      INTEGER DEFAULT 0,
    created_at      TIMESTAMP DEFAULT NOW()
);

-- business_subcategories
CREATE TABLE business_subcategories (
    id              BIGSERIAL PRIMARY KEY,
    category_id     BIGINT REFERENCES business_categories(id) ON DELETE CASCADE,
    name            VARCHAR(100) NOT NULL,
    slug            VARCHAR(100) NOT NULL,
    UNIQUE(category_id, slug)
);
```

---

### 3. BUSINESS LOCATIONS (Multi-branch support)

```sql
-- business_locations
CREATE TABLE business_locations (
    id              BIGSERIAL PRIMARY KEY,
    business_id     BIGINT REFERENCES businesses(id) ON DELETE CASCADE,
    
    -- Address
    country         VARCHAR(100) NOT NULL,
    state           VARCHAR(100) NOT NULL,
    city            VARCHAR(100) NOT NULL,
    address         TEXT NOT NULL,
    postal_code     VARCHAR(20),
    
    -- Geolocation (For "near me" search)
    latitude        DECIMAL(10, 8) NOT NULL,
    longitude       DECIMAL(11, 8) NOT NULL,
    geohash         VARCHAR(12),  -- For fast geo queries
    
    -- Contact (Can override business-level contact)
    phone           VARCHAR(20),
    email           VARCHAR(255),
    
    -- Operating Hours (JSON for flexibility)
    opening_hours   JSONB DEFAULT '[
        {"day": "monday", "open": "08:00", "close": "18:00", "closed": false},
        {"day": "tuesday", "open": "08:00", "close": "18:00", "closed": false}
    ]',
    
    -- Flags
    is_primary      BOOLEAN DEFAULT FALSE,  -- Main branch
    is_headquarters BOOLEAN DEFAULT FALSE,
    
    created_at      TIMESTAMP DEFAULT NOW(),
    updated_at      TIMESTAMP DEFAULT NOW()
);

-- Index for geo search
CREATE INDEX idx_locations_geo ON business_locations USING GIST (
    point(longitude, latitude)
);
```

---

### 4. WEBSITES & THEMES (The Presentation Layer)

```sql
-- themes (Pre-built templates)
CREATE TABLE themes (
    id              BIGSERIAL PRIMARY KEY,
    name            VARCHAR(100) NOT NULL,
    slug            VARCHAR(100) UNIQUE NOT NULL,
    industry        VARCHAR(50),  -- restaurant, medical, corporate, etc.
    description     TEXT,
    preview_image   VARCHAR(255),
    
    -- Theme config (sections available, color schemes, etc.)
    config          JSONB DEFAULT '{
        "sections": ["hero", "about", "services", "products", "gallery", "testimonials", "contact", "map"],
        "color_schemes": ["default", "dark", "light"],
        "supports_store": false,
        "supports_booking": false
    }',
    
    is_premium      BOOLEAN DEFAULT FALSE,
    is_active       BOOLEAN DEFAULT TRUE,
    created_at      TIMESTAMP DEFAULT NOW()
);

-- websites (One per business)
CREATE TABLE websites (
    id              BIGSERIAL PRIMARY KEY,
    business_id     BIGINT REFERENCES businesses(id) ON DELETE CASCADE,
    theme_id        BIGINT REFERENCES themes(id),
    
    -- Domain Configuration
    subdomain       VARCHAR(100) UNIQUE,  -- johns-bakery
    custom_domain   VARCHAR(255) UNIQUE,  -- johnsbakery.com
    domain_status   ENUM('pending', 'active', 'expired', 'suspended') DEFAULT 'pending',
    ssl_enabled     BOOLEAN DEFAULT FALSE,
    ssl_expires_at  TIMESTAMP,
    
    -- Publishing
    is_published    BOOLEAN DEFAULT FALSE,
    published_at    TIMESTAMP,
    
    -- SEO
    seo_title       VARCHAR(255),
    seo_description TEXT,
    seo_keywords    TEXT[],
    og_image        VARCHAR(255),  -- Open Graph image
    
    -- Analytics
    visits_count    BIGINT DEFAULT 0,
    unique_visitors BIGINT DEFAULT 0,
    
    created_at      TIMESTAMP DEFAULT NOW(),
    updated_at      TIMESTAMP DEFAULT NOW()
);

-- website_pages (Dynamic pages: Home, About, Services, etc.)
CREATE TABLE website_pages (
    id              BIGSERIAL PRIMARY KEY,
    website_id      BIGINT REFERENCES websites(id) ON DELETE CASCADE,
    slug            VARCHAR(100) NOT NULL,  -- home, about, services, contact
    title           VARCHAR(255) NOT NULL,
    meta_description TEXT,
    is_visible      BOOLEAN DEFAULT TRUE,
    sort_order      INTEGER DEFAULT 0,
    UNIQUE(website_id, slug)
);

-- website_sections (The actual content blocks)
CREATE TABLE website_sections (
    id              BIGSERIAL PRIMARY KEY,
    page_id         BIGINT REFERENCES website_pages(id) ON DELETE CASCADE,
    type            VARCHAR(50) NOT NULL,  -- hero, about, services, products, gallery, testimonials, contact, map, faq, cta
    
    -- Structured content (JSON schema depends on type)
    content         JSONB NOT NULL,
    
    -- Example content for "hero" type:
    -- {
    --   "heading": "Welcome to John's Bakery",
    --   "subheading": "Fresh bread daily since 1995",
    --   "background_image": "...",
    --   "cta_text": "Order Now",
    --   "cta_link": "#order"
    -- }
    
    sort_order      INTEGER DEFAULT 0,
    is_visible      BOOLEAN DEFAULT TRUE,
    created_at      TIMESTAMP DEFAULT NOW(),
    updated_at      TIMESTAMP DEFAULT NOW()
);
```

---

### 5. PRODUCTS & SERVICES

```sql
-- product_categories (Per business)
CREATE TABLE product_categories (
    id              BIGSERIAL PRIMARY KEY,
    business_id     BIGINT REFERENCES businesses(id) ON DELETE CASCADE,
    name            VARCHAR(100) NOT NULL,
    slug            VARCHAR(100) NOT NULL,
    description     TEXT,
    image           VARCHAR(255),
    sort_order      INTEGER DEFAULT 0,
    UNIQUE(business_id, slug)
);

-- products
CREATE TABLE products (
    id              BIGSERIAL PRIMARY KEY,
    business_id     BIGINT REFERENCES businesses(id) ON DELETE CASCADE,
    category_id     BIGINT REFERENCES product_categories(id) ON DELETE SET NULL,
    
    name            VARCHAR(255) NOT NULL,
    slug            VARCHAR(255) NOT NULL,
    description     TEXT,
    short_description VARCHAR(500),
    
    -- Pricing
    price           DECIMAL(12, 2) NOT NULL,
    compare_price   DECIMAL(12, 2),  -- Original price for "sale"
    currency        VARCHAR(3) DEFAULT 'NGN',
    
    -- Inventory
    sku             VARCHAR(100),
    stock_quantity  INTEGER DEFAULT 0,
    track_inventory BOOLEAN DEFAULT TRUE,
    allow_backorder BOOLEAN DEFAULT FALSE,
    
    -- Media
    images          JSONB,  -- [{"url": "...", "alt": "..."}]
    
    -- Status
    status          ENUM('draft', 'active', 'out_of_stock', 'discontinued') DEFAULT 'draft',
    is_featured     BOOLEAN DEFAULT FALSE,
    
    -- SEO
    seo_title       VARCHAR(255),
    seo_description TEXT,
    
    -- Analytics
    views_count     BIGINT DEFAULT 0,
    sales_count     BIGINT DEFAULT 0,
    
    created_at      TIMESTAMP DEFAULT NOW(),
    updated_at      TIMESTAMP DEFAULT NOW(),
    
    UNIQUE(business_id, slug)
);

-- services (Non-inventory offerings)
CREATE TABLE services (
    id              BIGSERIAL PRIMARY KEY,
    business_id     BIGINT REFERENCES businesses(id) ON DELETE CASCADE,
    
    name            VARCHAR(255) NOT NULL,
    slug            VARCHAR(255) NOT NULL,
    description     TEXT,
    
    -- Pricing
    price           DECIMAL(12, 2),
    price_type      ENUM('fixed', 'hourly', 'quote', 'free') DEFAULT 'fixed',
    duration        INTEGER,  -- Minutes (for bookings)
    
    -- Media
    image           VARCHAR(255),
    gallery         JSONB,
    
    -- Booking
    requires_booking BOOLEAN DEFAULT FALSE,
    booking_buffer  INTEGER DEFAULT 0,  -- Minutes between appointments
    
    status          ENUM('active', 'inactive') DEFAULT 'active',
    sort_order      INTEGER DEFAULT 0,
    
    created_at      TIMESTAMP DEFAULT NOW(),
    updated_at      TIMESTAMP DEFAULT NOW(),
    
    UNIQUE(business_id, slug)
);
```

---

### 6. BOOKINGS & APPOINTMENTS

```sql
-- booking_slots (Availability templates)
CREATE TABLE booking_slots (
    id              BIGSERIAL PRIMARY KEY,
    business_id     BIGINT REFERENCES businesses(id) ON DELETE CASCADE,
    service_id      BIGINT REFERENCES services(id) ON DELETE CASCADE,
    location_id     BIGINT REFERENCES business_locations(id) ON DELETE CASCADE,
    
    day_of_week     INTEGER NOT NULL,  -- 0=Sunday, 1=Monday
    start_time      TIME NOT NULL,
    end_time        TIME NOT NULL,
    slot_duration   INTEGER DEFAULT 30,  -- Minutes
    max_bookings_per_slot INTEGER DEFAULT 1,
    
    is_active       BOOLEAN DEFAULT TRUE,
    created_at      TIMESTAMP DEFAULT NOW()
);

-- bookings
CREATE TABLE bookings (
    id              BIGSERIAL PRIMARY KEY,
    business_id     BIGINT REFERENCES businesses(id) ON DELETE CASCADE,
    service_id      BIGINT REFERENCES services(id) ON DELETE SET NULL,
    location_id     BIGINT REFERENCES business_locations(id) ON DELETE SET NULL,
    
    -- Customer info (Can be registered user or guest)
    customer_id     BIGINT REFERENCES users(id) ON DELETE SET NULL,
    customer_name   VARCHAR(255) NOT NULL,
    customer_email  VARCHAR(255),
    customer_phone  VARCHAR(20) NOT NULL,
    
    -- Booking details
    booking_date    DATE NOT NULL,
    start_time      TIME NOT NULL,
    end_time        TIME NOT NULL,
    timezone        VARCHAR(50) DEFAULT 'Africa/Lagos',
    
    -- Status
    status          ENUM('pending', 'confirmed', 'completed', 'cancelled', 'no_show') DEFAULT 'pending',
    
    -- Payment
    payment_status  ENUM('pending', 'paid', 'refunded', 'failed') DEFAULT 'pending',
    payment_amount  DECIMAL(12, 2),
    
    -- Notes
    customer_notes  TEXT,
    business_notes  TEXT,
    
    -- Reminders
    reminder_sent   BOOLEAN DEFAULT FALSE,
    
    created_at      TIMESTAMP DEFAULT NOW(),
    updated_at      TIMESTAMP DEFAULT NOW()
);
```

---

### 7. REVIEWS & REPUTATION

```sql
-- reviews
CREATE TABLE reviews (
    id              BIGSERIAL PRIMARY KEY,
    business_id     BIGINT REFERENCES businesses(id) ON DELETE CASCADE,
    user_id         BIGINT REFERENCES users(id) ON DELETE SET NULL,
    booking_id      BIGINT REFERENCES bookings(id) ON DELETE SET NULL,  -- Verified purchase
    
    -- Rating
    rating          INTEGER NOT NULL CHECK (rating >= 1 AND rating <= 5),
    title           VARCHAR(255),
    body            TEXT,
    
    -- Media
    photos          JSONB,  -- [{"url": "...", "caption": "..."}]
    
    -- Response from business
    business_response TEXT,
    responded_at    TIMESTAMP,
    
    -- Moderation
    status          ENUM('pending', 'approved', 'rejected', 'flagged') DEFAULT 'pending',
    helpful_count   INTEGER DEFAULT 0,
    
    created_at      TIMESTAMP DEFAULT NOW(),
    updated_at      TIMESTAMP DEFAULT NOW()
);

-- review_votes (Helpful / Not helpful)
CREATE TABLE review_votes (
    id              BIGSERIAL PRIMARY KEY,
    review_id       BIGINT REFERENCES reviews(id) ON DELETE CASCADE,
    user_id         BIGINT REFERENCES users(id) ON DELETE CASCADE,
    is_helpful      BOOLEAN,
    UNIQUE(review_id, user_id)
);
```

---

### 8. MESSAGING & COMMUNICATION

```sql
-- conversations (Chat threads between customer and business)
CREATE TABLE conversations (
    id              BIGSERIAL PRIMARY KEY,
    business_id     BIGINT REFERENCES businesses(id) ON DELETE CASCADE,
    customer_id     BIGINT REFERENCES users(id) ON DELETE CASCADE,
    
    -- Context
    source          ENUM('website', 'whatsapp', 'booking', 'review', 'direct') DEFAULT 'direct',
    source_id       BIGINT,  -- ID of booking, review, etc.
    
    -- Status
    status          ENUM('open', 'closed', 'archived') DEFAULT 'open',
    priority        ENUM('low', 'normal', 'high', 'urgent') DEFAULT 'normal',
    
    -- Timestamps
    last_message_at TIMESTAMP,
    created_at      TIMESTAMP DEFAULT NOW(),
    updated_at      TIMESTAMP DEFAULT NOW()
);

-- messages
CREATE TABLE messages (
    id              BIGSERIAL PRIMARY KEY,
    conversation_id BIGINT REFERENCES conversations(id) ON DELETE CASCADE,
    sender_id       BIGINT REFERENCES users(id) ON DELETE SET NULL,
    sender_type     ENUM('customer', 'business_owner', 'system', 'ai') NOT NULL,
    
    content         TEXT NOT NULL,
    attachments     JSONB,  -- [{"type": "image", "url": "..."}]
    
    -- Status
    read_at         TIMESTAMP,
    delivered_at    TIMESTAMP,
    
    created_at      TIMESTAMP DEFAULT NOW()
);
```

---

### 9. ORDERS & PAYMENTS

```sql
-- orders
CREATE TABLE orders (
    id              BIGSERIAL PRIMARY KEY,
    business_id     BIGINT REFERENCES businesses(id) ON DELETE CASCADE,
    customer_id     BIGINT REFERENCES users(id) ON DELETE SET NULL,
    
    -- Customer info (for guest checkout)
    customer_name   VARCHAR(255),
    customer_email  VARCHAR(255),
    customer_phone  VARCHAR(20),
    shipping_address JSONB,
    
    -- Order details
    order_number    VARCHAR(50) UNIQUE NOT NULL,  -- ORD-2024-000001
    status          ENUM('pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded') DEFAULT 'pending',
    
    -- Financials
    subtotal        DECIMAL(12, 2) NOT NULL,
    tax_amount      DECIMAL(12, 2) DEFAULT 0,
    shipping_amount DECIMAL(12, 2) DEFAULT 0,
    discount_amount DECIMAL(12, 2) DEFAULT 0,
    total           DECIMAL(12, 2) NOT NULL,
    currency        VARCHAR(3) DEFAULT 'NGN',
    
    -- Payment
    payment_method  VARCHAR(50),  -- paystack, flutterwave, bank_transfer
    payment_status  ENUM('pending', 'paid', 'failed', 'refunded') DEFAULT 'pending',
    paid_at         TIMESTAMP,
    
    -- Delivery
    delivery_method ENUM('pickup', 'delivery', 'digital') DEFAULT 'pickup',
    tracking_number VARCHAR(100),
    
    notes           TEXT,
    created_at      TIMESTAMP DEFAULT NOW(),
    updated_at      TIMESTAMP DEFAULT NOW()
);

-- order_items
CREATE TABLE order_items (
    id              BIGSERIAL PRIMARY KEY,
    order_id        BIGINT REFERENCES orders(id) ON DELETE CASCADE,
    product_id      BIGINT REFERENCES products(id) ON DELETE SET NULL,
    
    product_name    VARCHAR(255) NOT NULL,  -- Snapshot at time of order
    product_price   DECIMAL(12, 2) NOT NULL,
    quantity        INTEGER NOT NULL,
    
    total           DECIMAL(12, 2) NOT NULL,
    
    created_at      TIMESTAMP DEFAULT NOW()
);

-- payments (Transaction log)
CREATE TABLE payments (
    id              BIGSERIAL PRIMARY KEY,
    order_id        BIGINT REFERENCES orders(id) ON DELETE CASCADE,
    business_id     BIGINT REFERENCES businesses(id) ON DELETE CASCADE,
    
    amount          DECIMAL(12, 2) NOT NULL,
    currency        VARCHAR(3) DEFAULT 'NGN',
    
    gateway         VARCHAR(50) NOT NULL,  -- paystack, flutterwave
    gateway_ref     VARCHAR(255),  -- External transaction reference
    gateway_response JSONB,
    
    status          ENUM('pending', 'success', 'failed', 'refunded') DEFAULT 'pending',
    
    paid_at         TIMESTAMP,
    created_at      TIMESTAMP DEFAULT NOW()
);
```

---

### 10. ANALYTICS & TRACKING

```sql
-- business_analytics (Daily aggregated stats)
CREATE TABLE business_analytics (
    id              BIGSERIAL PRIMARY KEY,
    business_id     BIGINT REFERENCES businesses(id) ON DELETE CASCADE,
    date            DATE NOT NULL,
    
    -- Traffic
    profile_views   INTEGER DEFAULT 0,
    website_visits  INTEGER DEFAULT 0,
    unique_visitors INTEGER DEFAULT 0,
    
    -- Engagement
    search_appeared INTEGER DEFAULT 0,  -- Times in search results
    search_clicked  INTEGER DEFAULT 0,   -- Times clicked from search
    calls_received  INTEGER DEFAULT 0,
    whatsapp_clicks INTEGER DEFAULT 0,
    email_clicks    INTEGER DEFAULT 0,
    direction_requests INTEGER DEFAULT 0,
    
    -- Conversions
    bookings_made   INTEGER DEFAULT 0,
    orders_placed   INTEGER DEFAULT 0,
    inquiries_received INTEGER DEFAULT 0,
    reviews_received INTEGER DEFAULT 0,
    
    -- Revenue (if applicable)
    revenue         DECIMAL(12, 2) DEFAULT 0,
    
    UNIQUE(business_id, date)
);

-- page_views (Raw tracking)
CREATE TABLE page_views (
    id              BIGSERIAL PRIMARY KEY,
    business_id     BIGINT REFERENCES businesses(id) ON DELETE CASCADE,
    website_id      BIGINT REFERENCES websites(id) ON DELETE SET NULL,
    
    visitor_ip      INET,
    user_agent      TEXT,
    page_url        TEXT,
    referrer        TEXT,
    
    session_id      VARCHAR(100),
    user_id         BIGINT REFERENCES users(id) ON DELETE SET NULL,
    
    created_at      TIMESTAMP DEFAULT NOW()
);
```

---

### 11. SUBSCRIPTIONS & BILLING

```sql
-- plans (Free, Pro, Enterprise)
CREATE TABLE plans (
    id              BIGSERIAL PRIMARY KEY,
    name            VARCHAR(100) NOT NULL,
    slug            VARCHAR(100) UNIQUE NOT NULL,
    description     TEXT,
    
    -- Pricing
    price_monthly   DECIMAL(12, 2),
    price_yearly    DECIMAL(12, 2),
    currency        VARCHAR(3) DEFAULT 'NGN',
    
    -- Features (JSON for flexibility)
    features        JSONB DEFAULT '{
        "max_locations": 1,
        "max_products": 10,
        "max_services": 10,
        "custom_domain": false,
        "ai_website": true,
        "online_store": false,
        "booking_system": false,
        "analytics": "basic",
        "marketing_tools": false,
        "priority_support": false
    }',
    
    -- Display
    is_popular      BOOLEAN DEFAULT FALSE,
    is_active       BOOLEAN DEFAULT TRUE,
    sort_order      INTEGER DEFAULT 0,
    
    created_at      TIMESTAMP DEFAULT NOW()
);

-- subscriptions
CREATE TABLE subscriptions (
    id              BIGSERIAL PRIMARY KEY,
    business_id     BIGINT REFERENCES businesses(id) ON DELETE CASCADE,
    plan_id         BIGINT REFERENCES plans(id),
    
    -- Billing cycle
    billing_cycle   ENUM('monthly', 'yearly') DEFAULT 'monthly',
    starts_at     TIMESTAMP NOT NULL,
    ends_at       TIMESTAMP NOT NULL,
    
    -- Status
    status          ENUM('active', 'cancelled', 'expired', 'past_due') DEFAULT 'active',
    auto_renew      BOOLEAN DEFAULT TRUE,
    
    -- Payment
    last_payment_at TIMESTAMP,
    next_payment_at TIMESTAMP,
    
    created_at      TIMESTAMP DEFAULT NOW(),
    updated_at      TIMESTAMP DEFAULT NOW()
);
```

---

### 12. AI GENERATED CONTENT

```sql
-- ai_generations (Log of AI-created content)
CREATE TABLE ai_generations (
    id              BIGSERIAL PRIMARY KEY,
    business_id     BIGINT REFERENCES businesses(id) ON DELETE CASCADE,
    user_id         BIGINT REFERENCES users(id) ON DELETE SET NULL,
    
    type            VARCHAR(50) NOT NULL,  -- website, description, seo, social_post, ad, email
    prompt          TEXT,
    
    -- Generated content
    content         TEXT,
    content_type    VARCHAR(50),  -- text, html, json
    
    -- AI metadata
    model_used      VARCHAR(50),  -- gpt-4, claude, etc.
    tokens_used     INTEGER,
    cost            DECIMAL(10, 6),  -- In USD
    
    -- Status
    status          ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
    error_message   TEXT,
    
    created_at      TIMESTAMP DEFAULT NOW()
);

-- ai_prompts (Reusable prompt templates)
CREATE TABLE ai_prompts (
    id              BIGSERIAL PRIMARY KEY,
    name            VARCHAR(100) NOT NULL,
    type            VARCHAR(50) NOT NULL,
    template        TEXT NOT NULL,
    variables       JSONB,  -- ["business_name", "category", "location"]
    
    is_active       BOOLEAN DEFAULT TRUE,
    created_at      TIMESTAMP DEFAULT NOW()
);
```

---

### 13. MARKETING & PROMOTIONS

```sql
-- campaigns (Email, SMS, push notification campaigns)
CREATE TABLE campaigns (
    id              BIGSERIAL PRIMARY KEY,
    business_id     BIGINT REFERENCES businesses(id) ON DELETE CASCADE,
    
    name            VARCHAR(255) NOT NULL,
    type            ENUM('email', 'sms', 'push', 'social') NOT NULL,
    
    -- Content
    subject         VARCHAR(255),
    body            TEXT,
    
    -- Targeting
    target_audience JSONB,  -- {"segment": "all_customers", "location_radius": 10}
    
    -- Scheduling
    scheduled_at    TIMESTAMP,
    sent_at         TIMESTAMP,
    
    -- Stats
    recipients_count INTEGER DEFAULT 0,
    opened_count    INTEGER DEFAULT 0,
    clicked_count   INTEGER DEFAULT 0,
    
    status          ENUM('draft', 'scheduled', 'sending', 'sent', 'cancelled') DEFAULT 'draft',
    
    created_at      TIMESTAMP DEFAULT NOW(),
    updated_at      TIMESTAMP DEFAULT NOW()
);

-- coupons & discounts
CREATE TABLE coupons (
    id              BIGSERIAL PRIMARY KEY,
    business_id     BIGINT REFERENCES businesses(id) ON DELETE CASCADE,
    
    code            VARCHAR(50) NOT NULL,
    description     TEXT,
    
    -- Discount
    discount_type   ENUM('percentage', 'fixed_amount', 'free_shipping') DEFAULT 'percentage',
    discount_value  DECIMAL(12, 2) NOT NULL,
    
    -- Limits
    max_uses        INTEGER,  -- NULL = unlimited
    max_uses_per_customer INTEGER DEFAULT 1,
    min_order_amount DECIMAL(12, 2),
    
    -- Validity
    starts_at       TIMESTAMP,
    ends_at         TIMESTAMP,
    
    is_active       BOOLEAN DEFAULT TRUE,
    created_at      TIMESTAMP DEFAULT NOW(),
    
    <!-- UNIQUE(business_id, code) -->
);
```

---

### 14. MEDIA & ASSETS

```sql
-- media (Centralized file storage)
CREATE TABLE media (
    id              BIGSERIAL PRIMARY KEY,
    business_id     BIGINT REFERENCES businesses(id) ON DELETE CASCADE,
    uploaded_by     BIGINT REFERENCES users(id) ON DELETE SET NULL,
    
    filename        VARCHAR(255) NOT NULL,
    original_name   VARCHAR(255),
    mime_type       VARCHAR(100),
    size_bytes      BIGINT,
    
    -- Storage
    disk            VARCHAR(50) DEFAULT 's3',  -- s3, local, cloudflare_r2
    path            VARCHAR(500) NOT NULL,
    url             VARCHAR(500) NOT NULL,
    
    -- Variants (thumbnails, webp, etc.)
    variants        JSONB,  -- {"thumbnail": "...", "medium": "...", "webp": "..."}
    
    -- Metadata
    alt_text        VARCHAR(255),
    caption         TEXT,
    
    -- Usage tracking
    used_in         JSONB DEFAULT '[]',  -- ["website", "products", "gallery"]
    
    created_at      TIMESTAMP DEFAULT NOW()
);
```

---

## Domain Resolution Middleware

```php
<?php
// app/Http/Middleware/ResolveBusinessDomain.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Website;
use Symfony\Component\HttpFoundation\Response;

class ResolveBusinessDomain
{
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $baseDomain = config('app.base_domain'); // omnibusiness.com
        
        $website = null;
        
        // 1. Check custom domain (e.g., johnsbakery.com)
        if ($host !== $baseDomain && !str_ends_with($host, '.' . $baseDomain)) {
            $website = Website::where('custom_domain', $host)
                ->where('domain_status', 'active')
                ->with('business')
                ->first();
        }
        // 2. Check subdomain (e.g., johns-bakery.omnibusiness.com)
        elseif (str_ends_with($host, '.' . $baseDomain)) {
            $subdomain = str_replace('.' . $baseDomain, '', $host);
            $website = Website::where('subdomain', $subdomain)
                ->with('business')
                ->first();
        }
        // 3. Check path-based (e.g., omnibusiness.com/b/johns-bakery)
        elseif ($request->segment(1) === 'b') {
            $slug = $request->segment(2);
            $website = Website::whereHas('business', function ($q) use ($slug) {
                $q->where('slug', $slug);
            })->with('business')->first();
        }
        
        if ($website && $website->business) {
            // Share business instance across the request
            app()->instance('current_business', $website->business);
            app()->instance('current_website', $website);
            
            // Add to request attributes for easy access
            $request->attributes->set('business', $website->business);
            $request->attributes->set('website', $website);
        }
        
        return $next($request);
    }
}
```

### Helper Functions

```php
<?php
// app/Helpers/BusinessHelper.php

if (!function_exists('current_business')) {
    function current_business() {
        return app('current_business');
    }
}

if (!function_exists('current_website')) {
    function current_website() {
        return app('current_website');
    }
}

if (!function_exists('is_custom_domain')) {
    function is_custom_domain() {
        $website = current_website();
        return $website && request()->getHost() === $website->custom_domain;
    }
}
```

---

## Theme Engine

```php
<?php
// app/Services/ThemeEngine.php

namespace App\Services;

use App\Models\Business;
use App\Models\Website;
use Illuminate\Support\Facades\View;

class ThemeEngine
{
    public function render(Website $website, string $pageSlug = 'home')
    {
        $business = $website->business;
        $theme = $website->theme;
        
        // Load all business data
        $data = [
            'business' => $business,
            'website' => $website,
            'theme' => $theme,
            'locations' => $business->locations,
            'products' => $business->products()->active()->get(),
            'services' => $business->services()->active()->get(),
            'reviews' => $business->reviews()->approved()->latest()->limit(10)->get(),
            'gallery' => $business->gallery,
        ];
        
        // Load page sections
        $page = $website->pages()->where('slug', $pageSlug)->first();
        $sections = $page ? $page->sections()->visible()->ordered()->get() : [];
        
        // Render theme
        $themePath = "themes.{$theme->slug}";
        
        return View::make("{$themePath}.layout", compact('data', 'sections'));
    }
}
```

### Theme Blade Structure

```
resources/views/themes/
├── default/                    # Fallback theme
│   ├── layout.blade.php        # Main HTML wrapper
│   ├── components/
│   │   ├── hero.blade.php
│   │   ├── about.blade.php
│   │   ├── services.blade.php
│   │   ├── products.blade.php
│   │   ├── gallery.blade.php
│   │   ├── testimonials.blade.php
│   │   ├── contact.blade.php
│   │   └── map.blade.php
│   └── partials/
│       ├── header.blade.php
│       └── footer.blade.php
├── restaurant/
├── medical/
├── corporate/
└── luxury/
```

---

## AI Website Generation Service

```php
<?php
// app/Services/AIWebsiteGenerator.php

namespace App\Services;

use App\Models\Business;
use App\Models\Website;
use App\Models\Theme;
use OpenAI\Laravel\Facades\OpenAI;

class AIWebsiteGenerator
{
    public function generate(Business $business): Website
    {
        // 1. Select best theme based on category
        $theme = $this->selectTheme($business);
        
        // 2. Create website record
        $website = Website::create([
            'business_id' => $business->id,
            'theme_id' => $theme->id,
            'subdomain' => $this->generateSubdomain($business->name),
            'is_published' => true,
            'published_at' => now(),
        ]);
        
        // 3. Generate pages and sections
        $this->generateHomePage($website, $business);
        $this->generateAboutPage($website, $business);
        $this->generateServicesPage($website, $business);
        $this->generateContactPage($website, $business);
        
        // 4. Generate SEO metadata
        $this->generateSEO($website, $business);
        
        return $website;
    }
    
    protected function selectTheme(Business $business): Theme
    {
        // Match theme to business category
        return Theme::where('industry', $business->category->slug)
            ->where('is_active', true)
            ->first() 
            ?? Theme::where('slug', 'default')->first();
    }
    
    protected function generateHomePage(Website $website, Business $business): void
    {
        $page = $website->pages()->create([
            'slug' => 'home',
            'title' => 'Home',
            'sort_order' => 1,
        ]);
        
        // Hero section
        $page->sections()->create([
            'type' => 'hero',
            'content' => [
                'heading' => $business->name,
                'subheading' => $business->tagline ?? $this->aiGenerateTagline($business),
                'background_image' => $business->cover_image,
                'cta_text' => 'Contact Us',
                'cta_link' => '#contact',
            ],
            'sort_order' => 1,
        ]);
        
        // About section
        $page->sections()->create([
            'type' => 'about',
            'content' => [
                'heading' => 'About ' . $business->name,
                'body' => $business->ai_summary ?? $this->aiGenerateAbout($business),
                'image' => $business->logo,
            ],
            'sort_order' => 2,
        ]);
        
        // Services/Products section
        $page->sections()->create([
            'type' => 'services',
            'content' => [
                'heading' => 'Our Services',
                'show_pricing' => true,
            ],
            'sort_order' => 3,
        ]);
        
        // Contact section
        $page->sections()->create([
            'type' => 'contact',
            'content' => [
                'heading' => 'Get In Touch',
                'phone' => $business->phone,
                'email' => $business->email,
                'whatsapp' => $business->whatsapp,
                'show_map' => true,
            ],
            'sort_order' => 4,
        ]);
    }
    
    protected function aiGenerateTagline(Business $business): string
    {
        $response = OpenAI::chat()->create([
            'model' => 'gpt-4',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'Generate a short, catchy tagline for a business. Max 10 words.'
                ],
                [
                    'role' => 'user',
                    'content' => "Business: {$business->name}, Category: {$business->category->name}"
                ]
            ],
        ]);
        
        return trim($response->choices[0]->message->content);
    }
    
    protected function aiGenerateAbout(Business $business): string
    {
        $response = OpenAI::chat()->create([
            'model' => 'gpt-4',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'Write a professional "About Us" paragraph for a business. 3-4 sentences. SEO-friendly.'
                ],
                [
                    'role' => 'user',
                    'content' => "Business: {$business->name}, Type: {$business->category->name}, Location: {$business->locations->first()->city}"
                ]
            ],
        ]);
        
        return trim($response->choices[0]->message->content);
    }
    
    protected function generateSEO(Website $website, Business $business): void
    {
        $website->update([
            'seo_title' => "{$business->name} | {$business->category->name} in {$business->locations->first()->city}",
            'seo_description' => $this->aiGenerateMetaDescription($business),
        ]);
    }
    
    protected function generateSubdomain(string $businessName): string
    {
        return \Str::slug($businessName);
    }
}
```

---

## Search Engine (Meilisearch/Typesense)

```php
<?php
// app/Models/Business.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Business extends Model
{
    use Searchable;
    
    public function toSearchableArray(): array
    {
        $location = $this->primaryLocation;
        
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'category' => $this->category?->name,
            'subcategory' => $this->subcategory?->name,
            'tags' => $this->tags,
            'services' => $this->services->pluck('name')->toArray(),
            'products' => $this->products->pluck('name')->toArray(),
            'city' => $location?->city,
            'state' => $location?->state,
            'country' => $location?->country,
            'location' => [
                'lat' => (float) $location?->latitude,
                'lng' => (float) $location?->longitude,
            ],
            'rating' => (float) $this->averageRating(),
            'review_count' => $this->reviews->count(),
            'trust_score' => (float) $this->trust_score,
            'is_verified' => $this->verified,
            'is_open_now' => $this->isOpenNow(),
            'plan' => $this->plan?->slug,
            'status' => $this->status,
            'created_at' => $this->created_at->timestamp,
        ];
    }
}
```

### Search Controller

```php
<?php
// app/Http/Controllers/DiscoveryController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Business;

class DiscoveryController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('q', '');
        $category = $request->input('category');
        $location = $request->input('location');
        $lat = $request->input('lat');
        $lng = $request->input('lng');
        $radius = $request->input('radius', 10); // km
        
        $filters = [];
        
        if ($category) {
            $filters[] = "category = '{$category}'";
        }
        
        if ($request->has('open_now')) {
            $filters[] = 'is_open_now = true';
        }
        
        if ($request->has('verified')) {
            $filters[] = 'is_verified = true';
        }
        
        $searchQuery = Business::search($query, function($meilisearch, $query, $options) use ($filters, $lat, $lng, $radius) {
            if (!empty($filters)) {
                $options['filter'] = implode(' AND ', $filters);
            }
            
            if ($lat && $lng) {
                $options['sort'] = ["_geoPoint({$lat}, {$lng}):asc"];
            }
            
            return $meilisearch->search($query, $options);
        });
        
        $results = $searchQuery->paginate(20);
        
        return view('discovery.search', compact('results', 'query'));
    }
    
    public function aiSearch(Request $request)
    {
        // Natural language search
        // "I need a mechanic that repairs Toyota vehicles today under ₦30,000"
        
        $userQuery = $request->input('q');
        
        // Parse intent using AI
        $parsedIntent = $this->parseSearchIntent($userQuery);
        
        // Build search from parsed intent
        $results = Business::search($parsedIntent['keywords'], function($meilisearch, $query, $options) use ($parsedIntent) {
            $filters = [];
            
            if ($parsedIntent['category']) {
                $filters[] = "category = '{$parsedIntent['category']}'";
            }
            
            if ($parsedIntent['max_price']) {
                $filters[] = "price <= {$parsedIntent['max_price']}";
            }
            
            if ($parsedIntent['open_today']) {
                $filters[] = 'is_open_now = true';
            }
            
            $options['filter'] = implode(' AND ', $filters);
            
            return $meilisearch->search($query, $options);
        })->paginate(20);
        
        return view('discovery.ai-search', compact('results', 'userQuery', 'parsedIntent'));
    }
}
```

---

## API Structure (For Future Mobile App)

```php
<?php
// routes/api.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    BusinessController,
    SearchController,
    BookingController,
    OrderController,
    ReviewController,
    MessageController,
    AuthController
};

Route::prefix('v1')->group(function () {
    
    // Public routes
    Route::get('/businesses', [BusinessController::class, 'index']);
    Route::get('/businesses/{slug}', [BusinessController::class, 'show']);
    Route::get('/businesses/{slug}/products', [BusinessController::class, 'products']);
    Route::get('/businesses/{slug}/services', [BusinessController::class, 'services']);
    Route::get('/businesses/{slug}/reviews', [BusinessController::class, 'reviews']);
    
    Route::get('/search', [SearchController::class, 'search']);
    Route::get('/categories', [SearchController::class, 'categories']);
    Route::get('/nearby', [SearchController::class, 'nearby']);
    
    // Auth
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    
    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', [AuthController::class, 'user']);
        Route::post('/logout', [AuthController::class, 'logout']);
        
        // Business owner routes
        Route::middleware('business.owner')->group(function () {
            Route::apiResource('my-businesses', BusinessController::class);
            Route::post('/businesses/{id}/products', [BusinessController::class, 'storeProduct']);
            Route::post('/businesses/{id}/services', [BusinessController::class, 'storeService']);
            Route::get('/businesses/{id}/analytics', [BusinessController::class, 'analytics']);
            Route::get('/businesses/{id}/bookings', [BookingController::class, 'index']);
            Route::get('/businesses/{id}/orders', [OrderController::class, 'index']);
        });
        
        // Customer routes
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

---

## Queue Jobs

```php
<?php
// app/Jobs/GenerateAIWebsite.php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Business;
use App\Services\AIWebsiteGenerator;

class GenerateAIWebsite implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public function __construct(
        public Business $business
    ) {}
    
    public function handle(AIWebsiteGenerator $generator): void
    {
        $website = $generator->generate($this->business);
        
        // Notify user
        // Send email: "Your website is ready!"
    }
}

// app/Jobs/ProcessPayment.php
// app/Jobs/SendBookingReminder.php
// app/Jobs/GenerateSEOReport.php
// app/Jobs/SyncBusinessToSearchIndex.php
// app/Jobs/GenerateAIContent.php
```

---

## Event Listeners

```php
<?php
// app/Events/BusinessRegistered.php
// app/Listeners/CreateBusinessWebsite.php
// app/Listeners/SendWelcomeEmail.php
// app/Listeners/IndexBusinessForSearch.php

// app/Events/BookingCreated.php
// app/Listeners/NotifyBusinessOwner.php
// app/Listeners/SendCustomerConfirmation.php
// app/Listeners/UpdateBusinessAnalytics.php

// app/Events/ReviewSubmitted.php
// app/Listeners/UpdateBusinessRating.php
// app/Listeners/NotifyBusinessOfReview.php
// app/Listeners/ModerateReviewWithAI.php
```

---

## Folder Structure

```
omnibusiness/
├── app/
│   ├── Console/
│   ├── Exceptions/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/           # Admin dashboard
│   │   │   ├── Api/             # API controllers (for mobile)
│   │   │   ├── Business/        # Business owner dashboard
│   │   │   ├── Discovery/       # Customer search & discovery
│   │   │   ├── Website/         # Public business websites
│   │   │   └── Auth/
│   │   ├── Middleware/
│   │   │   ├── ResolveBusinessDomain.php
│   │   │   ├── TrackPageView.php
│   │   │   └── CheckBusinessPlan.php
│   │   ├── Requests/            # Form request validation
│   │   └── Resources/           # API resources
│   ├── Models/
│   │   ├── User.php
│   │   ├── Business.php
│   │   ├── Website.php
│   │   ├── Theme.php
│   │   ├── Product.php
│   │   ├── Service.php
│   │   ├── Booking.php
│   │   ├── Order.php
│   │   ├── Review.php
│   │   ├── Conversation.php
│   │   ├── Message.php
│   │   ├── Plan.php
│   │   ├── Subscription.php
│   │   ├── Media.php
│   │   └── AIGeneration.php
│   ├── Services/
│   │   ├── AIWebsiteGenerator.php
│   │   ├── ThemeEngine.php
│   │   ├── SearchService.php
│   │   ├── PaymentService.php
│   │   ├── AnalyticsService.php
│   │   ├── MarketingService.php
│   │   └── DomainService.php
│   ├── Jobs/
│   ├── Events/
│   ├── Listeners/
│   ├── Notifications/
│   ├── Policies/                # Authorization
│   └── Providers/
├── bootstrap/
├── config/
├── database/
│   ├── factories/
│   ├── migrations/              # All migrations above
│   └── seeders/
│       ├── ThemeSeeder.php
│       ├── PlanSeeder.php
│       ├── CategorySeeder.php
│       └── DemoBusinessSeeder.php
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   ├── components/          # Reusable UI components
│   │   ├── discovery/           # Search & browse pages
│   │   ├── business/            # Owner dashboard
│   │   ├── admin/               # Admin panel
│   │   ├── auth/                # Login, register, etc.
│   │   └── themes/              # Business website themes
│   │       ├── default/
│   │       ├── restaurant/
│   │       ├── medical/
│   │       ├── corporate/
│   │       └── luxury/
│   ├── css/
│   └── js/
├── routes/
│   ├── web.php                  # Web routes (discovery + dashboard)
│   ├── website.php              # Business website routes (subdomain/custom domain)
│   └── api.php                  # API routes (for mobile)
├── storage/
├── tests/
└── artisan
```

---

## Key Implementation Principles

1. **Business-Centric**: Every feature belongs to a business. The website is a view, not an entity.

2. **Single Source of Truth**: Business data is stored once. Website, discovery profile, mobile app, and API all read from the same tables.

3. **Data-Driven Themes**: Themes are JSON configs + Blade components, not static HTML. Changing themes doesn't lose content.

4. **Domain Agnostic**: One Laravel app serves unlimited domains. Middleware resolves the business from the Host header.

5. **AI-Assisted, Not AI-Dependent**: AI generates initial content, but users can always edit. AI is a tool, not a replacement.

6. **Progressive Enhancement**: Start with web (Laravel + Livewire). Add API later for mobile. Add native apps last.

7. **SEO-First**: Every business page is server-rendered HTML with proper meta tags, structured data, and sitemaps.

8. **Multi-tenant by Design**: Every query is scoped to the current business. No data leaks between businesses.

---

## Technology Stack

| Layer | Technology |
|-------|-----------|
| Backend | Laravel 11 + PHP 8.3 |
| Frontend | Livewire 3 + Blade + Tailwind CSS |
| Interactivity | Alpine.js |
| Database | PostgreSQL 16 |
| Cache | Redis |
| Search | Meilisearch or Typesense |
| Queue | Redis + Laravel Horizon |
| Storage | AWS S3 / Cloudflare R2 |
| Maps | OpenStreetMap + Leaflet |
| Payments | Paystack / Flutterwave |
| AI | OpenAI GPT-4 / Claude |
| Email | Mailgun / Postmark |
| SMS | Termii / Africa's Talking |
| Domain | Cloudflare / Custom DNS |
| SSL | Let's Encrypt + Caddy/Traefik |
| Hosting | Laravel Forge + DigitalOcean / AWS |
| CDN | Cloudflare |

---

## MVP Priority (Phase 1)

**Must Have (Weeks 1-4):**
- [ ] User registration & auth
- [ ] Business registration with AI website generation
- [ ] Subdomain websites (business.omnibusiness.com)
- [ ] Basic search (by name, category, location)
- [ ] Business profiles with contact info
- [ ] Reviews & ratings
- [ ] Basic admin dashboard

**Should Have (Weeks 5-8):**
- [ ] Custom domain support
- [ ] Products & services catalog
- [ ] Booking system
- [ ] AI content generation
- [ ] Analytics dashboard
- [ ] Premium plans & billing

**Nice to Have (Weeks 9-12):**
- [ ] Online store with payments
- [ ] Messaging system
- [ ] Marketing campaigns
- [ ] Advanced AI search
- [ ] Mobile app (API + .NET MAUI)
- [ ] Multi-branch support

---

*Document Version: 1.0*
*Platform: OmniBusiness*
*Architecture: Laravel + Livewire + PostgreSQL + Meilisearch*
*Date: July 2026*
"""

# Save to file
with open('/mnt/agents/output/omnibusiness_backend_architecture.md', 'w', encoding='utf-8') as f:
    f.write(backend_doc)

print("Backend architecture document created successfully!")
print(f"File size: {len(backend_doc)} characters")
