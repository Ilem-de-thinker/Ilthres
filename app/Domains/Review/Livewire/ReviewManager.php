<?php

namespace App\Domains\Review\Livewire;

use App\Models\Business;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ReviewManager extends Component
{
    use WithPagination;

    public Business $business;

    public $ratingFilter = 'all';

    public $searchQuery = '';

    public $selectedReview = null;

    public $responseText = '';

    protected $rules = [
        'responseText' => 'required|string|max:1000',
    ];

    protected $messages = [
        'responseText.required' => 'Please enter a response.',
        'responseText.max' => 'Response cannot exceed 1000 characters.',
    ];

    public function mount(): void
    {
        $this->business = Auth::user()->business;
    }

    public function getReviewsProperty()
    {
        $query = Review::where('business_id', $this->business->id)
            ->with(['user', 'order', 'booking']);

        // Rating filter
        if ($this->ratingFilter !== 'all') {
            $query->where('rating', $this->ratingFilter);
        }

        // Search filter
        if ($this->searchQuery) {
            $query->where(function ($q) {
                $q->where('reviewer_name', 'like', "%{$this->searchQuery}%")
                    ->orWhere('comment', 'like', "%{$this->searchQuery}%");
            });
        }

        return $query->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function viewReview($reviewId): void
    {
        $this->selectedReview = Review::with(['user', 'order', 'booking'])->find($reviewId);
        $this->responseText = $this->selectedReview->seller_response ?? '';
    }

    public function respondToReview(): void
    {
        $this->validate();

        if (! $this->selectedReview || $this->selectedReview->business_id !== $this->business->id) {
            return;
        }

        $this->selectedReview->respond($this->responseText);

        $this->responseText = '';
        session()->flash('success', 'Response added successfully.');
    }

    public function flagReview($reviewId, $reason = 'inappropriate'): void
    {
        $review = Review::find($reviewId);
        if (! $review || $review->business_id !== $this->business->id) {
            return;
        }

        // For now, just mark as inactive
        $review->update(['is_active' => false]);
        $this->business->updateRatingCache();

        session()->flash('success', 'Review has been flagged and removed.');
    }

    public function render()
    {
        return view('livewire.review.review-manager');
    }
}
