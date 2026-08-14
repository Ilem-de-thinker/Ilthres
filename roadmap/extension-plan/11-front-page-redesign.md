# 11 — Front Page Redesign

> Update welcome, explore, and public pages to match the "business operating system" positioning from proc.md.

## Current State

### Welcome Page (`welcome.blade.php`)

Sections:
1. Hero — "Your business, online in minutes"
2. Features grid — 6 cards (profile, products, themes, discoverability, analytics, marketing)
3. How it works — 3 steps
4. Browse by category — 6 hardcoded categories
5. Pricing — 3 tiers (Free ₦0, Professional ₦15K, Business ₦35K)
6. Testimonials — 3 hardcoded
7. FAQ — 5 questions
8. CTA — "Ready to grow your business?"

### Explore Page (`explore.blade.php`)

- Simple header + ExploreSearch Livewire component
- Search, filter, results grid

## What proc.md / proc2.md Calls For

The positioning shifts from "business profile builder" to **"business operating system"**:
- "Don't think of it as a website builder. Think of it as a business operating system."
- "The website is just one way to present the business's data."
- Businesses can have their own website (external) OR use our platform.
- Discovery, search, bookings, reviews, messaging, orders are all part of the OS.

## Redesign: Welcome Page

### Section 1: Hero (Update)

**Current:** "Your business, online in minutes"
**New:** "Your business operating system"
**Sub:** "Create a professional presence, get discovered, take bookings, sell products, and grow — all in one place."

Keep the same layout. Update copy only.

### Section 2: Features Grid (Update)

**Current:** 6 generic feature cards
**New:** 8 cards reflecting the full platform:

```
1. Business Profile    — Professional online presence with custom theme
2. Online Store        — Sell products with inventory, variants, and checkout
3. Service Booking     — Customers book appointments online
4. Discovery Platform  — Get found by customers searching nearby
5. Reviews & Trust     — Build credibility with verified reviews
6. Messaging           — Chat with customers in real-time
7. Analytics           — Track views, clicks, bookings, and revenue
8. Marketing           — Discounts, referral codes, lead capture, campaigns
```

### Section 3: How It Works (Update)

**Current:** 3 steps (Create → Set up → Get discovered)
**New:** 4 steps reflecting the full flow:

```
1. Sign up free          — Choose business type, pick a plan
2. Set up your profile   — Add details, upload media, customize theme
3. Go live               — Your website is live at name.ilthres.com
4. Grow                  — Take bookings, sell products, get reviews, run campaigns
```

### Section 4: Business Types (New)

Replace hardcoded categories with the two business types:

```
┌─────────────────────┐  ┌─────────────────────┐
│   Product Store     │  │  Service Business    │
│                     │  │                     │
│  Sell physical &    │  │  Offer appointments │
│  digital products   │  │  and services       │
│  with inventory,    │  │  with booking,      │
│  variants, and      │  │  scheduling, and    │
│  online checkout    │  │  customer management│
│                     │  │                     │
│  [Create Store]     │  │  [Create Business]  │
└─────────────────────┘  └─────────────────────┘
```

### Section 5: Platform Preview (Keep)

Keep the dashboard screenshot. Maybe add a second screenshot showing the public business page.

### Section 6: Browse by Category (Keep + Expand)

Use dynamic categories from database instead of hardcoded:
```php
$categories = Category::where('is_active', true)->orderBy('sort_order')->get();
```

Add more categories to the seeder:
- Restaurant, Hotel, Fashion, Electronics, Beauty, Fitness, Medical, Legal, Technology, Construction, Education, Food, Home Services, Automotive

### Section 7: Testimonials (Update Copy)

Update testimonials to reflect the full platform:
```
1. "I had my fashion store online in 10 minutes. Customers book appointments and buy products — all from one page."
2. "The analytics show me exactly which services customers want. I added online booking and my revenue doubled."
3. "My clinic looks professional. Patients book online, leave reviews, and I can message them directly."
```

### Section 8: Pricing (Update)

Add new features to plan comparison:
```
Free:           Business profile, basic theme, 5 products/services, analytics, 5 media
Professional:   Premium themes, unlimited products, booking system, marketing tools, custom branding
Business:       Everything + custom domain, multiple staff, priority support, API access
```

### Section 9: FAQ (Update)

Update questions to reflect the full platform:
```
1. What is Ilthres Market?
2. Is it really free?
3. Can I use my own website/domain?
4. How do customers find my business?
5. Can I take bookings and orders online?
6. What payment methods do you accept?
7. Can I switch between product store and service business?
```

### Section 10: CTA (Update)

**Current:** "Ready to grow your business?"
**New:** "Start your business operating system — free"
**Sub:** "Join thousands of businesses already using Ilthres Market."

---

## Redesign: Explore Page

### Current: Simple search + results
### New: Discovery platform with rich filters

```
┌──────────────────────────────────────────────────┐
│  Search: [________________________] [Search]     │
│                                                  │
│  Quick Filters: [Verified] [Nearby] [Open Now]  │
│  Sort: [Relevance ▼]                            │
├──────────────┬───────────────────────────────────┤
│  Filters     │  Results Grid                     │
│              │                                   │
│  Category    │  ┌─────────┐ ┌─────────┐         │
│  □ Restaurant│  │ Business│ │ Business│         │
│  □ Hotel     │  │ Card    │ │ Card    │         │
│  □ Fashion   │  └─────────┘ └─────────┘         │
│  □ Beauty    │  ┌─────────┐ ┌─────────┐         │
│  □ ...       │  │ Business│ │ Business│         │
│              │  │ Card    │ │ Card    │         │
│  Location    │  └─────────┘ └─────────┘         │
│  [City ▼]    │                                   │
│  [State ▼]   │  Pagination                      │
│              │                                   │
│  Rating      │                                   │
│  ★★★★☆+     │                                   │
│              │                                   │
│  Type        │                                   │
│  □ Products  │                                   │
│  □ Services  │                                   │
└──────────────┴───────────────────────────────────┘
```

### Business Card Update

Add trust signals to cards:
- Verified badge ✓
- Star rating + review count
- "Open Now" indicator
- Category tag
- Location (city, state)

---

## Files to Modify

- `resources/views/welcome.blade.php` — full section updates
- `resources/views/explore.blade.php` — add filter sidebar
- `app/Domains/Search/Livewire/ExploreSearch.php` — add faceted filters
- `resources/views/livewire/search/explore-search.blade.php` — filter UI
- `database/seeders/CategorySeeder.php` — add more categories

---

## Checklist

- [ ] Update welcome.blade.php hero copy
- [ ] Update features grid (8 cards)
- [ ] Update how it works (4 steps)
- [ ] Add business types section (product store vs service business)
- [ ] Update testimonials (reflect full platform)
- [ ] Update pricing features list
- [ ] Update FAQ questions
- [ ] Update CTA section
- [ ] Use dynamic categories from database
- [ ] Add more categories to seeder
- [ ] Update explore page with filter sidebar
- [ ] Add quick filters (Verified, Nearby, Open Now)
- [ ] Add sort options
- [ ] Update business cards with trust signals
- [ ] Add "Open Now" indicator to cards
