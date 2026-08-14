<?php

namespace App\Domains\Administration\Livewire;

use App\Models\AnalyticsEvent;
use App\Models\Business;
use App\Models\MarketingLink;
use App\Models\Order;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class PlatformAnalytics extends Component
{
    public string $period = '30d';

    public function render()
    {
        $days = match ($this->period) {
            '7d' => 7,
            '30d' => 30,
            '90d' => 90,
            '365d' => 365,
            default => 30,
        };

        $since = now()->subDays($days);

        // Top products by views
        $topProducts = AnalyticsEvent::where('type', 'product_view')
            ->where('created_at', '>=', $since)
            ->whereNotNull('metadata->product_id')
            ->select('metadata->product_id as product_id', DB::raw('count(*) as views'))
            ->groupBy('product_id')
            ->orderByDesc('views')
            ->limit(10)
            ->get()
            ->map(function ($row) {
                $product = Product::with('business')->find($row->product_id);

                return [
                    'name' => $product?->name ?? 'Deleted Product',
                    'business' => $product?->business?->name ?? '—',
                    'views' => $row->views,
                    'price' => $product?->price ?? 0,
                ];
            });

        // Top services by views
        $topServices = AnalyticsEvent::where('type', 'service_view')
            ->where('created_at', '>=', $since)
            ->whereNotNull('metadata->service_id')
            ->select('metadata->service_id as service_id', DB::raw('count(*) as views'))
            ->groupBy('service_id')
            ->orderByDesc('views')
            ->limit(10)
            ->get()
            ->map(function ($row) {
                $service = Service::with('business')->find($row->service_id);

                return [
                    'name' => $service?->name ?? 'Deleted Service',
                    'business' => $service?->business?->name ?? '—',
                    'views' => $row->views,
                    'price' => $service?->price ?? 0,
                ];
            });

        // Top businesses by page views
        $topBusinesses = AnalyticsEvent::where('type', 'page_view')
            ->where('created_at', '>=', $since)
            ->select('business_id', DB::raw('count(*) as views'))
            ->groupBy('business_id')
            ->orderByDesc('views')
            ->limit(10)
            ->get()
            ->map(function ($row) {
                $business = Business::find($row->business_id);

                return [
                    'name' => $business?->name ?? 'Deleted',
                    'type' => $business?->isProductStore() ? 'Store' : 'Service',
                    'views' => $row->views,
                ];
            });

        // Views by category
        $viewsByCategory = AnalyticsEvent::where('type', 'page_view')
            ->where('created_at', '>=', $since)
            ->join('businesses', 'analytics_events.business_id', '=', 'businesses.id')
            ->leftJoin('categories', 'businesses.category_id', '=', 'categories.id')
            ->select('categories.name as category_name', DB::raw('count(*) as views'))
            ->groupBy('category_name')
            ->orderByDesc('views')
            ->limit(10)
            ->get();

        // Sales summary
        $salesSummary = Order::where('created_at', '>=', $since)
            ->select('status', DB::raw('count(*) as count'), DB::raw('sum(total) as revenue'))
            ->groupBy('status')
            ->get();

        $totalRevenue = $salesSummary->sum('revenue');
        $totalOrders = $salesSummary->sum('count');
        $completedOrders = $salesSummary->where('status', 'completed')->first();
        $pendingOrders = $salesSummary->where('status', 'pending')->first();

        // Marketing link clicks
        $topMarketingLinks = MarketingLink::withCount('clicks')
            ->whereHas('clicks', fn ($q) => $q->where('created_at', '>=', $since))
            ->orderByDesc('clicks_count')
            ->limit(10)
            ->get()
            ->map(function ($link) {
                return [
                    'name' => $link->name,
                    'business' => $link->business?->name ?? '—',
                    'clicks' => $link->clicks_count,
                ];
            });

        // Contact clicks (whatsapp + phone)
        $totalContactClicks = AnalyticsEvent::whereIn('type', ['whatsapp_click', 'phone_click'])
            ->where('created_at', '>=', $since)
            ->count();

        return view('livewire.administration.platform-analytics', [
            'topProducts' => $topProducts,
            'topServices' => $topServices,
            'topBusinesses' => $topBusinesses,
            'viewsByCategory' => $viewsByCategory,
            'totalRevenue' => $totalRevenue,
            'totalOrders' => $totalOrders,
            'completedOrders' => $completedOrders,
            'pendingOrders' => $pendingOrders,
            'topMarketingLinks' => $topMarketingLinks,
            'totalContactClicks' => $totalContactClicks,
        ]);
    }
}
