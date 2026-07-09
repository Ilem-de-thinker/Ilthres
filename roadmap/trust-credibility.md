# Trust & Credibility — Verified Badge, Testimonials

> **New + expanded features** — verified business badge (already planned in Phase 2, expanded here with KYC/document flow) and a testimonials showcase section on public profiles.
>
> **Livewire Components:** ~8  
> **Database Tables:** ~4

---

## 1. Verified Business Badge (Both Business Types)

> Already planned in [Phase 2 — Business Verification](./phase-2-engagement.md). This section expands the scope with document upload, KYC checks, and badge display.

A verified badge (✓) displayed on the public business profile and in explore/search results. Increases customer trust and improves search ranking.

**Business Type:** Both

**Livewire Components (extended):**

| Component | Command |
|-----------|---------|
| `Business\VerificationBadge` | Badge display on public profile | `php artisan make:livewire Business\\VerificationBadge` |
| `Business\VerificationRequest` | Merchant submits for verification | `php artisan make:livewire Business\\VerificationRequest` |
| `Business\VerificationStatus` | Current verification status dashboard | `php artisan make:livewire Business\\VerificationStatus` |
| `Business\VerificationDocuments` | Upload business documents | `php artisan make:livewire Business\\VerificationDocuments` |
| `Admin\VerifyBusinesses` | Admin review queue | `php artisan make:livewire Admin\\VerifyBusinesses` |
| `Admin\VerificationDetail` | Single verification review | `php artisan make:livewire Admin\\VerificationDetail` |

**Models:** `Verification`, `VerificationDocument` (with `--migration`)

**Schema:**
```sql
verifications
├── id
├── business_id
├── status              -- pending | approved | rejected
├── verification_type   -- manual | kyc | document
├── reviewed_by         -- admin user id (nullable)
├── reviewed_at
├── rejection_reason    -- if rejected
└── timestamps

verification_documents
├── id
├── verification_id
├── type                -- cac_certificate | business_permit | id_card | utility_bill
├── file_path
├── status              -- pending | approved | rejected
└── timestamps
```

**Verification Levels:**

| Level | Requirement | Badge | Benefit |
|-------|-------------|-------|---------|
| **Basic** | Email + phone confirmed | ✓ Basic | — |
| **Document** | Business registration document uploaded | ✓ Document | Priority in search |
| **KYC** | ID verification + business proof | ✓ Verified | Top rank in explore, "Verified" filter |

**Verification Flow:**
```
1. Merchant navigates to Dashboard → Settings → Verification
2. Sees current status: "Not verified" or "Pending review"
3. Submits: choose verification type
   a. Document: upload CAC certificate, business permit, or utility bill
   b. KYC: upload government ID + selfie + business proof
4. Admin reviews in admin panel
5. Status updated:
   - Approved → badge appears on public profile
   - Rejected → reason shown, merchant can re-submit
6. Notification sent to merchant
```

**Badge Display:**
```blade
{{-- On public business profile --}}
<h1 class="text-2xl font-bold">
    {{ $business->name }}
    @if ($business->isVerified())
        <span class="inline-flex items-center text-blue-500" title="Verified Business">
            ✓
        </span>
    @endif
</h1>

{{-- In explore/search results --}}
<div class="flex items-center gap-1">
    <span>{{ $business->name }}</span>
    @if ($business->isVerified())
        <span class="text-blue-500 text-sm">✓</span>
    @endif
</div>
```

**Search/Explore Impact:**
- Verified businesses get a "Verified" filter chip
- Verified badge displayed in search result cards
- Verified businesses rank higher in search results (tiebreaker)
- "Only Verified" toggle in explore filters

**Queue Jobs:**

| Job | Queue | Purpose |
|-----|-------|---------|
| `ProcessVerificationDocuments` | `media` | Validate + store uploaded docs |
| `SendVerificationNotification` | `notifications` | Notify merchant of status change |

**Tier Access:**
- **Free:** Basic verification (email + phone)
- **Professional:** Document verification
- **Business:** KYC verification + priority review (48h turnaround)

---

## 2. Testimonials Showcase Section (Both Business Types)

A dedicated section on the public business profile where the merchant can showcase selected customer testimonials — pulled from reviews or manually added.

**Business Type:** Both

**Livewire Components:**

| Component | Command |
|-----------|---------|
| `Business\TestimonialList` | Public testimonial carousel/grid | `php artisan make:livewire Business\\TestimonialList` |
| `Business\TestimonialManager` | Merchant picks which reviews to feature | `php artisan make:livewire Business\\TestimonialManager` |
| `Business\TestimonialForm` | Manual testimonial entry | `php artisan make:livewire Business\\TestimonialForm` |

**Models:** `Testimonial` (with `--migration`)

**Schema:**
```sql
testimonials
├── id
├── business_id
├── source              -- 'review' | 'manual'
├── review_id           -- FK to reviews (nullable, if sourced from review)
├── customer_name
├── customer_title      -- optional: "CEO at ABC Corp"
├── customer_photo      -- optional avatar
├── content             -- testimonial text
├── rating              -- 1-5 stars (optional)
├── sort_order
├── is_featured
├── is_active
└── timestamps
```

**Actions:**
- `Business\AddTestimonialFromReview` — pick a review to feature
- `Business\CreateTestimonial` — add manually
- `Business\UpdateTestimonial`
- `Business\DeleteTestimonial`
- `Business\ReorderTestimonials`

**Events:** `TestimonialAdded`, `TestimonialUpdated`, `TestimonialRemoved`

**Testimonial Sources:**

| Source | How It Works |
|--------|-------------|
| **From Review** | Merchant browses their reviews, clicks "Feature as Testimonial" on great ones. Pre-filled with review content + customer name. Customer can opt out. |
| **Manual Entry** | Merchant writes a testimonial manually — useful for offline clients, WhatsApp conversations, email praise. |
| **Auto-Feature** | Optional: automatically feature 5-star reviews as testimonials. |

**Public Display:**
```blade
{{-- Testimonials section on public profile --}}
@if ($testimonials->isNotEmpty())
    <section class="my-8">
        <h2 class="text-xl font-bold mb-4">What Our Customers Say</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach ($testimonials as $testimonial)
                <div class="p-4 border rounded-lg">
                    <div class="flex items-center gap-2 mb-2">
                        @if ($testimonial->customer_photo)
                            <img src="{{ $testimonial->customer_photo }}" class="w-10 h-10 rounded-full">
                        @endif
                        <div>
                            <p class="font-semibold">{{ $testimonial->customer_name }}</p>
                            @if ($testimonial->customer_title)
                                <p class="text-sm text-gray-500">{{ $testimonial->customer_title }}</p>
                            @endif
                        </div>
                    </div>
                    @if ($testimonial->rating)
                        <div class="text-yellow-400 mb-1">
                            @for ($i = 1; $i <= 5; $i++)
                                {{ $i <= $testimonial->rating ? '★' : '☆' }}
                            @endfor
                        </div>
                    @endif
                    <p class="text-gray-700 italic">"{{ $testimonial->content }}"</p>
                </div>
            @endforeach
        </div>
    </section>
@endif
```

**Carousel Mode (optional):**
- Horizontal scroll with left/right arrows
- Auto-rotate every 5 seconds (can pause on hover)
- Shows 1–3 testimonials at a time (responsive)

**Dashboard Management:**
```
┌─────────────────────────────────────────────────┐
│ Testimonials (6)                   [+ Add New]  │
├─────────────────────────────────────────────────┤
│ ═══ ═══                                          │ ← Drag to reorder
│ ┌─────────────────────────────────────────┐     │
│ │ "Amazing service! Highly recommend."   │     │
│ │ — Sarah J. ★★★★☆                       │     │
│ │ [Edit] [Unfeature] [Delete]            │     │
│ └─────────────────────────────────────────┘     │
│ ┌─────────────────────────────────────────┐     │
│ │ "Best prices in town."                  │     │
│ │ — Michael R. ★★★★★                      │     │
│ │ [Edit] [Unfeature] [Delete]            │     │
│ └─────────────────────────────────────────┘     │
│ ...                                              │
│ [Import from Reviews →]                         │
└─────────────────────────────────────────────────┘
```

**Tier Access:**
- **Free:** 3 testimonials, text only, no photos
- **Professional:** 10 testimonials, photos allowed, reorderable
- **Business:** Unlimited testimonials, carousel mode, auto-feature from reviews

---

## Integration with Reviews

Testimonials and reviews are connected but separate:

```
Reviews:   Customer-driven, all ratings visible on profile
           ↓ "Feature as Testimonial"
Testimonials: Merchant-curated, best reviews showcased prominently
```

- A review can be featured as a testimonial (one-to-one)
- Featuring a review does not delete it from the reviews list
- Un-featuring reverts it to a normal review
- Customer can request removal of a featured testimonial (reverts to review)

---

## Database Tables

| Table | Purpose |
|-------|---------|
| `verifications` | Business verification requests/status |
| `verification_documents` | Uploaded business documents |
| `testimonials` | Curated customer testimonials |

---

## Route Summary

| Path | Component | Description |
|------|-----------|-------------|
| `/dashboard/settings/verification` | `Business\VerificationStatus` | Verification status & submit |
| `/dashboard/settings/verification/documents` | `Business\VerificationDocuments` | Upload docs |
| `/dashboard/testimonials` | `Business\TestimonialManager` | Manage featured testimonials |
| `/admin/verifications` | `Admin\VerifyBusinesses` | Admin review queue |
| `/admin/verifications/{id}` | `Admin\VerificationDetail` | Single review |
