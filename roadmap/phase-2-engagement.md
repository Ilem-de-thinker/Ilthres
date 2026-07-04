# Phase 2 — Engagement

> **Goal:** Increase customer trust and merchant engagement through reviews, saved businesses, and marketing tools.  
> **Prerequisite:** Phase 1 (MVP) complete  
> **Livewire Components:** ~15  
> **Database Tables:** ~6

---

## Features

### 1. Business Verification

Verified badge on public profiles. Manual admin approval or document upload.

**Livewire Components:**
| Component | Command |
|-----------|---------|
| `Business\VerificationBadge` | `php artisan make:livewire Business\\VerificationBadge` |
| `Business\VerificationRequest` | `php artisan make:livewire Business\\VerificationRequest` |
| `Admin\VerifyBusinesses` | `php artisan make:livewire Admin\\VerifyBusinesses` |

**Models:** `Verification` (with `--migration`)

**Actions:** `Business\RequestVerification`, `Business\ApproveVerification`, `Business\RejectVerification`

**Events:** `VerificationRequested`, `VerificationApproved`, `VerificationRejected`

---

### 2. Reviews & Ratings

Customers leave star ratings and text reviews on business profiles.

**Livewire Components:**
| Component | Command |
|-----------|---------|
| `Review\ReviewList` | `php artisan make:livewire Review\\ReviewList` |
| `Review\CreateReview` | `php artisan make:livewire Review\\CreateReview` |
| `Review\ReviewSummary` | `php artisan make:livewire Review\\ReviewSummary` |
| `Review\ReportReview` | `php artisan make:livewire Review\\ReportReview` |

**Models:** `Review` (with `--migration`)

**Actions:** `Review\CreateReview`, `Review\UpdateReview`, `Review\DeleteReview`, `Review\ReportReview`, `Review\ModerateReview`

**Events:** `ReviewSubmitted`, `ReviewUpdated`, `ReviewDeleted`, `ReviewReported`, `ReviewModerated`

**Policies:** `ReviewPolicy` — customer creates, business owner can report, admin moderates

---

### 3. Saved Businesses

Customers save/bookmark businesses for quick access.

**Livewire Components:**
| Component | Command |
|-----------|---------|
| `Business\SaveButton` | `php artisan make:livewire Business\\SaveButton` |
| `Business\SavedList` | `php artisan make:livewire Business\\SavedList` |

**Models:** `SavedBusiness` (with `--migration`)

**Actions:** `Business\SaveBusiness`, `Business\UnsaveBusiness`

**Events:** `BusinessSaved`, `BusinessUnsaved`

---

### 4. Premium Themes

Expanded theme library available on Professional plan and above.

**Livewire Components:**
| Component | Command |
|-----------|---------|
| `Theme\PremiumThemeList` | `php artisan make:livewire Theme\\PremiumThemeList` |
| `Theme\ThemeMarketplace` | `php artisan make:livewire Theme\\ThemeMarketplace` |

**Actions:** `Theme\PurchaseTheme`, `Theme\InstallPremiumTheme`

**Events:** `PremiumThemePurchased`, `PremiumThemeInstalled`

---

### 5. Marketing Links

Merchants create short marketing links to track campaign performance.

**Livewire Components:**
| Component | Command |
|-----------|---------|
| `Marketing\MarketingLinks` | `php artisan make:livewire Marketing\\MarketingLinks` |
| `Marketing\CreateLink` | `php artisan make:livewire Marketing\\CreateLink` |
| `Marketing\LinkStats` | `php artisan make:livewire Marketing\\LinkStats` |

**Models:** `MarketingLink`, `LinkClick` (with `--migration`)

**Actions:** `Marketing\CreateLink`, `Marketing\DeleteLink`, `Marketing\TrackClick`

**Events:** `LinkClicked`, `LinkCreated`, `LinkDeleted`

---

### 6. QR Codes

Generate QR codes for business pages, products, and marketing links.

**Livewire Components:**
| Component | Command |
|-----------|---------|
| `Marketing\QrCodeGenerator` | `php artisan make:livewire Marketing\\QrCodeGenerator` |
| `Marketing\QrCodeList` | `php artisan make:livewire Marketing\\QrCodeList` |

**Models:** `QrCode` (with `--migration`)

**Actions:** `Marketing\GenerateQrCode`, `Marketing\DeleteQrCode`

**Events:** `QrCodeGenerated`, `QrCodeScanned`

---

### 7. Notifications Engine

In-app and email notifications for reviews, messages, subscription events.

**Livewire Components:**
| Component | Command |
|-----------|---------|
| `Notifications\NotificationBell` | `php artisan make:livewire Notifications\\NotificationBell` |
| `Notifications\NotificationList` | `php artisan make:livewire Notifications\\NotificationList` |
| `Notifications\NotificationPreferences` | `php artisan make:livewire Notifications\\NotificationPreferences` |

**Notifications (Laravel):**
- `ReviewReceived` — merchant gets notified of new review
- `BusinessSaved` — business was saved by a customer
- `SubscriptionExpiring` — 7 days before expiry
- `VerificationApproved` — business verified
- `NewFeatureAvailable` — product announcements

**Actions:** `Notifications\MarkAsRead`, `Notifications\MarkAllAsRead`, `Notifications\UpdatePreferences`

**Events:** `NotificationSent`, `NotificationRead`

---

## Database Tables (Phase 2)

| Table | Purpose |
|-------|---------|
| `verifications` | Business verification requests |
| `reviews` | Customer reviews and ratings |
| `saved_businesses` | User saved/bookmarked businesses |
| `marketing_links` | Custom tracking links |
| `link_clicks` | Individual link click events |
| `qr_codes` | Generated QR codes |

---

## Queue Jobs (Phase 2)

| Job | Queue | Purpose |
|-----|-------|---------|
| `SendReviewNotification` | `notifications` | Alert merchant of new review |
| `GenerateQrCodeImage` | `media` | Generate QR code image file |
| `ProcessLinkClick` | `analytics` | Record marketing link click |
