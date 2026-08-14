<div>
    @if (! $hasBusiness)
        <div class="rounded-lg border border-dashed border-gray-300 bg-white p-12 text-center">
            <p class="text-sm text-gray-500">Create a business to see analytics.</p>
        </div>
    @else
        {{-- Header with export --}}
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Analytics Overview</h2>
                <p class="text-sm text-gray-500">Track your {{ Str::title($itemType) }} performance and visitor trends.</p>
            </div>
            <div class="relative flex items-center gap-2" x-data="exportDropdown()">
                <button @click="toggle()" class="rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 flex items-center gap-2">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Export
                </button>
                <div x-show="open" @click.away="close()" x-cloak class="absolute right-0 top-full z-20 mt-1 w-44 rounded-xl border border-gray-200 bg-white py-1 shadow-xl">
                    <button wire:click="exportCsv" @click="close()" class="flex w-full items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                        <svg class="h-4 w-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Export CSV
                    </button>
                    <button wire:click="exportPdf" @click="close()" class="flex w-full items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                        <svg class="h-4 w-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        Export PDF
                    </button>
                </div>
            </div>
        </div>

        {{-- Stat cards --}}
        <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-lg border border-gray-200 bg-white p-5">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Page views</p>
                <p class="mt-1 text-2xl font-semibold text-gray-900">{{ number_format($totalViews) }}</p>
                <p class="mt-1 text-xs {{ $viewsToday >= $viewsYesterday ? 'text-green-600' : 'text-red-600' }}">
                    {{ $viewsToday >= $viewsYesterday ? '▲' : '▼' }}
                    {{ $viewsToday }} today ({{ $viewsYesterday }} yesterday)
                </p>
            </div>
            <div class="rounded-lg border border-gray-200 bg-white p-5">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">{{ $itemType === 'product' ? 'Product' : 'Service' }} views</p>
                <p class="mt-1 text-2xl font-semibold text-gray-900">{{ number_format($itemViews) }}</p>
                <p class="mt-1 text-xs {{ $itemViewsToday >= $itemViewsYesterday ? 'text-green-600' : 'text-red-600' }}">
                    {{ $itemViewsToday >= $itemViewsYesterday ? '▲' : '▼' }}
                    {{ $itemViewsToday }} today ({{ $itemViewsYesterday }} yesterday)
                </p>
            </div>
            <div class="rounded-lg border border-gray-200 bg-white p-5">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">WhatsApp clicks</p>
                <p class="mt-1 text-2xl font-semibold text-gray-900">{{ number_format($whatsappClicks) }}</p>
            </div>
            <div class="rounded-lg border border-gray-200 bg-white p-5">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Phone clicks</p>
                <p class="mt-1 text-2xl font-semibold text-gray-900">{{ number_format($phoneClicks) }}</p>
            </div>
        </div>

        {{-- Tabs --}}
        <div class="mt-6 border-b border-gray-200">
            <nav class="-mb-px flex gap-6" x-data>
                <button wire:click="setTab('overview')"
                    class="whitespace-nowrap border-b-2 pb-3 text-sm font-medium transition-colors {{ $activeTab === 'overview' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}">
                    Overview
                </button>
                <button wire:click="setTab('traffic')"
                    class="whitespace-nowrap border-b-2 pb-3 text-sm font-medium transition-colors {{ $activeTab === 'traffic' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}">
                    Traffic
                </button>
                <button wire:click="setTab('marketing')"
                    class="whitespace-nowrap border-b-2 pb-3 text-sm font-medium transition-colors {{ $activeTab === 'marketing' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}">
                    Marketing
                    @if ($marketingLinks->isNotEmpty())
                        <span class="ml-1.5 inline-flex items-center rounded-full bg-blue-50 px-2 py-0.5 text-xs font-medium text-blue-700">{{ $marketingLinks->count() }}</span>
                    @endif
                </button>
            </nav>
        </div>

        {{-- Tab: Overview --}}
        @if ($activeTab === 'overview')
            <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-2">
                <div class="rounded-lg border border-gray-200 bg-white p-5" x-data="chart($el)" data-chart='@json($lineChartConfig)'>
                    <h3 class="text-sm font-semibold text-gray-900">Page Views Trend</h3>
                    <p class="mt-0.5 text-xs text-gray-500">Last 14 days</p>
                    <div class="mt-4" style="height: 200px;"><canvas></canvas></div>
                </div>

                <div class="rounded-lg border border-gray-200 bg-white p-5" x-data="chart($el)" data-chart='@json($weeklyTrendChart)'>
                    <h3 class="text-sm font-semibold text-gray-900">Weekly Comparison</h3>
                    <p class="mt-0.5 text-xs text-gray-500">This week vs last week</p>
                    <div class="mt-4" style="height: 200px;"><canvas></canvas></div>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-2">
                <div class="rounded-lg border border-gray-200 bg-white p-5" x-data="chart($el)" data-chart='@json($comparisonChart)'>
                    <h3 class="text-sm font-semibold text-gray-900">Today vs Yesterday</h3>
                    <p class="mt-0.5 text-xs text-gray-500">Compare activity across channels</p>
                    <div class="mt-4" style="height: 200px;"><canvas></canvas></div>
                </div>

                @livewire(\App\Domains\Analytics\Livewire\TopItems::class, ['business' => $business, 'limit' => 5], key('top-items'))
            </div>
        @endif

        {{-- Tab: Traffic --}}
        @if ($activeTab === 'traffic')
            <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-2">
                <div class="rounded-lg border border-gray-200 bg-white p-5" x-data="chart($el)" data-chart='@json($trafficSourceChart)'>
                    <h3 class="text-sm font-semibold text-gray-900">Traffic Sources</h3>
                    <p class="mt-0.5 text-xs text-gray-500">Where your visitors come from</p>
                    <div class="mt-4" style="height: 240px;"><canvas></canvas></div>
                </div>

                <div class="rounded-lg border border-gray-200 bg-white p-5" x-data="chart($el)" data-chart='@json($lineChartConfig)'>
                    <h3 class="text-sm font-semibold text-gray-900">Page Views Trend</h3>
                    <p class="mt-0.5 text-xs text-gray-500">Last 14 days</p>
                    <div class="mt-4" style="height: 240px;"><canvas></canvas></div>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-2">
                <div class="rounded-lg border border-gray-200 bg-white p-5" x-data="chart($el)" data-chart='@json($weeklyTrendChart)'>
                    <h3 class="text-sm font-semibold text-gray-900">Weekly Comparison</h3>
                    <p class="mt-0.5 text-xs text-gray-500">This week vs last week</p>
                    <div class="mt-4" style="height: 240px;"><canvas></canvas></div>
                </div>

                <div class="rounded-lg border border-gray-200 bg-white p-5" x-data="chart($el)" data-chart='@json($comparisonChart)'>
                    <h3 class="text-sm font-semibold text-gray-900">Today vs Yesterday</h3>
                    <p class="mt-0.5 text-xs text-gray-500">Compare activity across channels</p>
                    <div class="mt-4" style="height: 240px;"><canvas></canvas></div>
                </div>
            </div>
        @endif

        {{-- Tab: Marketing --}}
        @if ($activeTab === 'marketing')
            @if ($marketingLinks->isEmpty())
                <div class="mt-6 rounded-lg border border-dashed border-gray-300 bg-white p-12 text-center">
                    <svg class="mx-auto h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                    <p class="mt-3 text-sm text-gray-500">No marketing links yet.</p>
                    <a href="{{ route('dashboard.marketing.create') }}" class="mt-3 inline-flex items-center gap-1.5 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                        Create your first link
                    </a>
                </div>
            @else
                <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-2">
                    <div class="rounded-lg border border-gray-200 bg-white p-5" x-data="chart($el)" data-chart='@json($marketingBarChart)'>
                        <h3 class="text-sm font-semibold text-gray-900">Top Marketing Links</h3>
                        <p class="mt-0.5 text-xs text-gray-500">Clicks per link (last 30 days)</p>
                        <div class="mt-4" style="height: 240px;"><canvas></canvas></div>
                    </div>

                    <div class="rounded-lg border border-gray-200 bg-white p-5" x-data="chart($el)" data-chart='@json($marketingTrendChart)'>
                        <h3 class="text-sm font-semibold text-gray-900">Marketing Clicks Trend</h3>
                        <p class="mt-0.5 text-xs text-gray-500">Total link clicks over 14 days</p>
                        <div class="mt-4" style="height: 240px;"><canvas></canvas></div>
                    </div>
                </div>

                <div class="mt-6 rounded-lg border border-gray-200 bg-white">
                    <div class="border-b border-gray-100 px-5 py-4">
                        <h3 class="text-sm font-semibold text-gray-900">Marketing Links Performance</h3>
                        <p class="mt-0.5 text-xs text-gray-500">Click metrics for each link</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="border-b border-gray-100 text-xs font-medium text-gray-500 uppercase tracking-wide">
                                    <th class="px-5 py-3">Link</th>
                                    <th class="px-5 py-3 text-right">Total Clicks</th>
                                    <th class="px-5 py-3 text-right">Today</th>
                                    <th class="px-5 py-3 text-right">Yesterday</th>
                                    <th class="px-5 py-3 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach ($marketingLinks as $link)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-5 py-3">
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $link->name }}</p>
                                                <p class="text-xs text-gray-400">{{ $link->slug }}</p>
                                            </div>
                                        </td>
                                        <td class="px-5 py-3 text-right font-semibold text-gray-900">{{ number_format($link->clicks_count) }}</td>
                                        <td class="px-5 py-3 text-right text-gray-700">{{ $link->clicks_today }}</td>
                                        <td class="px-5 py-3 text-right text-gray-700">{{ $link->clicks_yesterday }}</td>
                                        <td class="px-5 py-3 text-center">
                                            @if ($link->is_active)
                                                <span class="inline-flex items-center rounded-full bg-green-50 px-2 py-0.5 text-xs font-medium text-green-700">Active</span>
                                            @else
                                                <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-500">Inactive</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        @endif
    @endif
</div>
