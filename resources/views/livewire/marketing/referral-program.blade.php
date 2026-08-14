<div>
    @if (!$hasBusiness)
        <p class="text-sm text-gray-500">No business selected.</p>
    @else
        <div class="mb-6 grid grid-cols-2 gap-4">
            <div class="rounded-lg border border-gray-200 bg-white p-4">
                <p class="text-xs text-gray-500">Total Referrals</p>
                <p class="text-2xl font-bold text-gray-900">{{ $totalReferrals }}</p>
            </div>
            <div class="rounded-lg border border-gray-200 bg-white p-4">
                <p class="text-xs text-gray-500">Converted</p>
                <p class="text-2xl font-bold text-green-600">{{ $convertedReferrals }}</p>
            </div>
        </div>

        <form wire:submit="generateCode" class="mb-6 flex gap-2">
            <input wire:model="newCode" type="text" placeholder="Enter referral code"
                class="block flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
            <button type="submit"
                class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800">
                Generate
            </button>
        </form>
        @error('newCode') <p class="mb-4 text-sm text-red-600">{{ $message }}</p> @enderror

        <div class="overflow-hidden rounded-lg border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Code</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Referrer</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Status</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Created</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($referrals as $referral)
                        <tr>
                            <td class="px-4 py-3 font-medium text-gray-900">{{ $referral->code }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $referral->referrer?->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium {{ $referral->status === 'converted' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                    {{ ucfirst($referral->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-500">{{ $referral->created_at->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-500">No referrals yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($referrals->hasPages())
            <div class="mt-4">{{ $referrals->links() }}</div>
        @endif
    @endif
</div>
