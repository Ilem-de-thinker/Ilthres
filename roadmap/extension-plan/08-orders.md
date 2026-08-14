# 08 — Orders & E-Commerce

> Product stores need order management. Customers browse, add to cart, checkout, pay.

## What Already Exists

- Products with price, stock, images, variants
- Paystack payment integration
- Business has `type: product_store`
- Discount system exists

## What This Phase Adds

### 1. Tables

```sql
CREATE TABLE orders (
    id              BIGSERIAL PRIMARY KEY,
    business_id     BIGINT REFERENCES businesses(id) ON DELETE CASCADE,
    customer_id     BIGINT REFERENCES users(id) ON DELETE SET NULL,

    customer_name   VARCHAR(255),
    customer_email  VARCHAR(255),
    customer_phone  VARCHAR(20),
    shipping_address JSONB,

    order_number    VARCHAR(50) UNIQUE NOT NULL,
    status          ENUM('pending','confirmed','processing','shipped','delivered','cancelled','refunded') DEFAULT 'pending',

    subtotal        DECIMAL(12, 2) NOT NULL,
    tax_amount      DECIMAL(12, 2) DEFAULT 0,
    shipping_amount DECIMAL(12, 2) DEFAULT 0,
    discount_amount DECIMAL(12, 2) DEFAULT 0,
    total           DECIMAL(12, 2) NOT NULL,
    currency        VARCHAR(3) DEFAULT 'NGN',

    payment_method  VARCHAR(50),
    payment_status  ENUM('pending','paid','failed','refunded') DEFAULT 'pending',
    paid_at         TIMESTAMP,

    delivery_method ENUM('pickup','delivery','digital') DEFAULT 'pickup',
    tracking_number VARCHAR(100),

    notes           TEXT,
    created_at      TIMESTAMP DEFAULT NOW(),
    updated_at      TIMESTAMP DEFAULT NOW()
);

CREATE TABLE order_items (
    id              BIGSERIAL PRIMARY KEY,
    order_id        BIGINT REFERENCES orders(id) ON DELETE CASCADE,
    product_id      BIGINT REFERENCES products(id) ON DELETE SET NULL,

    product_name    VARCHAR(255) NOT NULL,
    product_price   DECIMAL(12, 2) NOT NULL,
    quantity        INTEGER NOT NULL,
    total           DECIMAL(12, 2) NOT NULL,

    created_at      TIMESTAMP DEFAULT NOW()
);

CREATE TABLE payments (
    id              BIGSERIAL PRIMARY KEY,
    order_id        BIGINT REFERENCES orders(id) ON DELETE CASCADE,
    business_id     BIGINT REFERENCES businesses(id) ON DELETE CASCADE,

    amount          DECIMAL(12, 2) NOT NULL,
    currency        VARCHAR(3) DEFAULT 'NGN',
    gateway         VARCHAR(50) NOT NULL,
    gateway_ref     VARCHAR(255),
    gateway_response JSONB,
    status          ENUM('pending','success','failed','refunded') DEFAULT 'pending',
    paid_at         TIMESTAMP,
    created_at      TIMESTAMP DEFAULT NOW()
);
```

### 2. Files to Create

- Migrations for all 3 tables
- Models: Order, OrderItem, Payment
- Actions: PlaceOrder, UpdateOrderStatus, ProcessPayment, IssueRefund
- DTOs: OrderData, OrderItemData
- Events: OrderPlaced, OrderShipped, OrderDelivered, OrderCancelled, PaymentReceived
- Listeners: NotifyBusinessOfOrder, SendCustomerOrderUpdate, UpdateBusinessAnalytics
- Policy: OrderPolicy
- Livewire: OrderManager (merchant), CheckoutFlow (customer)
- Views: order-manager.blade.php, checkout.blade.php

### 3. Order Number Generation

```php
// ORD-2026-000001 format
$orderNumber = 'ORD-' . now()->format('Y') . '-' . str_pad($business->orders()->count() + 1, 6, '0', STR_PAD_LEFT);
```

### 4. Routes

- `/dashboard/orders` — merchant order management
- `/{slug}/checkout` — public checkout page
- `/{slug}/cart` — cart page

---

## Checklist

- [ ] Migration: `orders` table
- [ ] Migration: `order_items` table
- [ ] Migration: `payments` table
- [ ] Models: Order, OrderItem, Payment
- [ ] Actions: PlaceOrder, UpdateOrderStatus, ProcessPayment, IssueRefund
- [ ] DTOs: OrderData, OrderItemData
- [ ] Events + Listeners (wired in EventServiceProvider)
- [ ] Policy: OrderPolicy
- [ ] Livewire: OrderManager, CheckoutFlow
- [ ] Views: order-manager.blade.php, checkout.blade.php
- [ ] Routes: `/dashboard/orders`, `/{slug}/checkout`
