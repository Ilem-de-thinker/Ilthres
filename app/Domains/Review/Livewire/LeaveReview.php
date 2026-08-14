<?php

namespace App\Domains\Review\Livewire;

use App\Models\Booking;
use App\Models\Business;
use App\Models\Order;
use App\Models\Review;
use Livewire\Component;

class LeaveReview extends Component
{
    public Business $business;

    public ?Order $order = null;

    public ?Booking $booking = null;

    public $rating = 0;

    public $comment = '';

    public $reviewerName = '';

    public $submitted = false;

    protected $rules = [
        'rating' => 'required|integer|in:1,2,3,4,5',
        'comment' => 'nullable|string|max:1000',
        'reviewerName' => 'required|string|max:255',
    ];

    protected $messages = [
        'rating.required' => 'Please select a rating.',
        'rating.in' => 'Please select a valid rating (1-5 stars).',
        'reviewerName.required' => 'Please enter your name.',
        'reviewerName.max' => 'Name cannot exceed 255 characters.',
        'comment.max' => 'Review cannot exceed 1000 characters.',
    ];

    public function mount(int $businessId, ?int $orderId = null, ?int $bookingId = null): void
    {
        $this->business = Business::findOrFail($businessId);

        if ($orderId) {
            $this->order = Order::findOrFail($orderId);
        }

        if ($bookingId) {
            $this->booking = Booking::findOrFail($bookingId);
        }

        // Pre-fill from session if logged in
        if (auth()->check()) {
            $user = auth()->user();
            $this->reviewerName = $user->name;
        }
    }

    public function setRating($rating): void
    {
        $this->rating = $rating;
    }

    public function submit(): void
    {
        $this->validate();

        // Check eligibility
        if (! $this->canReview()) {
            session()->flash('error', 'You are not eligible to leave a review for this business.');

            return;
        }

        // Check if already reviewed
        if ($this->hasAlreadyReviewed()) {
            session()->flash('error', 'You have already reviewed this business.');

            return;
        }

        Review::create([
            'business_id' => $this->business->id,
            'order_id' => $this->order?->id,
            'booking_id' => $this->booking?->id,
            'user_id' => auth()->id(),
            'reviewer_name' => $this->reviewerName,
            'rating' => $this->rating,
            'comment' => $this->comment,
            'is_active' => true,
        ]);

        // Update business rating cache
        $this->business->updateRatingCache();

        $this->submitted = true;
        session()->flash('success', 'Thank you for your review!');
    }

    private function canReview(): bool
    {
        if (! auth()->check()) {
            return false;
        }

        // Check if user has completed order with this business
        $hasCompletedOrder = Order::where('business_id', $this->business->id)
            ->where('user_id', auth()->id())
            ->where('status', 'completed')
            ->exists();

        // Check if user has completed booking with this business
        $hasCompletedBooking = Booking::where('business_id', $this->business->id)
            ->where('user_id', auth()->id())
            ->where('status', 'completed')
            ->exists();

        return $hasCompletedOrder || $hasCompletedBooking;
    }

    private function hasAlreadyReviewed(): bool
    {
        return Review::where('business_id', $this->business->id)
            ->where('user_id', auth()->id())
            ->exists();
    }

    public function render()
    {
        return view('livewire.review.leave-review');
    }
}
