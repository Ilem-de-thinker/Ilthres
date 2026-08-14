<?php

namespace App\Domains\Marketing\Livewire;

use App\Domains\Marketing\Actions\DeleteLink;
use App\Models\Business;
use App\Models\MarketingLink;
use Livewire\Component;

class MarketingLinks extends Component
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

    public function delete(int $id): void
    {
        $link = MarketingLink::findOrFail($id);
        $this->authorize('delete', $link);

        app(DeleteLink::class)($link);
    }

    public function render()
    {
        if (! $this->business) {
            return view('livewire.marketing.marketing-links', [
                'hasBusiness' => false,
            ]);
        }

        $links = MarketingLink::where('business_id', $this->business->id)
            ->withCount('clicks')
            ->latest()
            ->get();

        return view('livewire.marketing.marketing-links', [
            'hasBusiness' => true,
            'links' => $links,
        ]);
    }
}
