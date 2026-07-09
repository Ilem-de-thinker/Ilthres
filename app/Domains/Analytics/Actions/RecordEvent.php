<?php

namespace App\Domains\Analytics\Actions;

use App\Models\AnalyticsEvent;
use App\Models\Business;
use Illuminate\Http\Request;

class RecordEvent
{
    public function __invoke(
        Business $business,
        string $type,
        ?string $source = null,
        ?array $metadata = null,
        ?string $url = null,
    ): AnalyticsEvent {
        $request = request();

        return AnalyticsEvent::create([
            'business_id' => $business->id,
            'user_id' => $request?->user()?->id,
            'type' => $type,
            'source' => $source,
            'url' => $url ?? $request?->fullUrl(),
            'ip' => $request?->ip(),
            'user_agent' => $request?->userAgent(),
            'metadata' => $metadata,
            'created_at' => now(),
        ]);
    }
}
