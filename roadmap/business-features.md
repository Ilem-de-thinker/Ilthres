# Business Features — Booking, Reviews, QR Codes, WhatsApp, Multi-Location

> **Cross-phase feature collection** — covers appointment booking, reviews & ratings, QR codes, WhatsApp catalog/auto-reply, and multi-location support. Some are already planned in existing phases; this document consolidates and extends them.
>
> **Livewire Components:** ~20  
> **Database Tables:** ~10

---

## 1. Booking / Appointment Scheduling Widget (Service Businesses Only)

> Already planned in [Phase 3 — Appointments](./phase-3-advanced.md). This section expands the widget scope.

A public-facing booking widget embedded on service business profiles. Customers see real-time availability and book without leaving the page.

**Business Type:** `service_business` only

**Livewire Components:**

| Component | Command |
|-----------|---------|
| `Appointment\BookingWidget` | Public widget — shows available slots for a service | `php artisan make:livewire Appointment\\BookingWidget` |
| `Appointment\BookingCalendar` | Month/week/day calendar picker | `php artisan make:livewire Appointment\\BookingCalendar` |
| `Appointment\BookingForm` | Customer info + confirmation | `php artisan make:livewire Appointment\\BookingForm` |
| `Appointment\AppointmentCalendar` | Merchant dashboard calendar | `php artisan make:livewire Appointment\\AppointmentCalendar` |
| `Appointment\AppointmentList` | Merchant upcoming/past list | `php artisan make:livewire Appointment\\AppointmentList` |
| `Appointment\AvailabilityManager` | Weekly schedule + exceptions | `php artisan make:livewire Appointment\\AvailabilityManager` |
| `Appointment\CreateAppointment` | Merchant-side manual booking | `php artisan make:livewire Appointment\\CreateAppointment` |

**Models:** `Appointment`, `AvailabilitySlot`, `BookingSchedule`, `BookingBlock` (blocked dates)

**Actions:**
- `Appointment\CheckAvailability` — returns open slots for a date
- `Appointment\CreateAppointment` — customer books
- `Appointment\RescheduleAppointment`
- `Appointment\CancelAppointment` — with cancellation reason
- `Appointment\ConfirmAppointment` — merchant confirms
- `Appointment\UpdateAvailability` — set weekly hours + exceptions
- `Appointment\BlockTime` — block-off personal time

**Events:** `AppointmentBooked`, `AppointmentRescheduled`, `AppointmentCancelled`, `AppointmentConfirmed`, `AvailabilityUpdated`, `AppointmentNoShow`

**Policies:** `AppointmentPolicy` — customer creates own, merchant manages all under business

**Queue Jobs:**

| Job | Queue | Purpose |
|-----|-------|---------|
| `SendAppointmentReminder` | `notifications` | 24h and 1h before appointment |
| `SendAppointmentConfirmation` | `emails` | Booking confirmation with calendar (.ics) |
| `SyncGoogleCalendar` | `default` | Two-way Google Calendar sync |

**Widget Embeds:**
```blade
{{-- Embedded on public profile for service businesses --}}
@if ($business->type === 'service_business')
    <livewire:appointment.booking-widget :business="$business" />
@endif
```

**Booking Flow:**
```
1. Customer visits business page
2. Sees "Book Appointment" button
3. Picks a service → date → available time slot
4. Fills name, email, phone (optional notes)
5. Confirms → receives confirmation email + .ics
6. Merchant notified via dashboard + notification
7. 24h before: reminder sent to both parties
```

---

## 2. Customer Reviews & Ratings (Both Business Types)

> Already planned in [Phase 2 — Reviews & Ratings](./phase-2-engagement.md). This section expands the scope.

Customers leave star ratings (1–5) and text reviews on business profiles. Reviews appear on the public profile and in search/explore results.

**Business Type:** Both

**Livewire Components (extended):**

| Component | Command |
|-----------|---------|
| `Review\ReviewList` | Public review list with sorting | `php artisan make:livewire Review\\ReviewList` |
| `Review\CreateReview` | Star rating + text form | `php artisan make:livewire Review\\CreateReview` |
| `Review\ReviewSummary` | Average rating, breakdown bar chart | `php artisan make:livewire Review\\ReviewSummary` |
| `Review\ReviewMedia` | Photo/video attachment to reviews | `php artisan make:livewire Review\\ReviewMedia` |
| `Review\ReportReview` | Flag inappropriate review | `php artisan make:livewire Review\\ReportReview` |
| `Review\MerchantReply` | Business owner can reply to reviews | `php artisan make:livewire Review\\MerchantReply` |

**Models:** `Review`, `ReviewReply`, `ReviewFlag`

**Actions:**
- `Review\CreateReview`
- `Review\UpdateReview`
- `Review\DeleteReview`
- `Review\ReportReview`
- `Review\ModerateReview` — admin approves/hides
- `Review\ReplyToReview` — merchant public reply

**Events:** `ReviewSubmitted`, `ReviewUpdated`, `ReviewDeleted`, `ReviewReported`, `ReviewModerated`, `ReviewReplyAdded`

**Rating Display:**
```
★★★★☆  4.2 (128 reviews)
```

**Search Integration:**
- Businesses with 4.5+ rating get a "Top Rated" badge in explore results
- Explore filters: "4+ stars", "5 stars only"
- Reviews factor into explore ranking algorithm

---

## 3. QR Code Generator (Both Business Types)

> Already planned in [Phase 2 — QR Codes](./phase-2-engagement.md). This section adds detail.

Generate scannable QR codes for business pages, product pages, and marketing links. Bridges offline marketing (flyers, packaging, business cards) to the online profile.

**Business Type:** Both

**Livewire Components:**

| Component | Command |
|-----------|---------|
| `Marketing\QrCodeGenerator` | Generate QR code for any page | `php artisan make:livewire Marketing\\QrCodeGenerator` |
| `Marketing\QrCodeList` | Saved QR codes with download | `php artisan make:livewire Marketing\\QrCodeList` |
| `Marketing\QrCodeStats` | Scan count + location data | `php artisan make:livewire Marketing\\QrCodeStats` |
| `Marketing\QrCodeDesigner` | Custom colors, logo overlay | `php artisan make:livewire Marketing\\QrCodeDesigner` |

**Models:** `QrCode`, `QrCodeScan`

**Actions:**
- `Marketing\GenerateQrCode` — generates image, stores SVG/PNG
- `Marketing\DeleteQrCode`
- `Marketing\RecordScan` — tracks scan event
- `Marketing\CustomizeQrCode` — colors, logo, frame

**Events:** `QrCodeGenerated`, `QrCodeDeleted`, `QrCodeScanned`

**QR Code Types:**

| Type | Links To | Use Case |
|------|----------|---------|
| Business Profile | `/business/{slug}` | Flyers, business cards |
| Product | `/business/{slug}/product/{id}` | Product packaging |
| Service | `/business/{slug}/service/{id}` | Service brochure |
| Marketing Link | Tracked redirect | Campaign-specific traffic |
| WhatsApp | `wa.me/{number}` | Direct chat from offline ads |

**Queue Jobs:**

| Job | Queue | Purpose |
|-----|-------|---------|
| `GenerateQrCodeImage` | `media` | Async QR image generation |
| `ProcessQrCodeScan` | `analytics` | Record scan with referrer |

---

## 4. WhatsApp Catalog Sync & Auto-Reply Templates (Both Business Types)

> **New feature** — extends the Phase 1 WhatsApp button into a full integration.

Sync the merchant's product/service catalog to WhatsApp Business API and create auto-reply templates for common customer questions.

**Business Type:** Both

**Livewire Components:**

| Component | Command |
|-----------|---------|
| `WhatsApp\WhatsAppSettings` | Connect WhatsApp Business account | `php artisan make:livewire WhatsApp\\WhatsAppSettings` |
| `WhatsApp\CatalogSync` | Sync products/services to WhatsApp | `php artisan make:livewire WhatsApp\\CatalogSync` |
| `WhatsApp\AutoReplyTemplates` | Manage message templates | `php artisan make:livewire WhatsApp\\AutoReplyTemplates` |
| `WhatsApp\TemplateEditor` | Edit template with variables | `php artisan make:livewire WhatsApp\\TemplateEditor` |
| `WhatsApp\ChatWidget` | Public chat bubble with quick replies | `php artisan make:livewire WhatsApp\\ChatWidget` |
| `WhatsApp\MessageLog` | Recent conversations log | `php artisan make:livewire WhatsApp\\MessageLog` |

**Models:** `WhatsAppSetting`, `WhatsAppTemplate`, `WhatsAppCatalogItem`, `WhatsAppMessage`

**Actions:**
- `WhatsApp\ConnectAccount` — OAuth flow to WhatsApp Business API
- `WhatsApp\SyncCatalog` — push products/services to WhatsApp catalog
- `WhatsApp\CreateTemplate` — create auto-reply template
- `WhatsApp\UpdateTemplate`
- `WhatsApp\DeleteTemplate`
- `WhatsApp\SendMessage` — send via API
- `WhatsApp\ProcessWebhook` — handle incoming messages

**Events:** `WhatsAppConnected`, `WhatsAppDisconnected`, `CatalogSynced`, `TemplateCreated`, `MessageReceived`

**Queue Jobs:**

| Job | Queue | Purpose |
|-----|-------|---------|
| `SyncWhatsAppCatalog` | `default` | Push catalog to WhatsApp API |
| `ProcessWhatsAppWebhook` | `webhooks` | Handle incoming WhatsApp messages |
| `SendWhatsAppNotification` | `notifications` | Order/appointment confirmations |

**Template Variables:**
```
{{business_name}} — auto-filled business name
{{customer_name}} — auto-filled customer name
{{product_name}} — product/service name
{{price}} — item price
{{hours}} — business hours
{{link}} — business page link
```

**Default Templates:**
```
1. Greeting: "Hi {{customer_name}}! Welcome to {{business_name}}. How can we help?"
2. Hours: "We're open {{hours}}. Check our catalog here: {{link}}"
3. Pricing: "{{product_name}} is {{price}}. Want to see more? {{link}}"
```

**Tier Access:**
- **Free:** Basic WhatsApp button (Phase 1), 3 templates
- **Professional:** Catalog sync, 10 templates, message log
- **Business:** Unlimited templates, auto-reply bot, analytics

---

## 5. Multi-Location Support (Both Business Types)

> **New feature** — for businesses with more than one branch.

A single business account can manage multiple locations. Each location has its own address, phone, hours, products/services, and public profile under a shared brand.

**Business Type:** Both

**Livewire Components:**

| Component | Command |
|-----------|---------|
| `Business\LocationList` | All locations overview map + list | `php artisan make:livewire Business\\LocationList` |
| `Business\CreateLocation` | Add a new branch/location | `php artisan make:livewire Business\\CreateLocation` |
| `Business\EditLocation` | Edit location details | `php artisan make:livewire Business\\EditLocation` |
| `Business\LocationSwitcher` | Dashboard context switcher for locations | `php artisan make:livewire Business\\LocationSwitcher` |
| `Business\LocationHours` | Per-location business hours | `php artisan make:livewire Business\\LocationHours` |
| `Business\LocationPublicPage` | Per-location public profile | `php artisan make:livewire Business\\LocationPublicPage` |

**Models:** `Location` (with `--migration`)

**Schema Design:**
```
locations
├── id
├── business_id
├── name              # "Downtown Branch", "Mall Location"
├── slug              # business-slug-downtown
├── email
├── phone
├── address
├── city
├── state
├── country
├── latitude
├── longitude
├── about
├── is_main           # one location is the "main" branch
├── is_active
├── sort_order
└── timestamps
```

**Key Decisions:**

| Decision | Choice | Rationale |
|----------|--------|-----------|
| Location vs separate business | One business, many locations | Shared brand, subscription, staff; only address/contact differs |
| Products/Services | Can be shared or per-location | Toggle in settings: "Same products everywhere" vs "Per-location inventory" |
| Public URL | `/business/{slug}` redirects to main; `/business/{slug}/{location-slug}` for specific | Customers often want nearest branch |
| Dashboard | Location switcher in sidebar, same as business switcher | Clean UX — merchant picks a location, sees its data |
| Map view | All locations shown on one map in public profile | Customers find nearest branch |
| Subscription | Single subscription covers all locations | One billing entity |

**Actions:**
- `Business\CreateLocation`
- `Business\UpdateLocation`
- `Business\DeleteLocation`
- `Business\SetMainLocation`
- `Business\ToggleLocationStatus`

**Events:** `LocationCreated`, `LocationUpdated`, `LocationDeleted`, `MainLocationChanged`

**Policies:** `LocationPolicy` — business owner manages all locations

**Public Profile Behavior:**
```
/business/{slug}           → Main location (or nearest based on geolocation)
/business/{slug/downtown   → Specific location page

Each location page shows:
  - Location-specific address, phone, hours
  - Location-specific products/services (if per-location mode)
  - Map with all branches listed below
```

**Dashboard Location Switcher:**
```
[ Ilthres Market ]  ▼  [ Downtown  ▼ ]
                     ├── Downtown
                     ├── Mall Branch
                     └── Airport Kiosk
```

**Tier Access:**
- **Free:** 1 location
- **Professional:** Up to 3 locations
- **Business:** Unlimited locations

---

## Database Tables

| Table | Purpose | Phase |
|-------|---------|-------|
| `appointments` | Customer bookings | Phase 3 |
| `availability_slots` | Available time slots per service | Phase 3 |
| `booking_schedules` | Weekly schedule templates | Phase 3 |
| `booking_blocks` | Blocked dates/times | This doc |
| `reviews` | Customer reviews and ratings | Phase 2 |
| `review_replies` | Merchant replies to reviews | This doc |
| `review_flags` | Flagged reviews for moderation | This doc |
| `qr_codes` | Generated QR codes | Phase 2 |
| `qr_code_scans` | QR scan events | This doc |
| `whats_app_settings` | WhatsApp Business API config | This doc |
| `whats_app_templates` | Auto-reply message templates | This doc |
| `whats_app_catalog_items` | Synced product/service catalog | This doc |
| `whats_app_messages` | Message log | This doc |
| `locations` | Multi-location branches | This doc |

---

## Route Summary

| Path | Component | Description |
|------|-----------|-------------|
| `/business/{business}/book/{service}` | `Appointment\BookingWidget` | Public booking (service only) |
| `/dashboard/appointments` | `Appointment\AppointmentCalendar` | Merchant calendar |
| `/dashboard/appointments/availability` | `Appointment\AvailabilityManager` | Set hours |
| `/dashboard/whatsapp` | `WhatsApp\WhatsAppSettings` | WhatsApp integration |
| `/dashboard/whatsapp/templates` | `WhatsApp\AutoReplyTemplates` | Auto-reply editor |
| `/dashboard/locations` | `Business\LocationList` | Manage branches |
| `/dashboard/locations/create` | `Business\CreateLocation` | Add branch |
| `/dashboard/locations/{location}/edit` | `Business\EditLocation` | Edit branch |
| `/business/{business}/{location}` | `Business\LocationPublicPage` | Location profile |
| `/dashboard/reviews` | `Review\ReviewList` | Merchant review management |
| `/dashboard/qrcodes` | `Marketing\QrCodeList` | QR code management |
