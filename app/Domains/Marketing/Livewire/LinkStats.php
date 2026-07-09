<?php

namespace App\Domains\Marketing\Livewire;

use App\Models\Business;
use App\Models\MarketingLink;

class LinkStats extends \Livewire\Component
{
    public ?Business $business = null;

    public function mount(): void
    {
        $user = auth()->user();
        $businesses = $user->businesses;

        if ($businesses->isEmpty()) {
            return;
        }

        $defaultId = session('current_business_id', $businesses->first()->id);
        $this->business = $businesses->find($defaultId) ?? $businesses->first();
    }

    public function render()
    {
        if (! $this->business) {
            return view('livewire.marketing.link-stats', [
                'hasBusiness' => false,
            ]);
        }

        $links = MarketingLink::where('business_id', $this->business->id)
            ->withCount('clicks')
            ->latest()
            ->get();

        $totalClicks = $links->sum('clicks_count');
        $topLink = $links->sortByDesc('clicks_count')->first();

        return view('livewire.marketing.link-stats', [
            'hasBusiness' => true,
            'links' => $links,
            'totalClicks' => $totalClicks,
            'topLink' => $topLink,
        ]);
    }
}
