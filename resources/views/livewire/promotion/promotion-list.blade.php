<div>
    @if (! $hasBusiness)
        <div class="rounded-lg border border-dashed border-gray-300 bg-white p-12 text-center">
            <p class="text-sm text-gray-500">Create a business to manage promotions.</p>
        </div>
        @php return; @endphp
    @endif

    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold text-gray-900">Promotions</h2>
        <a href="{{ route('dashboard.promotions.create') }}" class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800">New Promotion</a>
    </div>

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Name</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Type</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Items</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Schedule</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Status</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($promotions as $promotion)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <span class="text-sm font-semibold text-gray-900">{{ $promotion->name }}</span>
                            @if ($promotion->description)
                                <p class="text-xs text-gray-500 mt-0.5">{{ Str::limit($promotion->description, 60) }}</p>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-700">
                            <span class="inline-flex items-center rounded-full bg-blue-50 px-2 py-0.5 text-xs font-medium text-blue-700">
                                {{ str_replace('_', ' ', ucfirst($promotion->promotion_type)) }}
                            </span>
                            @if ($promotion->discount_value)
                                <span class="ml-1 text-xs text-gray-500">
                                    {{ $promotion->promotion_type === 'percentage' ? $promotion->discount_value.'%' : '₦'.number_format($promotion->discount_value) }}
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-700">
                            {{ $promotion->products_count + $promotion->services_count }} items
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-700">
                            @if ($promotion->starts_at)
                                <span class="text-xs">{{ $promotion->starts_at->format('d M') }}</span>
                            @endif
                            @if ($promotion->ends_at)
                                <span class="text-xs text-gray-400"> → </span>
                                <span class="text-xs">{{ $promotion->ends_at->format('d M') }}</span>
                                @if ($promotion->daysRemaining() > 0)
                                    <span class="text-xs text-orange-600 ml-1">{{ $promotion->daysRemaining() }}d left</span>
                                @endif
                            @endif
                            @unless ($promotion->starts_at || $promotion->ends_at)
                                <span class="text-xs text-gray-400">No schedule</span>
                            @endunless
                        </td>
                        <td class="px-4 py-3">
                            @php
                                $statusStyles = [
                                    'active' => 'bg-green-100 text-green-700',
                                    'scheduled' => 'bg-blue-100 text-blue-700',
                                    'draft' => 'bg-gray-100 text-gray-600',
                                    'paused' => 'bg-yellow-100 text-yellow-700',
                                    'expired' => 'bg-red-100 text-red-700',
                                    'archived' => 'bg-gray-100 text-gray-400',
                                ];
                                $style = $statusStyles[$promotion->status] ?? 'bg-gray-100 text-gray-600';
                            @endphp
                            <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium {{ $style }}">
                                {{ ucfirst($promotion->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if (in_array($promotion->status, ['active', 'paused']))
                                    <button wire:click="toggleStatus({{ $promotion->id }})" class="text-xs font-medium {{ $promotion->status === 'active' ? 'text-yellow-600 hover:text-yellow-800' : 'text-green-600 hover:text-green-800' }}">
                                        {{ $promotion->status === 'active' ? 'Pause' : 'Activate' }}
                                    </button>
                                @endif
                                <a href="{{ route('dashboard.promotions.edit', $promotion) }}" class="text-xs font-medium text-gray-600 hover:text-gray-900">Edit</a>
                                @if (! in_array($promotion->status, ['expired', 'archived']))
                                    <button wire:click="confirmDelete({{ $promotion->id }})" class="text-xs font-medium text-red-600 hover:text-red-800">Archive</button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-sm text-gray-400">No promotions yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $promotions->links() }}</div>

    @if ($showDeleteConfirm)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40" wire:click.self="cancelDelete">
            <div class="mx-4 w-full max-w-sm rounded-xl bg-white p-6 shadow-xl">
                <h3 class="text-lg font-semibold text-gray-900">Archive promotion?</h3>
                <p class="mt-2 text-sm text-gray-600">This will archive the promotion. It will no longer be active on your storefront.</p>
                <div class="mt-6 flex justify-end gap-3">
                    <button wire:click="cancelDelete" class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Cancel</button>
                    <button wire:click="delete" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">Archive</button>
                </div>
            </div>
        </div>
    @endif
</div>
