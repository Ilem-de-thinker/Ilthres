<?php

namespace App\Domains\Search\Actions;

use App\Models\AnalyticsEvent;
use Illuminate\Support\Facades\Request;

class TrackSearchAction
{
    public function execute(string $query, int $resultCount, ?string $filters = null): void
    {
        AnalyticsEvent::create([
            'type' => 'search',
            'source' => 'explore',
            'url' => Request::fullUrl(),
            'ip' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'metadata' => json_encode([
                'query' => $query,
                'result_count' => $resultCount,
                'filters' => $filters,
            ]),
        ]);
    }
}
