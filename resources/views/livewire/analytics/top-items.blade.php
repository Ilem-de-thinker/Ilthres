<div class="rounded-lg border border-gray-200 bg-white p-5">
    <h3 class="text-sm font-semibold text-gray-900">Top {{ $itemType === 'product' ? 'products' : 'services' }}</h3>
    <p class="mt-0.5 text-xs text-gray-500">Most viewed {{ $itemType === 'product' ? 'products' : 'services' }}</p>

    @if ($items->isEmpty())
        <p class="mt-6 text-sm text-gray-400">No views yet.</p>
    @else
        <div class="mt-4 space-y-3">
            @foreach ($items as $index => $row)
                <div class="flex items-center gap-3">
                    <span class="w-5 text-sm font-medium {{ $index === 0 ? 'text-gray-900' : 'text-gray-400' }}">{{ $index + 1 }}.</span>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-gray-900">{{ $row['item']->name }}</p>
                        <p class="text-xs text-gray-500">{{ number_format($row['views']) }} view{{ $row['views'] !== 1 ? 's' : '' }}</p>
                    </div>
                    @if ($row['item']->images && count($row['item']->images) > 0)
                        <img src="{{ Storage::url($row['item']->images[0]) }}" alt="" class="h-10 w-10 rounded-lg object-cover bg-gray-100">
                    @endif
                </div>
                @if (! $loop->last)
                    <hr class="border-gray-100">
                @endif
            @endforeach
        </div>
    @endif
</div>
