<div>
    @if (! $hasBusiness)
        <div class="rounded-lg border border-dashed border-gray-300 bg-white p-12 text-center">
            <p class="text-sm text-gray-500">Create a business to see analytics.</p>
        </div>
    @else
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
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

        <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-2">
            @livewire(\App\Domains\Analytics\Livewire\VisitorsChart::class, ['business' => $business], key('visitors-chart'))
            @livewire(\App\Domains\Analytics\Livewire\TopItems::class, ['business' => $business, 'limit' => 5], key('top-items'))
        </div>
    @endif
</div>
