<?php

namespace App\Domains\Analytics\Livewire;

use App\Domains\Analytics\Actions\GenerateReport;
use App\Models\AnalyticsEvent;
use App\Models\Business;
use App\Models\LinkClick;
use App\Models\MarketingLink;
use Illuminate\Support\Carbon;
use Livewire\Component;

class AnalyticsDashboard extends Component
{
    public ?Business $business = null;

    public string $activeTab = 'overview';

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

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function exportCsv()
    {
        if (! $this->business) {
            return;
        }

        $report = app(GenerateReport::class);
        $viewsByDay = $report->viewsByDay($this->business, 30);

        $csv = fopen('php://temp', 'r+');
        fputcsv($csv, ['Date', 'Page Views', 'Product Views', 'Service Views', 'WhatsApp Clicks', 'Phone Clicks']);

        $productByDay = $this->viewsByType($this->business, AnalyticsEvent::TYPE_PRODUCT_VIEW, 30);
        $serviceByDay = $this->viewsByType($this->business, AnalyticsEvent::TYPE_SERVICE_VIEW, 30);
        $whatsappByDay = $this->viewsByType($this->business, AnalyticsEvent::TYPE_WHATSAPP_CLICK, 30);
        $phoneByDay = $this->viewsByType($this->business, AnalyticsEvent::TYPE_PHONE_CLICK, 30);

        foreach ($viewsByDay as $date => $count) {
            fputcsv($csv, [
                $date,
                $count,
                $productByDay[$date] ?? 0,
                $serviceByDay[$date] ?? 0,
                $whatsappByDay[$date] ?? 0,
                $phoneByDay[$date] ?? 0,
            ]);
        }

        rewind($csv);
        $content = stream_get_contents($csv);
        fclose($csv);

        $filename = 'analytics-'.$this->business->slug.'-'.now()->format('Y-m-d').'.csv';

        return response()->streamDownload(function () use ($content) {
            echo $content;
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    public function exportPdf()
    {
        $this->dispatch('print-analytics');
    }

    public function render(GenerateReport $report)
    {
        if (! $this->business) {
            return view('livewire.analytics.analytics-dashboard', ['hasBusiness' => false]);
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
        $whatsappToday = $report->todayVsYesterday($this->business, AnalyticsEvent::TYPE_WHATSAPP_CLICK);
        $phoneToday = $report->todayVsYesterday($this->business, AnalyticsEvent::TYPE_PHONE_CLICK);

        // Traffic source data
        $sources = AnalyticsEvent::where('business_id', $this->business->id)
            ->selectRaw('source, COUNT(*) as count')
            ->groupBy('source')
            ->orderByDesc('count')
            ->pluck('count', 'source')
            ->toArray();

        $sourceLabels = array_map(fn ($s) => $s ? ucfirst(str_replace('_', ' ', $s)) : 'Direct', array_keys($sources));
        $sourceValues = array_values($sources);

        // Weekly trend (this week vs last week)
        $thisWeek = $report->viewsByDay($this->business, 7);
        $lastWeek = $this->viewsByTypeWeekAgo($this->business, AnalyticsEvent::TYPE_PAGE_VIEW);

        // Marketing link data
        $marketingLinks = MarketingLink::where('business_id', $this->business->id)
            ->withCount(['clicks' => fn ($q) => $q->where('created_at', '>=', now()->subDays(30))])
            ->withCount(['clicks as clicks_today' => fn ($q) => $q->whereDate('created_at', today())])
            ->withCount(['clicks as clicks_yesterday' => fn ($q) => $q->whereDate('created_at', today()->subDay())])
            ->orderByDesc('clicks_count')
            ->get();

        $marketingClicksByDay = LinkClick::whereHas('marketingLink', fn ($q) => $q->where('business_id', $this->business->id))
            ->where('created_at', '>=', now()->subDays(14))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date')
            ->toArray();

        $marketingClicksFilled = [];
        for ($i = 13; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $marketingClicksFilled[$date] = $marketingClicksByDay[$date] ?? 0;
        }

        // Per-link performance (top 5 by clicks)
        $linkPerformance = $marketingLinks->take(5);

        // Build chart configs
        $lineChartConfig = [
            'type' => 'line',
            'labels' => array_keys($viewsByDay),
            'datasets' => [[
                'label' => 'Page Views',
                'data' => array_values($viewsByDay),
                'borderColor' => '#3b82f6',
                'backgroundColor' => 'rgba(59, 130, 246, 0.08)',
                'pointBackgroundColor' => '#3b82f6',
            ]],
        ];

        $trafficSourceChart = [
            'type' => 'doughnut',
            'labels' => $sourceLabels ?: ['No data'],
            'datasets' => [[
                'data' => $sourceValues ?: [1],
                'backgroundColor' => ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#06b6d4'],
                'borderWidth' => 0,
            ]],
            'legend' => true,
        ];

        $comparisonChart = [
            'type' => 'bar',
            'labels' => ['Page Views', $itemType === 'product' ? 'Product Views' : 'Service Views', 'WhatsApp', 'Phone'],
            'datasets' => [
                [
                    'label' => 'Today',
                    'data' => [$viewsToday['today'], $itemViewsToday['today'], $whatsappToday['today'], $phoneToday['today']],
                    'backgroundColor' => '#3b82f6',
                ],
                [
                    'label' => 'Yesterday',
                    'data' => [$viewsToday['yesterday'], $itemViewsToday['yesterday'], $whatsappToday['yesterday'], $phoneToday['yesterday']],
                    'backgroundColor' => '#93c5fd',
                ],
            ],
        ];

        $weeklyTrendChart = [
            'type' => 'line',
            'labels' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            'datasets' => [
                [
                    'label' => 'This Week',
                    'data' => array_values($thisWeek),
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.08)',
                    'pointBackgroundColor' => '#3b82f6',
                ],
                [
                    'label' => 'Last Week',
                    'data' => array_values($lastWeek),
                    'borderColor' => '#d1d5db',
                    'backgroundColor' => 'rgba(209, 213, 219, 0.08)',
                    'pointBackgroundColor' => '#d1d5db',
                ],
            ],
        ];

        $marketingTrendChart = [
            'type' => 'line',
            'labels' => array_keys($marketingClicksFilled),
            'datasets' => [[
                'label' => 'Link Clicks',
                'data' => array_values($marketingClicksFilled),
                'borderColor' => '#10b981',
                'backgroundColor' => 'rgba(16, 185, 129, 0.08)',
                'pointBackgroundColor' => '#10b981',
            ]],
        ];

        $marketingBarChart = [
            'type' => 'bar',
            'labels' => $linkPerformance->pluck('name')->toArray() ?: ['No links'],
            'datasets' => [[
                'label' => 'Clicks (30 days)',
                'data' => $linkPerformance->pluck('clicks_count')->toArray() ?: [0],
                'backgroundColor' => ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'],
                'borderRadius' => 6,
            ]],
        ];

        return view('livewire.analytics.analytics-dashboard', [
            'hasBusiness' => true,
            'business' => $this->business,
            'totalViews' => $totalViews,
            'viewsToday' => $viewsToday['today'],
            'viewsYesterday' => $viewsToday['yesterday'],
            'itemViews' => $itemViews,
            'itemViewsToday' => $itemViewsToday['today'],
            'itemViewsYesterday' => $itemViewsToday['yesterday'],
            'itemType' => $itemType,
            'whatsappClicks' => $whatsappClicks,
            'phoneClicks' => $phoneClicks,
            'lineChartConfig' => $lineChartConfig,
            'trafficSourceChart' => $trafficSourceChart,
            'comparisonChart' => $comparisonChart,
            'weeklyTrendChart' => $weeklyTrendChart,
            'marketingTrendChart' => $marketingTrendChart,
            'marketingBarChart' => $marketingBarChart,
            'marketingLinks' => $marketingLinks,
        ]);
    }

    private function viewsByTypeWeekAgo(Business $business, string $type): array
    {
        $start = now()->subWeek()->startOfWeek();
        $end = now()->subWeek()->endOfWeek();

        $raw = AnalyticsEvent::where('business_id', $business->id)
            ->where('type', $type)
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->pluck('count', 'date')
            ->toArray();

        $result = array_fill(0, 7, 0);
        foreach ($raw as $date => $count) {
            $dayOfWeek = Carbon::parse($date)->dayOfWeekIso - 1;
            $result[$dayOfWeek] += $count;
        }

        return $result;
    }

    private function viewsByType(Business $business, string $type, int $days): array
    {
        $since = now()->subDays($days);

        $raw = AnalyticsEvent::where('business_id', $business->id)
            ->where('type', $type)
            ->where('created_at', '>=', $since)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date')
            ->toArray();

        $result = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $result[$date] = $raw[$date] ?? 0;
        }

        return $result;
    }
}
