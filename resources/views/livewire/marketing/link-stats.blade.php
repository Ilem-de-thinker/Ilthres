{{--
  Link stats — summary cards showing total clicks, top link, and per-link breakdown.
--}}
<div>
    @if (! $hasBusiness)
        <div class="rounded-lg border border-dashed border-gray-300 bg-white p-12 text-center">
            <p class="text-sm text-gray-500">Create a business to see link statistics.</p>
        </div>
    @else
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="rounded-lg border border-gray-200 bg-white p-5">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total links</p>
                <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $links->count() }}</p>
            </div>
            <div class="rounded-lg border border-gray-200 bg-white p-5">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total clicks</p>
                <p class="mt-1 text-2xl font-semibold text-gray-900">{{ number_format($totalClicks) }}</p>
            </div>
            <div class="rounded-lg border border-gray-200 bg-white p-5">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Top link</p>
                <p class="mt-1 text-2xl font-semibold text-gray-900 truncate">{{ $topLink ? $topLink->name : '—' }}</p>
                @if ($topLink)
                    <p class="text-xs text-gray-500">{{ number_format($topLink->clicks_count) }} clicks</p>
                @endif
            </div>
        </div>

        @if ($links->isNotEmpty())
            <div class="mt-6 rounded-lg border border-gray-200 bg-white">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-900">Per-link breakdown</h3>
                </div>
                <div class="space-y-0 divide-y divide-gray-100">
                    @foreach ($links as $link)
                        <div class="flex items-center justify-between px-6 py-3">
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $link->name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ $link->url }}</p>
                            </div>
                            <div class="ml-4 text-right">
                                <p class="text-sm font-semibold text-gray-900">{{ number_format($link->clicks_count) }}</p>
                                <p class="text-xs text-gray-500">clicks</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endif
</div>
