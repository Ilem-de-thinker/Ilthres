# Ilthres Market — MVP Scope

> **What ships. Nothing else.**
>
> This file lists ONLY the features that must work end-to-end for MVP launch.
> Everything else (marketing, loyalty, referrals, lead forms, messaging,
> portfolio, AI generation, API, queues) is POST-MVP.

---

## MVP Definition

A business owner signs up, sets up a store/service page, adds products or services,
customers can browse, add to cart, and pay. The business owner sees orders.
That's it.

---

## Must-Have Features (MVP)

### 1. Authentication

- [x] Register / Login / Forgot Password / Reset Password
- [x] Logout

### 2. Onboarding

- [x] 4-step wizard (WhatsApp → Business Type → Pricing → Setup)
- [x] Get Started page (post-registration choice)
- [x] Create Business (name, slug, description, category, logo, cover)

### 3. Business Profile (Public)

- [x] Theme-rendered public page at `/{business:slug}`
- [x] Product listing page
- [x] Service listing page
- [x] Product detail page
- [x] Service detail page
- [x] WhatsApp / Phone contact CTAs
- [x] About page
- [x] Contact page

### 4. Business Settings (Dashboard)

- [x] Edit business details (name, description, about, category, contact info)
- [x] Address, city, state, country
- [x] Social links
- [x] Logo & cover upload
- [x] Theme selection & customization (colors, fonts, radius)
- [x] Order mode toggle (inquiry vs checkout)
- [x] Payment/bank details (for checkout mode)

### 5. Products

- [x] CRUD (create, list, edit, delete)
- [x] Images
- [x] Price (NGN)
- [x] Active/inactive toggle
- [x] Subscription-gated limits

### 6. Services

- [x] CRUD (create, list, edit, delete)
- [x] Price (NGN)
- [x] Active/inactive toggle
- [x] Subscription-gated limits

### 7. Theme System

- [x] 4 themes (default, bold, heritage, modern)
- [x] Theme selection per business
- [x] Color customization (primary, secondary, background, text, accent)
- [x] Font selection (heading, body)
- [x] Border radius, animations toggle
- [x] Product detail views with CTA buttons
- [x] Service detail views with CTA buttons
- [x] Storefront/home views

### 8. Cart & Checkout

- [x] Add to cart (POST route, session-based, zoned per business)
- [x] Cart page (view items, update quantity, remove, clear)
- [x] Checkout page (buyer name, email, phone, message)
- [x] Paystack payment integration
- [x] Paystack callback (verify + confirm order)
- [x] Order creation (Order + OrderItem records)
- [x] Order number generation

### 9. Inquiry System

- [x] Inquiry form for businesses with `order_mode = 'inquiry'`
- [x] Creates Order record with type `inquiry`
- [x] Pre-filled product/service info

### 10. Orders (Seller Side)

- [x] Seller Orders page (list incoming orders)
- [x] View order details (buyer info, items, total)
- [x] Update order status (pending → confirmed → processing → completed / cancelled)
- [x] Search by order number, buyer name, buyer email

### 11. Explore & Search

- [x] Public explore page at `/explore`
- [x] Search businesses by name, description, city, state
- [x] Filter by type (business, product, service)
- [x] Filter by category
- [x] Discoverable flag on businesses
- [x] AI-powered natural language search
- [x] Smart ranking (verified, rating, activity, proximity)
- [x] Geolocation support ("near me" queries)
- [x] Product search across businesses

### 12. Analytics (Basic)

- [x] Page views (business profile, product detail, service detail)
- [x] Click tracking (WhatsApp, phone)
- [x] Dashboard with view counts, top items
- [x] Marketing link click tracking

### 13. Subscriptions & Payments

- [x] 4 plans (Free, Professional, Business, Linked Business)
- [x] Paystack subscription payment
- [x] Subscription status display
- [x] Plan change (upgrade/downgrade)
- [x] Feature gating (max products, max services, max media)
- [x] Webhook for payment confirmation

### 14. Media Library

- [x] Upload images
- [x] Organize / delete
- [x] Subscription-gated limits

### 15. Notifications

- [x] Bell icon with unread count
- [x] Notification list (mark read/unread)
- [x] Welcome notification on registration
- [x] Verification approved/rejected notifications

### 16. KYC / Verification

- [x] Document upload (Government ID, Business Registration, Utility Bill, Tax ID)
- [x] Admin review flow (approve/reject with notes)
- [x] Verified badge on business profile
- [x] Checkout requires verified business

### 17. Admin Panel

- [x] Admin dashboard (stats, charts)
- [x] User management
- [x] Business management
- [x] Order management
- [x] Subscription management
- [x] Theme management
- [x] Category management
- [x] Platform settings (pricing toggle)
- [x] Verification review

### 18. Website Infrastructure

- [x] Websites table (subdomain, custom domain, SEO)
- [x] Website pages & sections (CRUD, reorder)
- [x] Auto-create on business creation
- [x] ThemeEngine service

### 19. Bookings (Service Businesses)

- [x] Booking model + migration
- [x] ServiceAvailability model + migration
- [x] BookingSlot model + migration
- [x] BookAppointment Livewire component (customer-facing)
- [x] BookingManager Livewire component (seller dashboard)
- [x] AvailabilityManager Livewire component (seller sets hours)
- [x] Booking routes (`/{business:slug}/book/{service}`)
- [x] Dashboard navigation (desktop + mobile)
- [x] Book Now section on all 4 theme service-detail views
- [x] Slot generation algorithm (30 days ahead)
- [x] Double-booking prevention
- [x] Status progression (pending → confirmed → in_progress → completed)
- [x] Payment integration for paid bookings

### 20. Reviews

- [x] Review model + migration
- [x] Add average_rating + review_count to businesses
- [x] LeaveReview Livewire component (customer-facing)
- [x] ReviewManager Livewire component (seller dashboard)
- [x] ReviewDisplay Livewire component (public)
- [x] Review routes (`/{business:slug}/review`)
- [x] Dashboard navigation (desktop + mobile)
- [x] Reviews section on all 4 theme service-detail views
- [x] Review eligibility (completed order/booking only)
- [x] One review per transaction
- [x] Seller response to reviews
- [x] Rating distribution display
- [x] Average rating cache on Business model

---

## MVP Bug Fixes (Completed)

### Cart & Checkout

1. **Cart route fixed** — `/{business:slug}/cart/add/{type}/{id}` (was missing `/{type}/{id}`)
2. **Default theme "ADD TO BAG" fixed** — removed broken `@else` fallback that linked to `#`
3. **Cart clearing moved to after payment** — `session()->forget` now in Paystack callback, not `initiatePayment()`

### Explore

4. **"Verified" filter fixed** — column is `is_verified` not `verified` in the query

---

## MVP Remaining Fixes (Must Do Before Launch)

### Checkout Flow

1. **Validate items at checkout** — re-check `is_active` and price before creating order
2. **Add order summary to checkout page** — show cart items, quantities, subtotal before payment
3. **Show order confirmation after payment** — redirect to a success page with order details
4. **Email notification to business owner on new order** — send mail/notification when order is placed

### Seller Orders

5. **Add status update notification** — notify buyer when order status changes (at minimum, database notification)

### Categories

6. **88 categories seeded** — full set across 14 groups

---

## What's NOT in MVP (Post-Launch)

| Feature | Why Deferred |
|---------|-------------|
| Customer model / repeat customer tracking | Denormalized buyer info on orders is sufficient for MVP |
| Database-persisted carts | Session carts work for MVP; add persistence post-launch if users report issues |
| Marketing links, discounts, lead forms, loyalty, referrals | Nice-to-have, not core transaction flow |
| Promotions system | Can add after launch |
| Hero banners / promo banners | Visual enhancement, not functional |
| Product CSV import | Power-user feature |
| Messaging & communication | WhatsApp serves this purpose for MVP |
| Portfolio & gallery | Themes render products/services; gallery is enhancement |
| Advanced search (Meilisearch) | AI search with DB queries works for MVP scale |
| AI website generation | Manual setup is fine for MVP |
| API & mobile app | Web-only for MVP |
| Queue jobs & background processing | Synchronous is fine for MVP scale |
| Campaigns & coupons (advanced marketing) | Post-launch |
| Subdomain / custom domain resolution | Path-based access (`/{slug}`) is sufficient for MVP |
| OrderPolicy | Add authorization post-launch |
| PDF analytics export | CSV is sufficient |

---

## MVP Success Criteria

A user can:
1. Register as a business owner
2. Complete onboarding and create a business
3. Add products with images and prices
4. Choose a theme and customize colors
5. Share their business link (`/{slug}`)
6. A customer visits the link, browses products
7. Customer adds items to cart
8. Customer checks out and pays via Paystack
9. Business owner sees the order in their dashboard
10. Business owner marks order as confirmed/completed
11. Service businesses can set availability and accept bookings
12. Customers can book services and pay
13. Customers can leave reviews after completing transactions
14. Customers can search with natural language (e.g., "plumber near me")

That's the MVP. Ship this. Everything else comes after.
