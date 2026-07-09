{{--
  Billing history — lists past payments with status and details.
--}}
<div>
    @if (! $hasBusiness)
        <div class="rounded-lg border border-dashed border-gray-300 bg-white p-12 text-center">
            <p class="text-sm text-gray-500">Create a business to see billing history.</p>
        </div>
    @else
        <div class="rounded-lg border border-gray-200 bg-white">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse ($transactions as $txn)
                            <tr class="hover:bg-gray-50">
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">
                                    {{ $txn->paid_at?->format('M j, Y') ?? $txn->created_at->format('M j, Y') }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm font-mono text-gray-900">{{ $txn->reference }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $txn->description ?? '—' }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium text-gray-900">
                                    {{ number_format($txn->amount, 2) }} {{ $txn->currency }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-center">
                                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium
                                        {{ $txn->status === 'success' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $txn->status === 'failed' ? 'bg-red-100 text-red-800' : '' }}
                                        {{ $txn->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                        {{ ucfirst($txn->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-400">No transactions yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($transactions->hasPages())
                <div class="border-t border-gray-200 px-6 py-4">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>
    @endif
</div>
