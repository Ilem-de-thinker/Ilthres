<div class="rounded-lg border border-gray-200 bg-white p-5" x-data="chart($el)" data-chart='@json($chartConfig)'>
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-sm font-semibold text-gray-900">Visitors over time</h3>
            <p class="mt-0.5 text-xs text-gray-500">Last {{ $days }} days</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1 text-xs text-gray-500">
                <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                Page views
            </span>
        </div>
    </div>
    <div class="mt-4" style="height: 220px;">
        <canvas></canvas>
    </div>
</div>
