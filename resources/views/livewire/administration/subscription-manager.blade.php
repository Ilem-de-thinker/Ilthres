{{-- Admin subscription management — list with search and filter. --}}
<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Subscriptions</h1>
        <p class="mt-1 text-sm text-gray-500">View and manage all business subscriptions.</p>
    </div>

    <div class="mb-4 flex flex-wrap items-center gap-3">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by business name..."
            class="min-w-0 flex-1 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:outline-none">
        <select wire:model.live="filterStatus"
            class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none">
            <option value="">All Statuses</option>
            <option value="trial">Trial</option>
            <option value="active">Active</option>
            <option value="expired">Expired</option>
            <option value="cancelled">Cancelled</option>
            <option value="past_due">Past Due</option>
        </select>
    </div>

    <div class="overflow-x-auto rounded-lg border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Business</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Plan</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Price</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Starts</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Ends</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Created</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @forelse ($subscriptions as $sub)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $sub->business?->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $sub->plan?->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium
                                {{ $sub->status === 'active' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $sub->status === 'trial' ? 'bg-blue-100 text-blue-700' : '' }}
                                {{ $sub->status === 'expired' ? 'bg-red-100 text-red-700' : '' }}
                                {{ $sub->status === 'cancelled' ? 'bg-gray-100 text-gray-700' : '' }}
                                {{ $sub->status === 'past_due' ? 'bg-yellow-100 text-yellow-700' : '' }}">
                                {{ ucfirst(str_replace('_', ' ', $sub->status)) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right text-sm text-gray-900">
                            ₦{{ number_format($sub->plan?->price ?? 0) }}
                        </td>
                        <td class="px-4 py-3 text-right text-sm text-gray-500">
                            {{ $sub->starts_at?->format('M j, Y') ?? '—' }}
                        </td>
                        <td class="px-4 py-3 text-right text-sm text-gray-500">
                            {{ $sub->ends_at?->format('M j, Y') ?? '—' }}
                        </td>
                        <td class="px-4 py-3 text-right text-sm text-gray-500">
                            {{ $sub->created_at->format('M j, Y') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-sm text-gray-500">No subscriptions found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $subscriptions->links() }}
    </div>
</div>
