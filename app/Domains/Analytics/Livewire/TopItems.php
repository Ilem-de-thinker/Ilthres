<?php

namespace App\Domains\Analytics\Livewire;

use App\Domains\Analytics\Actions\GenerateReport;
use App\Models\AnalyticsEvent;
use App\Models\Business;
use App\Models\Product;
use App\Models\Service;
use Livewire\Component;

class TopItems extends Component
{
    public Business $business;

    public int $limit = 5;

    public function render(GenerateReport $report)
    {
        if ($this->business->isProductStore()) {
            $raw = $report->topItems($this->business, AnalyticsEvent::TYPE_PRODUCT_VIEW, $this->limit);
            $items = collect($raw)->map(fn ($row) => [
                'item' => Product::find($row['item_id']),
                'views' => $row['views'],
            ])->filter(fn ($r) => $r['item'])->values();
        } else {
            $raw = $report->topItems($this->business, AnalyticsEvent::TYPE_SERVICE_VIEW, $this->limit);
            $items = collect($raw)->map(fn ($row) => [
                'item' => Service::find($row['item_id']),
                'views' => $row['views'],
            ])->filter(fn ($r) => $r['item'])->values();
        }

        return view('livewire.analytics.top-items', [
            'items' => $items,
            'itemType' => $this->business->isProductStore() ? 'product' : 'service',
        ]);
    }
}
