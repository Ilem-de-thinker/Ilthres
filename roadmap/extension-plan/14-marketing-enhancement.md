# 14 — Marketing Enhancement

> Extend existing marketing (links, QR codes) with campaigns, coupons, and advanced promotions.

## What Already Exists

- Marketing links + QR codes + click tracking
- Discount system (discounts + discount_redemptions)
- Lead forms (lead_forms + lead_form_fields + lead_submissions)
- Loyalty programs (loyalty_programs + loyalty_points_transactions)
- Referral system (referrals + referral_rewards)
- Promotions + promo banners
- Product imports

## What This Phase Adds

### 1. Campaigns Table

```sql
CREATE TABLE campaigns (
    id              BIGSERIAL PRIMARY KEY,
    business_id     BIGINT REFERENCES businesses(id) ON DELETE CASCADE,

    name            VARCHAR(255) NOT NULL,
    type            ENUM('email','sms','push','social') NOT NULL,

    subject         VARCHAR(255),
    body            TEXT,

    target_audience JSONB,   -- {"segment":"all_customers","location_radius":10}

    scheduled_at    TIMESTAMP,
    sent_at         TIMESTAMP,

    recipients_count INTEGER DEFAULT 0,
    opened_count    INTEGER DEFAULT 0,
    clicked_count   INTEGER DEFAULT 0,

    status          ENUM('draft','scheduled','sending','sent','cancelled') DEFAULT 'draft',

    created_at      TIMESTAMP DEFAULT NOW(),
    updated_at      TIMESTAMP DEFAULT NOW()
);
```

### 2. Coupons Table

```sql
CREATE TABLE coupons (
    id              BIGSERIAL PRIMARY KEY,
    business_id     BIGINT REFERENCES businesses(id) ON DELETE CASCADE,

    code            VARCHAR(50) NOT NULL,
    description     TEXT,

    discount_type   ENUM('percentage','fixed_amount','free_shipping') DEFAULT 'percentage',
    discount_value  DECIMAL(12, 2) NOT NULL,

    max_uses        INTEGER,
    max_uses_per_customer INTEGER DEFAULT 1,
    min_order_amount DECIMAL(12, 2),

    starts_at       TIMESTAMP,
    ends_at         TIMESTAMP,

    is_active       BOOLEAN DEFAULT TRUE,
    created_at      TIMESTAMP DEFAULT NOW(),

    UNIQUE(business_id, code)
);
```

### 3. Files to Create

- Migrations for both tables
- Models: Campaign, Coupon
- Actions: CreateCampaign, ScheduleCampaign, SendCampaign, CreateCoupon, ValidateCoupon, ApplyCoupon
- DTOs: CampaignData, CouponData
- Livewire: CampaignManager, CouponManager
- Views: campaign-manager.blade.php, coupon-manager.blade.php

### 4. Campaign Types

- **Email:** Send to customer email addresses (requires email collection)
- **SMS:** Send via SMS provider (future)
- **Push:** Push notifications (future)
- **Social:** Generate social media posts (future)

### 5. Coupon Integration

Coupons integrate with the Orders system (Phase 8):
- Customer enters coupon code at checkout
- Validate: active, not expired, within usage limits, meets minimum order
- Apply discount to order total
- Record redemption

### 6. Routes

- `/dashboard/campaigns` — campaign management
- `/dashboard/coupons` — coupon management

---

## Checklist

- [ ] Migration: `campaigns` table
- [ ] Migration: `coupons` table
- [ ] Models: Campaign, Coupon
- [ ] Actions: CreateCampaign, ScheduleCampaign, SendCampaign, CreateCoupon, ValidateCoupon, ApplyCoupon
- [ ] DTOs: CampaignData, CouponData
- [ ] Livewire: CampaignManager, CouponManager
- [ ] Views: campaign-manager.blade.php, coupon-manager.blade.php
- [ ] Routes: `/dashboard/campaigns`, `/dashboard/coupons`
- [ ] Integrate coupons with Orders checkout (Phase 8 dependency)
