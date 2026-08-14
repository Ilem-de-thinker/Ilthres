<?php

namespace App\Domains\Business\Livewire;

use App\Domains\Analytics\Actions\RecordEvent;
use App\Models\AnalyticsEvent;
use App\Models\Business;
use Livewire\Attributes\Layout;
use Livewire\Component;

/**
 * Public business detail page showing info, verification badge, products/services
 * preview, contact details, and a "View Site" CTA.
 *
 * This is the discovery page — customers learn about the business here.
 * The themed website lives at /{slug}/site or via external URL.
 */
#[Layout('layouts.app')]
class BusinessDetail extends Component
{
    public Business $business;

    public function mount(Business $business): void
    {
        $this->business = $business;

        abort_if(! $this->business->is_active, 404);

        $this->recordViewEvent();
    }

    public function getSiteUrl(): string
    {
        if ($this->business->website_url) {
            return $this->business->website_url;
        }

        return route('business.site', $this->business);
    }

    public function hasExternalSite(): bool
    {
        return filled($this->business->website_url);
    }

    private function recordViewEvent(): void
    {
        try {
            app(RecordEvent::class)(
                $this->business,
                AnalyticsEvent::TYPE_PAGE_VIEW,
            );
        } catch (\Throwable $e) {
            report($e);
        }
    }

    public function render()
    {
        $products = $this->business->isProductStore()
            ? $this->business->products()->where('is_active', true)->where('is_public', true)->latest()->limit(6)->get()
            : collect();

        $services = $this->business->isServiceBusiness()
            ? $this->business->services()->where('is_active', true)->where('is_public', true)->latest()->limit(6)->get()
            : collect();

        $totalProducts = $this->business->isProductStore()
            ? $this->business->products()->where('is_active', true)->where('is_public', true)->count()
            : 0;

        $totalServices = $this->business->isServiceBusiness()
            ? $this->business->services()->where('is_active', true)->where('is_public', true)->count()
            : 0;

        $primaryLocation = $this->business->primaryLocation;

        return view('livewire.business.business-detail', [
            'products' => $products,
            'services' => $services,
            'totalProducts' => $totalProducts,
            'totalServices' => $totalServices,
            'primaryLocation' => $primaryLocation,
        ]);
    }
}
