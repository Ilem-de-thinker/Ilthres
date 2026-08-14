# 05 — Reviews & Reputation

> Build trust through verified reviews. Business trust score based on reviews + verification + response rate.

## What Already Exists

- No reviews system yet
- `ReviewReceived` notification class exists (ready to use)
- Business has `is_verified` field

## What This Phase Adds

### 1. Reviews Table

```sql
CREATE TABLE reviews (
    id              BIGSERIAL PRIMARY KEY,
    business_id     BIGINT REFERENCES businesses(id) ON DELETE CASCADE,
    user_id         BIGINT REFERENCES users(id) ON DELETE SET NULL,
    booking_id      BIGINT REFERENCES bookings(id) ON DELETE SET NULL,

    rating          INTEGER NOT NULL CHECK (rating >= 1 AND rating <= 5),
    title           VARCHAR(255),
    body            TEXT,

    photos          JSONB,   -- [{"url":"...","caption":"..."}]

    business_response TEXT,
    responded_at    TIMESTAMP,

    status          ENUM('pending','approved','rejected','flagged') DEFAULT 'pending',
    helpful_count   INTEGER DEFAULT 0,

    created_at      TIMESTAMP DEFAULT NOW(),
    updated_at      TIMESTAMP DEFAULT NOW()
);

CREATE TABLE review_votes (
    id              BIGSERIAL PRIMARY KEY,
    review_id       BIGINT REFERENCES reviews(id) ON DELETE CASCADE,
    user_id         BIGINT REFERENCES users(id) ON DELETE CASCADE,
    is_helpful      BOOLEAN,
    UNIQUE(review_id, user_id)
);
```

### 2. Files to Create

- `database/migrations/xxxx_create_reviews_table.php`
- `database/migrations/xxxx_create_review_votes_table.php`
- `app/Models/Review.php` — belongsTo Business, belongsTo User, belongsTo Booking
- `app/Models/ReviewVote.php`
- `app/Domains/Reviews/Actions/SubmitReview.php`
- `app/Domains/Reviews/Actions/RespondToReview.php`
- `app/Domains/Reviews/Actions/ModerateReview.php`
- `app/Domains/Reviews/Actions/VoteReview.php`
- `app/Domains/Reviews/DTOs/ReviewData.php`
- `app/Domains/Reviews/Events/ReviewSubmitted.php`
- `app/Domains/Reviews/Events/ReviewApproved.php`
- `app/Domains/Reviews/Events/ReviewRejected.php`
- `app/Domains/Reviews/Events/ReviewResponded.php`
- `app/Domains/Reviews/Listeners/UpdateBusinessRating.php`
- `app/Domains/Reviews/Listeners/NotifyBusinessOfReview.php`
- `app/Domains/Reviews/Policies/ReviewPolicy.php`
- `app/Domains/Reviews/Livewire/ReviewManager.php`
- `app/Domains/Reviews/Livewire/WriteReview.php`
- `resources/views/livewire/reviews/review-manager.blade.php`
- `resources/views/livewire/reviews/write-review.blade.php`

### 3. Business Trust Score

Add computed attribute to Business model:
```php
public function averageRating(): float
{
    return $this->reviews()->where('status', 'approved')->avg('rating') ?? 0;
}

public function reviewCount(): int
{
    return $this->reviews()->where('status', 'approved')->count();
}

public function trustScore(): float
{
    // 0-5 scale based on: reviews (40%), verification (30%), response rate (30%)
    $avgRating = $this->averageRating() / 5 * 40;
    $verified = $this->is_verified ? 30 : 0;
    $responseRate = min(100, $this->reviewCount() > 0
        ? ($this->reviews()->whereNotNull('business_response')->count() / $this->reviewCount()) * 100
        : 0) / 100 * 30;

    return round($avgRating + $verified + $responseRate, 2);
}
```

### 4. Event Wiring

Register in `EventServiceProvider`:
```php
ReviewSubmitted::class => [
    UpdateBusinessRating::class,
    NotifyBusinessOfReview::class,
],
```

### 5. Routes

- `/dashboard/reviews` — merchant review management
- `/{slug}/reviews` — public reviews list
- `/{slug}/reviews/store` — submit review (authenticated)

---

## Checklist

- [ ] Migration: `reviews` table
- [ ] Migration: `review_votes` table
- [ ] Models: Review, ReviewVote
- [ ] Actions: SubmitReview, RespondToReview, ModerateReview, VoteReview
- [ ] DTO: ReviewData
- [ ] Events: ReviewSubmitted, ReviewApproved, ReviewRejected, ReviewResponded
- [ ] Listeners: UpdateBusinessRating, NotifyBusinessOfReview
- [ ] Register events in EventServiceProvider
- [ ] Policy: ReviewPolicy
- [ ] Livewire: ReviewManager (merchant)
- [ ] Livewire: WriteReview (customer)
- [ ] Business model: averageRating(), reviewCount(), trustScore()
- [ ] Routes: `/dashboard/reviews`, `/{slug}/reviews`
