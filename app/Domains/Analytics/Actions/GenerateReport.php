<?php

namespace App\Domains\Analytics\Actions;

use App\Models\AnalyticsEvent;
use App\Models\Business;
use Illuminate\Support\Carbon;

class GenerateReport
{
    public function totalViews(Business $business, ?Carbon $since = null): int
    {
        return $this->query($business, AnalyticsEvent::TYPE_PAGE_VIEW, $since)->count();
    }

    public function totalProductViews(Business $business, ?Carbon $since = null): int
    {
        return $this->query($business, AnalyticsEvent::TYPE_PRODUCT_VIEW, $since)->count();
    }

    public function totalServiceViews(Business $business, ?Carbon $since = null): int
    {
        return $this->query($business, AnalyticsEvent::TYPE_SERVICE_VIEW, $since)->count();
    }

    public function totalWhatsAppClicks(Business $business, ?Carbon $since = null): int
    {
        return $this->query($business, AnalyticsEvent::TYPE_WHATSAPP_CLICK, $since)->count();
    }

    public function totalPhoneClicks(Business $business, ?Carbon $since = null): int
    {
        return $this->query($business, AnalyticsEvent::TYPE_PHONE_CLICK, $since)->count();
    }

    public function viewsByDay(Business $business, int $days = 30): array
    {
        $since = now()->subDays($days);

        $raw = AnalyticsEvent::where('business_id', $business->id)
            ->where('type', AnalyticsEvent::TYPE_PAGE_VIEW)
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

    public function topItems(Business $business, string $type, int $limit = 5): array
    {
        $metadataKey = $type === AnalyticsEvent::TYPE_PRODUCT_VIEW ? 'product_id' : 'service_id';

        $events = AnalyticsEvent::where('business_id', $business->id)
            ->where('type', $type)
            ->get();

        $counts = [];
        foreach ($events as $event) {
            $id = $event->metadata[$metadataKey] ?? null;
            if ($id) {
                $counts[$id] = ($counts[$id] ?? 0) + 1;
            }
        }

        arsort($counts);

        return collect(array_slice($counts, 0, $limit, true))
            ->map(fn ($views, $id) => ['item_id' => (string) $id, 'views' => $views])
            ->values()
            ->toArray();
    }

    public function todayVsYesterday(Business $business, string $type): array
    {
        $today = AnalyticsEvent::where('business_id', $business->id)
            ->where('type', $type)
            ->whereDate('created_at', today())
            ->count();

        $yesterday = AnalyticsEvent::where('business_id', $business->id)
            ->where('type', $type)
            ->whereDate('created_at', today()->subDay())
            ->count();

        return ['today' => $today, 'yesterday' => $yesterday];
    }

    private function query(Business $business, string $type, ?Carbon $since = null)
    {
        $query = AnalyticsEvent::where('business_id', $business->id)->where('type', $type);

        if ($since) {
            $query->where('created_at', '>=', $since);
        }

        return $query;
    }
}
