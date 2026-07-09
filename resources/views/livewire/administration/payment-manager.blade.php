{{-- Admin payment management — transaction list with search and filters. --}}
<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Payments</h1>
        <p class="mt-1 text-sm text-gray-500">View and search all payment transactions.</p>
    </div>

    <div class="mb-4 flex flex-wrap items-center gap-3">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by reference, description, or business..."
            class="min-w-0 flex-1 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:outline-none">
        <select wire:model.live="filterStatus"
            class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none">
            <option value="">All Statuses</option>
            <option value="success">Success</option>
            <option value="failed">Failed</option>
            <option value="pending">Pending</option>
        </select>
        <select wire:model.live="filterGateway"
            class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none">
            <option value="">All Gateways</option>
            @foreach ($gateways as $gateway)
                <option value="{{ $gateway }}">{{ ucfirst($gateway) }}</option>
            @endforeach
        </select>
    </div>

    <div class="overflow-x-auto rounded-lg border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reference</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Business</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Amount</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Gateway</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @forelse ($transactions as $txn)
                    <tr class="hover:bg-gray-50">
                        <td class="max-w-[160px] truncate px-4 py-3 text-sm font-medium text-gray-900" title="{{ $txn->reference }}">
                            {{ $txn->reference }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $txn->business?->name ?? '—' }}</td>
                        <td class="max-w-[200px] truncate px-4 py-3 text-sm text-gray-500" title="{{ $txn->description }}">
                            {{ $txn->description ?: '—' }}
                        </td>
                        <td class="px-4 py-3 text-right text-sm font-semibold text-gray-900">
                            ₦{{ number_format($txn->amount) }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium
                                {{ $txn->status === 'success' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $txn->status === 'failed' ? 'bg-red-100 text-red-700' : '' }}
                                {{ $txn->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}">
                                {{ ucfirst($txn->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center text-sm text-gray-500">{{ ucfirst($txn->gateway ?? '—') }}</td>
                        <td class="px-4 py-3 text-right text-sm text-gray-500">
                            {{ $txn->paid_at?->format('M j, Y H:i') ?? $txn->created_at->format('M j, Y H:i') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-sm text-gray-500">No transactions found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $transactions->links() }}
    </div>
</div>
