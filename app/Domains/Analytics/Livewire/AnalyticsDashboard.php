<?php

namespace App\Domains\Analytics\Livewire;

use App\Domains\Analytics\Actions\GenerateReport;
use App\Models\AnalyticsEvent;
use App\Models\Business;

class AnalyticsDashboard extends \Livewire\Component
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

    public function render(GenerateReport $report)
    {
        if (! $this->business) {
            return view('livewire.analytics.analytics-dashboard', [
                'hasBusiness' => false,
            ]);
        }

        $totalViews = $report->totalViews($this->business);
        $viewsToday = $report->todayVsYesterday($this->business, AnalyticsEvent::TYPE_PAGE_VIEW);
        $viewsByDay = $report->viewsByDay($this->business, 14);

        if ($this->business->isProductStore()) {
            $itemViews = $report->totalProductViews($this->business);
            $itemViewsToday = $report->todayVsYesterday($this->business, AnalyticsEvent::TYPE_PRODUCT_VIEW);
            $topItems = $report->topItems($this->business, AnalyticsEvent::TYPE_PRODUCT_VIEW);
            $itemType = 'product';
        } else {
            $itemViews = $report->totalServiceViews($this->business);
            $itemViewsToday = $report->todayVsYesterday($this->business, AnalyticsEvent::TYPE_SERVICE_VIEW);
            $topItems = $report->topItems($this->business, AnalyticsEvent::TYPE_SERVICE_VIEW);
            $itemType = 'service';
        }

        $whatsappClicks = $report->totalWhatsAppClicks($this->business);
        $phoneClicks = $report->totalPhoneClicks($this->business);

        return view('livewire.analytics.analytics-dashboard', [
            'hasBusiness' => true,
            'business' => $this->business,
            'totalViews' => $totalViews,
            'viewsToday' => $viewsToday['today'],
            'viewsYesterday' => $viewsToday['yesterday'],
            'viewsByDay' => $viewsByDay,
            'itemViews' => $itemViews,
            'itemViewsToday' => $itemViewsToday['today'],
            'itemViewsYesterday' => $itemViewsToday['yesterday'],
            'topItems' => $topItems,
            'itemType' => $itemType,
            'whatsappClicks' => $whatsappClicks,
            'phoneClicks' => $phoneClicks,
        ]);
    }
}
