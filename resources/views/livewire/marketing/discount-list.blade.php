<div>
    @if (! $hasBusiness)
        <div class="rounded-lg border border-dashed border-gray-300 bg-white p-12 text-center">
            <p class="text-sm text-gray-500">Create a business to start adding discounts.</p>
        </div>
        @php return; @endphp
    @endif

    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold text-gray-900">Discount Codes</h2>
        <a href="{{ route('dashboard.marketing.discounts.create') }}" class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800">New Discount</a>
    </div>

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Code</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Value</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Uses</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Expires</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Status</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($discounts as $discount)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <span class="font-mono text-sm font-semibold text-gray-900">{{ $discount->code }}</span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-700">
                            @if ($discount->type === 'percentage')
                                {{ $discount->value }}%
                            @else
                                ?{{ number_format($discount->value) }}
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-700">
                            {{ $discount->used_count }}{{ $discount->max_uses ? ' / ' . $discount->max_uses : '' }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-700">
                            {{ $discount->expires_at ? $discount->expires_at->format('d M Y') : 'Never' }}
                        </td>
                        <td class="px-4 py-3">
                            @if ($discount->isValid())
                                <span class="inline-flex rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700">Active</span>
                            @else
                                <span class="inline-flex rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-700">Inactive</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <button wire:click="confirmDelete({{ $discount->id }})" class="text-xs font-medium text-red-600 hover:text-red-800">Disable</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-sm text-gray-400">No discounts yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $discounts->links() }}</div>

    @if ($showDeleteConfirm)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40" wire:click.self="cancelDelete">
            <div class="mx-4 w-full max-w-sm rounded-xl bg-white p-6 shadow-xl">
                <h3 class="text-lg font-semibold text-gray-900">Disable discount?</h3>
                <p class="mt-2 text-sm text-gray-600">This will deactivate the discount code. Existing redemptions remain valid.</p>
                <div class="mt-6 flex justify-end gap-3">
                    <button wire:click="cancelDelete" class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Cancel</button>
                    <button wire:click="delete" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">Disable</button>
                </div>
            </div>
        </div>
    @endif
</div>
