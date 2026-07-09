<?php

namespace App\Domains\Marketing\Livewire;

use App\Models\Business;
use App\Models\Discount;
use Livewire\Component;

class DiscountStats extends Component
{
    public ?Business $business = null;

    public function mount(): void
    {
        $user = auth()->user();
        $businesses = $user->businesses;
        if ($businesses->isEmpty()) { return; }
        $defaultId = session('current_business_id', $businesses->first()->id);
        $this->business = $businesses->find($defaultId) ?? $businesses->first();
    }

    public function render()
    {
        if (! $this->business) {
            return view('livewire.marketing.discount-stats', ['hasBusiness' => false]);
        }

        $total = Discount::where('business_id', $this->business->id)->count();
        $active = Discount::where('business_id', $this->business->id)->where('is_active', true)->count();
        $totalRedemptions = Discount::where('business_id', $this->business->id)->sum('used_count');

        return view('livewire.marketing.discount-stats', [
            'hasBusiness' => true,
            'total' => $total,
            'active' => $active,
            'totalRedemptions' => $totalRedemptions,
        ]);
    }
}
