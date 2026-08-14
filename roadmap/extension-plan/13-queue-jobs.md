# 13 — Queue Jobs & Background Processing

> Heavy work goes to the queue. Events trigger jobs. Jobs do the heavy lifting.

## What Already Exists

- Redis queue configured (`QUEUE_CONNECTION=redis`)
- 1 job exists: `SendWelcomeEmail`
- EventServiceProvider wired for `UserRegistered` → `SendWelcomeNotification`
- Jobs table migration exists

## What This Phase Adds

### Jobs to Create

| # | Job | Trigger | Purpose |
|---|-----|---------|---------|
| 1 | `GenerateBusinessContent` | BusinessCreated | AI-generate tagline, about, SEO for new business |
| 2 | `GenerateAIWebsite` | BusinessCreated (future) | Full AI website generation |
| 3 | `SyncBusinessToSearchIndex` | BusinessUpdated, ProductCreated, etc. | Sync to Meilisearch |
| 4 | `ProcessPayment` | OrderPlaced, SubscriptionStarted | Process via Paystack |
| 5 | `SendBookingReminder` | Scheduled (before appointment) | Send reminder notification |
| 6 | `GenerateSEOReport` | On-demand | Audit business SEO |
| 7 | `SendEmailCampaign` | CampaignScheduled | Send marketing emails |
| 8 | `GenerateAnalyticsReport` | Scheduled (daily) | Aggregate analytics data |
| 9 | `GenerateAIContent` | On-demand | Generate specific AI content |

### Event → Listener → Job Wiring

Update `EventServiceProvider`:

```php
protected $listen = [
    // Authentication
    UserRegistered::class => [
        SendWelcomeNotification::class,
    ],

    // Business
    BusinessCreated::class => [
        InitializeBusinessSettings::class,  // creates website + pages
    ],

    // Reviews
    ReviewSubmitted::class => [
        UpdateBusinessRating::class,
        NotifyBusinessOfReview::class,
    ],

    // Bookings
    BookingCreated::class => [
        NotifyBusinessOwner::class,
        SendCustomerConfirmation::class,
        UpdateBusinessAnalytics::class,
    ],

    // Orders
    OrderPlaced::class => [
        NotifyBusinessOfOrder::class,
        SendCustomerOrderUpdate::class,
        UpdateBusinessAnalytics::class,
    ],

    // Messages
    MessageSent::class => [
        NotifyBusinessOfMessage::class,
    ],

    // Search
    BusinessUpdated::class => [
        // SyncBusinessToSearchIndex handled by Observer
    ],
];
```

### Scheduled Tasks

```php
// app/Console/Kernel.php or routes/console.php
Schedule::job(new GenerateAnalyticsReport)->daily();
Schedule::job(new SendBookingReminder)->everyMinute(); // checks for upcoming bookings
Schedule::command('scout:reindex Business')->weekly();
```

---

## Checklist

- [ ] Create all 9 jobs listed above
- [ ] Wire events → listeners in EventServiceProvider
- [ ] Register scheduled tasks
- [ ] Test queue processing: `php artisan queue:work`
- [ ] Monitor failed jobs: `php artisan queue:failed`
