@if ($hasBusiness)
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="rounded-xl border border-gray-200 bg-white p-4">
            <p class="text-xs font-medium uppercase tracking-wider text-gray-500">Total Discounts</p>
            <p class="mt-1 text-2xl font-bold text-gray-900">{{ $total }}</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-4">
            <p class="text-xs font-medium uppercase tracking-wider text-gray-500">Active</p>
            <p class="mt-1 text-2xl font-bold text-green-600">{{ $active }}</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-4">
            <p class="text-xs font-medium uppercase tracking-wider text-gray-500">Redemptions</p>
            <p class="mt-1 text-2xl font-bold text-gray-900">{{ $totalRedemptions }}</p>
        </div>
    </div>
@endif
