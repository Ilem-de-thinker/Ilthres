# Analytics & Insights — Per-Item Analytics, Reports, Benchmarking, Pixel Integration

> **New + expanded features** — click-level analytics per product/service, exportable reports (PDF/CSV), and competitor benchmarking within categories. Google/Meta Pixel integration is planned as post-MVP.
>
> **Livewire Components:** ~12  
> **Database Tables:** ~5

---

## 1. Per-Product / Per-Service Click Analytics (Both Business Types)

Beyond page-level views, track individual interactions with each product or service — clicks on the detail link, WhatsApp button clicks, image views, add-to-cart (or book-now) events.

**Business Type:** Both

**Livewire Components:**

| Component | Command |
|-----------|---------|
| `Analytics\ItemAnalytics` | Per-item metrics dashboard | `php artisan make:livewire Analytics\\ItemAnalytics` |
| `Analytics\ItemAnalyticsDetail` | Single item deep dive | `php artisan make:livewire Analytics\\ItemAnalyticsDetail` |
| `Analytics\ClickHeatmap` | Which items get most engagement | `php artisan make:livewire Analytics\\ClickHeatmap` |

**Actions:**
- `Analytics\RecordItemEvent`
- `Analytics\GetItemAnalytics`
- `Analytics\GetTopPerformingItems`
- `Analytics\GetItemTrend`

**Events:** `ProductClicked`, `ServiceClicked`, `ProductImageViewed`, `ServiceWhatsAppClicked`, `ProductDetailViewed`

**Schema (extends `analytics_events`):**

```sql
-- Phase 1 analytics_events table columns:
--   id, business_id, event_type, visitor_id, ip_address, user_agent, created_at

-- Additional columns for item-level analytics:
ALTER TABLE analytics_events ADD COLUMN item_type VARCHAR(20) NULL;   -- 'product' or 'service'
ALTER TABLE analytics_events ADD COLUMN item_id BIGINT UNSIGNED NULL; -- polymorphic FK
ALTER TABLE analytics_events ADD COLUMN metadata JSON NULL;           -- extra context
```

**Tracked Event Types:**

| Event Type | Triggered When | Business Type |
|------------|---------------|---------------|
| `product_detail_viewed` | Customer clicks product card | product_store |
| `product_image_viewed` | Customer opens product gallery | product_store |
| `product_whatsapp_clicked` | Customer clicks WhatsApp on product | product_store |
| `service_detail_viewed` | Customer clicks service card | service_business |
| `service_book_button_clicked` | Customer clicks "Book Now" | service_business |
| `service_whatsapp_clicked` | Customer clicks WhatsApp on service | service_business |

**Dashboard View:**
```
┌────────────────────────────────────────────────────┐
│ Top Products (This Month)         Filter: [30d ▼]  │
├──────────────┬──────────┬──────────┬───────────────┤
│ Product      │ Views    │ Clicks   │ Conv. Rate    │
├──────────────┼──────────┼──────────┼───────────────┤
│ Summer Dress │ 1,240    │ 89       │ 7.2%          │
│ Winter Jacket│ 980      │ 64       │ 6.5%          │
│ Running Shoes│ 720      │ 41       │ 5.7%          │
└──────────────┴──────────┴──────────┴───────────────┘
```

**Trend Chart:**
Each item gets a 30-day trend line showing views and clicks — helps merchants see which products are gaining or losing traction.

**Tier Access:**
- **Free:** Page-level analytics only (Phase 1)
- **Professional:** Per-item views and clicks, top performers list
- **Business:** Full item analytics, trend charts, CSV export

---

## 2. Exportable Reports (PDF / CSV) (Both Business Types)

> Expanded from [Phase 3 — Advanced Reports](./phase-3-advanced.md). This section adds PDF rendering and scheduled email delivery.

Generate and download analytics reports in PDF and CSV formats. Schedule recurring reports via email.

**Business Type:** Both

**Livewire Components:**

| Component | Command |
|-----------|---------|
| `Analytics\ReportBuilder` | Select metrics + date range + format | `php artisan make:livewire Analytics\\ReportBuilder` |
| `Analytics\ReportList` | Saved + scheduled reports | `php artisan make:livewire Analytics\\ReportList` |
| `Analytics\ReportViewer` | View report in browser | `php artisan make:livewire Analytics\\ReportViewer` |
| `Analytics\ReportSchedule` | Set recurring delivery | `php artisan make:livewire Analytics\\ReportSchedule` |

**Models:** `SavedReport`, `ScheduledReport` (with `--migration`)

**Actions:**
- `Analytics\BuildReport`
- `Analytics\ExportCsv`
- `Analytics\ExportPdf`
- `Analytics\SaveReport`
- `Analytics\ScheduleReport`
- `Analytics\CancelScheduledReport`

**Events:** `ReportGenerated`, `ReportExported`, `ReportScheduled`, `ReportDelivered`

**Report Types:**

| Report | Metrics Included | Channels |
|--------|-----------------|----------|
| Business Overview | Views, visitors, top items, WhatsApp clicks | PDF, CSV |
| Product Performance | Per-product views, clicks, conversion rate | PDF, CSV |
| Traffic Report | Daily visitors, referral sources, devices | PDF, CSV |
| Time-of-Day | Visitor hours, peak times | PDF, CSV |
| Custom Report | User-selected metrics | PDF, CSV |

**Scheduling Options:**
```
Frequency: daily | weekly | monthly
Day:       Monday–Sunday (for weekly)
Date:      1st–28th (for monthly)
Format:    PDF | CSV | both
Time:      HH:MM (delivery time)
Recipients: email addresses (up to 5)
```

**PDF Report Styling:**
- Brand colors (matches merchant's theme)
- Charts rendered as embedded images (chart image generated server-side)
- Tables with alternating row colors
- Header: business name + logo
- Footer: generated date, "Powered by Ilthres Market"

**Queue Jobs:**

| Job | Queue | Purpose |
|-----|-------|---------|
| `GenerateReportExport` | `media` | Async PDF/CSV generation |
| `SendScheduledReport` | `emails` | Email recurring reports |
| `RenderReportChart` | `media` | Generate chart images for PDF |

**Tier Access:**
- **Free:** Not available
- **Professional:** CSV export, 1 saved report, no scheduling
- **Business:** PDF + CSV, unlimited saved reports, scheduled delivery

---

## 3. Competitor Benchmarking (Both Business Types)

Compare a business's analytics against others in the same category. Leverages the Explore layer's data to show relative performance.

**Business Type:** Both

**Livewire Components:**

| Component | Command |
|-----------|---------|
| `Analytics\BenchmarkOverview` | Category rank + comparison cards | `php artisan make:livewire Analytics\\BenchmarkOverview` |
| `Analytics\BenchmarkDetail` | Per-metric deep dive | `php artisan make:livewire Analytics\\BenchmarkDetail` |
| `Analytics\BenchmarkTrend` | Your trend vs category average | `php artisan make:livewire Analytics\\BenchmarkTrend` |

**Actions:**
- `Analytics\GetBenchmarkData`
- `Analytics\GetCategoryRank`
- `Analytics\GetMetricPercentile`

**Events:** `BenchmarkDataRefreshed`

**Benchmark Metrics:**

| Metric | Description | Source |
|--------|-------------|--------|
| Page views | 30-day total vs category average | Analytics events |
| Unique visitors | 30-day unique IPs vs category average | Analytics events |
| Engagement rate | Clicks per visit vs category average | Analytics events |
| WhatsApp CTR | WhatsApp clicks per view | Analytics events |
| Listing rank | Position in explore search results | Search index |
| Review score | Average rating vs category average | Reviews |
| Response time | Time to reply to leads | Lead submissions |

**Dashboard View:**
```
┌────────────────────────────────────────────────────┐
│ Benchmark: Fashion & Apparel                       │
├──────────────┬──────────┬──────────┬───────────────┤
│ Metric       │ You      │ Avg      │ Percentile     │
├──────────────┼──────────┼──────────┼───────────────┤
│ Page Views   │ 4,250    │ 2,100    │ Top 15%        │
│ Engagement   │ 8.2%     │ 5.1%     │ Top 10%        │
│ Review Score │ 4.5 ★    │ 3.9 ★    │ Top 5%         │
│ Rank         │ #3       │ —        │ In "Fashion"   │
└──────────────┴──────────┴──────────┴───────────────┘
```

**Privacy & Anonymization:**
- Competitors are never named — only aggregate/category averages shown
- Individual competitor data is never exposed
- Merchant sees their own percentile rank only
- Minimum 10 businesses in a category before benchmarks appear

**Data Refresh:** Benchmarks computed nightly via queue job.

**Queue Jobs:**

| Job | Queue | Purpose |
|-----|-------|---------|
| `ComputeBenchmarks` | `analytics` | Nightly benchmark calculation |
| `RefreshCategoryRankings` | `search` | Update explore ranking positions |

**Tier Access:**
- **Free:** Not available
- **Professional:** Basic overview (views, engagement percentile)
- **Business:** Full benchmarking (all metrics, trend comparison, rank tracking)

---

## 4. Google / Meta Pixel Integration (Post-MVP — Both Business Types)

> **Post-MVP** — not included in initial release. Intended for a future iteration after core analytics, reports, and benchmarks are stable.

Merchants running their own ads can install tracking pixels to measure ad performance — conversions, traffic, and retargeting.

**Business Type:** Both

**Livewire Components:**

| Component | Command |
|-----------|---------|
| `Analytics\PixelSettings` | Pixel management dashboard | `php artisan make:livewire Analytics\\PixelSettings` |
| `Analytics\PixelInstall` | Setup guide + code snippet | `php artisan make:livewire Analytics\\PixelInstall` |
| `Analytics\PixelEvents` | Tracked events log | `php artisan make:livewire Analytics\\PixelEvents` |
| `Analytics\PixelTestMode` | Test pixel before going live | `php artisan make:livewire Analytics\\PixelTestMode` |

**Models:** `TrackingPixel` (with `--migration`)

**Schema:**
```sql
tracking_pixels
├── id
├── business_id
├── platform         -- 'google_ads' | 'meta' | 'tiktok'
├── pixel_id         -- The ID provided by the platform
├── pixel_type       -- 'google' | 'meta' | 'tiktok'
├── is_active
├── events           -- JSON: which events to fire
├── verified_at      -- null until test event received
└── timestamps
```

**Actions:**
- `Analytics\InstallPixel`
- `Analytics\UpdatePixel`
- `Analytics\RemovePixel`
- `Analytics\VerifyPixel`
- `Analytics\TogglePixel`
- `Analytics\GetPixelSnippet` — raw JS snippet for manual install

**Events:** `PixelInstalled`, `PixelVerified`, `PixelRemoved`, `PixelFired`

**Supported Platforms & Events:**

| Platform | Events Tracked | Use Case |
|----------|---------------|----------|
| **Google Ads** | `PageView`, `ViewItem`, `AddToCart`, `Purchase`, `Lead` | Conversion tracking for Google campaigns |
| **Meta (Facebook)** | `PageView`, `ViewContent`, `AddToCart`, `Purchase`, `Lead`, `Contact` | Retargeting + conversion for Meta ads |
| **TikTok** | `PageView`, `ViewContent`, `AddToCart`, `Purchase` | Conversion tracking for TikTok ads |

**Automatic Event Firing:**
```javascript
// Injected into public business page <head>
// Fires automatically on:
// - Page load → PageView
// - Product card click → ViewContent
// - WhatsApp click → Contact
// - Booking form submit → Lead

// Merchant can also add custom events via the dashboard.
```

**Dashboard Setup Flow:**
```
1. Go to Dashboard → Analytics → Tracking Pixels
2. Click "Add Pixel"
3. Select platform (Google / Meta / TikTok)
4. Enter Pixel ID (from Google Ads / Meta Business)
5. Select events to track (or use defaults)
6. Click "Install" → snippet injected into public pages
7. Test mode: visit your page → verify pixel fires
8. Done — pixel goes live
```

**Test & Verify:**
- "Test Mode" — shows live event log in dashboard
- "Verify" — sends test event, confirms pixel is working
- "Preview" — shows where pixel fires on page (via browser extension)

**Tier Access:**
- **Free:** Not available
- **Professional:** 1 pixel (Google or Meta), standard events
- **Business:** 3 pixels, all platforms, custom events, test mode

---

## Database Tables

| Table | Purpose |
|-------|---------|
| `saved_reports` | Saved report configurations |
| `scheduled_reports` | Recurring report schedules |
| `tracking_pixels` | Google / Meta / TikTok pixel configs |
| _(analytics_events extended)_ | Existing table — add `item_type`, `item_id`, `metadata` columns |

---

## Route Summary

| Path | Component | Description |
|------|-----------|-------------|
| `/dashboard/analytics/items` | `Analytics\ItemAnalytics` | Per-product/service analytics |
| `/dashboard/analytics/items/{item}` | `Analytics\ItemAnalyticsDetail` | Single item deep dive |
| `/dashboard/analytics/reports` | `Analytics\ReportList` | Saved + scheduled reports |
| `/dashboard/analytics/reports/build` | `Analytics\ReportBuilder` | Build new report |
| `/dashboard/analytics/benchmarks` | `Analytics\BenchmarkOverview` | Competitor benchmarks |
| `/dashboard/analytics/pixels` | `Analytics\PixelSettings` | Tracking pixel management |
