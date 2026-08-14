<?php

namespace App\Domains\Review\Livewire;

use App\Models\Business;
use App\Models\Review;
use Livewire\Component;

class ReviewDisplay extends Component
{
    public Business $business;

    public int $limit = 5;

    public int $ratingFilter = 0;

    public function mount(int $businessId, int $limit = 5): void
    {
        $this->business = Business::findOrFail($businessId);
        $this->limit = $limit;
    }

    public function getReviewsProperty()
    {
        $query = Review::where('business_id', $this->business->id)
            ->where('is_active', true)
            ->with('user');

        if ($this->ratingFilter > 0) {
            $query->where('rating', $this->ratingFilter);
        }

        return $query->orderBy('created_at', 'desc')
            ->limit($this->limit)
            ->get();
    }

    public function filterByRating(int $rating): void
    {
        $this->ratingFilter = $this->ratingFilter === $rating ? 0 : $rating;
    }

    public function render()
    {
        return view('livewire.review.review-display');
    }
}
