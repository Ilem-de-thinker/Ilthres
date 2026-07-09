# Growth & Marketing — Promos, Leads, Referrals, Bulk Imports

> **New features** — discount codes, lead capture forms, referral/loyalty programs, and bulk CSV product import. Designed for merchants who want to actively promote and grow their business through Ilthres.
>
> **Livewire Components:** ~13  
> **Database Tables:** ~5

---

## 1. Discount Codes & Promo Banners (Both Business Types)

Merchants create discount codes and promotional banners displayed on their public business profile.

**Business Type:** Both

**Livewire Components:**

| Component | Command |
|-----------|---------|
| `Marketing\DiscountList` | All discount codes | `php artisan make:livewire Marketing\\DiscountList` |
| `Marketing\CreateDiscount` | Create discount code form | `php artisan make:livewire Marketing\\CreateDiscount` |
| `Marketing\DiscountStats` | Usage tracking per code | `php artisan make:livewire Marketing\\DiscountStats` |
| `Marketing\PromoBanner` | Public banner component | `php artisan make:livewire Marketing\\PromoBanner` |
| `Marketing\PromoBannerEditor` | Design + schedule banner | `php artisan make:livewire Marketing\\PromoBannerEditor` |

**Models:** `Discount`, `DiscountRedemption`, `PromoBanner` (with `--migration`)

**Actions:**
- `Marketing\CreateDiscount`
- `Marketing\UpdateDiscount`
- `Marketing\DisableDiscount`
- `Marketing\RedeemDiscount`
- `Marketing\CreatePromoBanner`
- `Marketing\UpdatePromoBanner`

**Events:** `DiscountCreated`, `DiscountRedeemed`, `DiscountExpired`, `BannerShown`, `BannerClicked`

**Discount Code Features:**
```
Code:      SAVE20
Type:      percentage | fixed_amount
Value:     20 or 500 (NGN)
Min order: 0 or minimum amount
Uses:      100 (max redemptions)
Expires:   2026-12-31
Scope:     all | specific products/services
```

**Promo Banner Features:**
```
Position:  top | bottom | hero
Style:     announcement | sale | seasonal
Schedule:  start_date → end_date
CTA:       "Shop Now" → links to product or external URL
```

**Public Display:**
```blade
{{-- Top announcement bar on business profile --}}
@if ($activeBanner)
    <div class="bg-accent text-white text-center py-2 px-4">
        {{ $activeBanner->message }}
        @if ($activeBanner->cta_url)
            <a href="{{ $activeBanner->cta_url }}" class="underline font-semibold">{{ $activeBanner->cta_text }}</a>
        @endif
    </div>
@endif

{{-- Discount code input on product/service pages --}}
<input type="text" wire:model="discountCode" placeholder="Enter discount code">
<button wire:click="applyDiscount">Apply</button>
```

**Tier Access:**
- **Free:** 1 active discount, text-only banner
- **Professional:** 5 active discounts, styled banner with CTA
- **Business:** Unlimited discounts, scheduled banners, A/B testing

---

## 2. Lead Capture Forms (Both Business Types)

Custom inquiry forms beyond the WhatsApp button. Merchants create forms to capture leads — contact info, service requests, custom questions.

**Business Type:** Both

**Livewire Components:**

| Component | Command |
|-----------|---------|
| `Marketing\LeadFormList` | All custom forms | `php artisan make:livewire Marketing\\LeadFormList` |
| `Marketing\LeadFormBuilder` | Drag-and-drop form builder | `php artisan make:livewire Marketing\\LeadFormBuilder` |
| `Marketing\LeadFormPreview` | Public-facing form | `php artisan make:livewire Marketing\\LeadFormPreview` |
| `Marketing\LeadFormSubmissions` | View submitted leads | `php artisan make:livewire Marketing\\LeadFormSubmissions` |
| `Marketing\LeadDetail` | Single lead view | `php artisan make:livewire Marketing\\LeadDetail` |

**Models:** `LeadForm`, `LeadFormField`, `LeadSubmission` (with `--migration`)

**Actions:**
- `Marketing\CreateLeadForm`
- `Marketing\UpdateLeadForm`
- `Marketing\DeleteLeadForm`
- `Marketing\SubmitLeadForm`
- `Marketing\UpdateLeadStatus`
- `Marketing\ExportLeads` — CSV download

**Events:** `LeadFormCreated`, `LeadFormSubmitted`, `LeadStatusChanged`, `LeadsExported`

**Form Builder Field Types:**
```
text          — Short text input
textarea      — Long text / message
email         — Email input with validation
phone         — Phone number input
select        — Dropdown with custom options
checkbox      — Multiple choice
radio         — Single choice
file          — File upload (max 5MB)
date          — Date picker
```

**Pre-built Form Templates:**
```
1. "Contact Us"     — name, email, phone, message
2. "Get a Quote"    — name, email, phone, service, budget, message
3. "Book a Call"    — name, email, phone, preferred date, preferred time
4. "Custom Request" — fully customizable
```

**Lead Statuses:**
```
new → contacted → qualified → converted → lost
```

**Notifications:**
- Merchant gets email + in-app notification on each submission
- Auto-reply email to customer: "Thanks for reaching out!"

**Dashboard View:**
```
┌─────────────────────────────────────────────────┐
│ Forms (3)          │ Leads (47)                 │
├─────────────────────────────────────────────────┤
│ ┌───────────────┐  │ New (12)  Contacted (23)   │
│ │ Custom Quote  │  │ Qualified (8)  Converted (4)│
│ │ 23 submissions│  │ ─────────────────────────── │
│ ├───────────────┤  │ John D.    2 min ago   ★  │
│ │ Contact Us    │  │ Sarah K.   15 min ago  ★  │
│ │ 18 submissions│  │ Michael R. 1 hr ago    ★  │
│ ├───────────────┤  │ ...                      │
│ │ Book a Call   │  │                          │
│ │ 6 submissions │  │                          │
│ └───────────────┘  │                          │
└─────────────────────────────────────────────────┘
```

**Tier Access:**
- **Free:** 1 form, 3 fields max, 50 submissions/month
- **Professional:** 3 forms, 10 fields max, 500 submissions/month
- **Business:** Unlimited forms, unlimited fields, unlimited submissions
---

## 3. Referral & Loyalty Program (Both Business Types)

Track referrals and reward repeat customers. Simple points-based system merchants can configure.

**Business Type:** Both

**Livewire Components:**

| Component | Command |
|-----------|---------|
| `Marketing\LoyaltySettings` | Points config, rewards | `php artisan make:livewire Marketing\\LoyaltySettings` |
| `Marketing\ReferralProgram` | Referral link + rewards | `php artisan make:livewire Marketing\\ReferralProgram` |
| `Marketing\CustomerLoyalty` | Customer-visible points balance | `php artisan make:livewire Marketing\\CustomerLoyalty` |
| `Marketing\LoyaltyHistory` | Points earned/redeemed log | `php artisan make:livewire Marketing\\LoyaltyHistory` |

**Models:** `LoyaltyProgram`, `LoyaltyPointsTransaction`, `Referral`, `ReferralReward` (with `--migration`)

**Actions:**
- `Marketing\CreateLoyaltyProgram`
- `Marketing\UpdateLoyaltyProgram`
- `Marketing\EarnPoints` — award points for actions
- `Marketing\RedeemPoints` — customer redeems for reward
- `Marketing\CreateReferralCode`
- `Marketing\TrackReferral`
- `Marketing\RewardReferrer`

**Events:** `LoyaltyProgramCreated`, `PointsEarned`, `PointsRedeemed`, `ReferralCreated`, `ReferralConverted`, `ReferrerRewarded`

**Earning Rules (merchant-configurable):**

| Action | Points |
|--------|--------|
| Visit business page | 1 point/day |
| Leave a review | 10 points |
| Refer a friend | 50 points (when friend signs up) |
| Make a purchase (product store) | 1 point per ₦500 |
| Book an appointment (service) | 20 points |
| Share on social media | 5 points |

**Reward Examples (merchant-configurable):**

| Reward | Points Required |
|--------|----------------|
| 5% discount code | 100 points |
| 10% discount code | 200 points |
| Free product/service | 500 points |
| Custom reward | Variable |

**Referral Flow:**
```
1. Customer gets referral link: ilthres.ng/business/{slug}?ref=CODE
2. Shares with friends via WhatsApp, social media, email
3. Friend clicks link → visits business page
4. Friend creates account → referrer gets points
5. Referral tracked in merchant dashboard
```

**Customer-Facing Widget:**
```blade
{{-- On business public profile --}}
@auth
    <livewire:marketing.customer-loyalty :business="$business" />
    {{-- Shows: "You have 150 points → Redeem for 10% off!" --}}
@endauth
```

**Tier Access:**
- **Free:** Basic points (visit, review), 1 reward tier
- **Professional:** Full points system, 3 reward tiers, referral tracking
- **Business:** Unlimited tiers, custom rules, referral analytics

---

## 4. Bulk Product Import via CSV (Product Stores Only)

Upload a CSV file to create or update multiple products at once. Essential for merchants with large catalogs migrating to Ilthres.

**Business Type:** `product_store` only

**Livewire Components:**

| Component | Command |
|-----------|---------|
| `Product\BulkImport` | CSV upload + mapping UI | `php artisan make:livewire Product\\BulkImport` |
| `Product\ImportHistory` | Past imports with results | `php artisan make:livewire Product\\ImportHistory` |
| `Product\ImportPreview` | Preview changes before confirming | `php artisan make:livewire Product\\ImportPreview` |
| `Product\ImportErrors` | Error log with row-level details | `php artisan make:livewire Product\\ImportErrors` |

**Actions:**
- `Product\ParseCsvImport`
- `Product\PreviewImport`
- `Product\ConfirmImport`
- `Product\ValidateImportRow`
- `Product\DownloadImportTemplate`

**Events:** `ImportStarted`, `ImportCompleted`, `ImportFailed`, `ImportRowSkipped`

**CSV Template:**

```csv
name,description,price,compare_price,category,image_url,tags,status
"Product A","Description here",5000,,Electronics,https://...,"new,featured",active
"Product B","Description here",3000,3500,Fashion,https://...,,active
```

**Supported Columns:**

| Column | Required | Notes |
|--------|----------|-------|
| `name` | Yes | Product name |
| `description` | No | HTML or plain text |
| `price` | Yes | Numeric, in business currency |
| `compare_price` | No | Striketrough price (sale indicator) |
| `category` | No | Matched to existing categories, created if new |
| `image_url` | No | Remote URL — image is downloaded and stored |
| `tags` | No | Comma-separated |
| `status` | No | `active` or `draft` (default: draft) |

**Import Flow:**
```
1. Download CSV template
2. Fill in product data
3. Upload CSV file
4. Preview — see products parsed with warnings (missing name, invalid price)
5. Confirm — queue background job
6. Notified on completion with summary:
   "150 products imported. 2 skipped (missing name). 0 errors."
```

**Queue Jobs:**

| Job | Queue | Purpose |
|-----|-------|---------|
| `ProcessProductImport` | `media` | Parse CSV, create products, download images |

**Tier Access:**
- **Free:** Not available
- **Professional:** Up to 100 products per import
- **Business:** Unlimited, category auto-matching, image download

---

## Database Tables

| Table | Purpose |
|-------|---------|
| `discounts` | Discount codes with rules |
| `discount_redemptions` | Per-code usage tracking |
| `promo_banners` | Banner content and scheduling |
| `lead_forms` | Custom form definitions |
| `lead_form_fields` | Individual form fields (type, options, order) |
| `lead_submissions` | Submitted form data (JSON) |
| `loyalty_programs` | Points rules per business |
| `loyalty_points_transactions` | Earn/redeem ledger |
| `referrals` | Referral links and tracking |
| `referral_rewards` | Reward payouts |
| `product_imports` | Bulk import records |
| `product_import_rows` | Per-row import status and errors |

---

## Route Summary

| Path | Component | Description |
|------|-----------|-------------|
| `/dashboard/marketing/discounts` | `Marketing\DiscountList` | Discount codes |
| `/dashboard/marketing/discounts/create` | `Marketing\CreateDiscount` | New discount |
| `/dashboard/marketing/banners` | `Marketing\PromoBannerEditor` | Promo banners |
| `/dashboard/marketing/leads` | `Marketing\LeadFormList` | Lead forms |
| `/dashboard/marketing/leads/submissions` | `Marketing\LeadFormSubmissions` | Inbox |
| `/dashboard/marketing/loyalty` | `Marketing\LoyaltySettings` | Points config |
| `/dashboard/marketing/referrals` | `Marketing\ReferralProgram` | Referral tracking |
| `/dashboard/products/import` | `Product\BulkImport` | CSV import |
| `/dashboard/products/import/history` | `Product\ImportHistory` | Past imports |
