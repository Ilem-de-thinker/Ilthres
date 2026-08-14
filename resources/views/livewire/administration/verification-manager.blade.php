<div>
    <div class="mb-4 flex items-center gap-2">
        <label class="text-sm font-medium text-gray-700">Filter:</label>
        <select wire:model.live="filterStatus" class="rounded-lg border px-3 py-1.5 text-sm">
            <option value="">Select Verification Status</option>
            <option value="pending">Pending</option>
            <option value="under_review">Under Review</option>
            <option value="verified">Verified</option>
            <option value="rejected">Rejected</option>
            <option value="unverified">Unverified</option>
        </select>
    </div>

    <div class="rounded-lg border border-gray-200 bg-white">
        <div class="border-b border-gray-200 px-6 py-4">
            <h3 class="text-sm font-semibold text-gray-900">KYC Verifications</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Business</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Owner</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Documents</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Submitted</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse ($verifications as $v)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $v->business->name }}</div>
                                <div class="text-xs text-gray-500">{{ $v->business->email }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $v->business->user?->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                @if ($v->documents)
                                    <div class="flex flex-wrap gap-1" >
                                        @foreach ($v->documents as $type => $doc)
                                            <span
                                                class="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-xs text-gray-600">
                                                {{ str_replace('_', ' ', $type) }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span
                                    class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium
                                    {{ $v->status === 'verified' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $v->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $v->status === 'pending' ? 'bg-amber-100 text-amber-800' : '' }}
                                    {{ $v->status === 'under_review' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $v->status === 'unverified' ? 'bg-gray-100 text-gray-500' : '' }}">
                                    {{ str_replace('_', ' ', $v->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $v->submitted_at?->diffForHumans() ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if ($v->isPending())
                                    <button wire:click="selectForReview({{ $v->id }})"
                                        class="rounded-lg bg-gray-900 px-3 py-1.5 text-xs font-medium text-white hover:bg-gray-800">Review</button>
                                @elseif ($v->isUnderReview() && $reviewingId === $v->id)
                                    <span class="text-xs text-blue-600">Reviewing...</span>
                                @else
                                    <span class="text-xs text-gray-400">{{ ucfirst($v->status) }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-sm text-gray-400">
                                No {{ str_replace('_', ' ', $filterStatus) }} verifications found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>

        <div class="border-t px-6 py-3">
            {{ $verifications->links() }}
        </div>
    </div>

    {{-- Review modal --}}
    @if ($reviewingId)
        @php $v = $verifications->firstWhere('id', $reviewingId); @endphp
        @if ($v)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/30"
                wire:click.self="$set('reviewingId', null)">
                <div class="w-full max-w-2xl rounded-xl bg-white p-6 shadow-xl max-h-[90vh] overflow-y-auto"
                    wire:click.self="">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Review Verification</h3>
                        <button wire:click="$set('reviewingId', null)"
                            class="text-gray-400 hover:text-gray-600">&times;</button>
                    </div>

                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-900">{{ $v->business->name }}</p>
                        <p class="text-xs text-gray-500">Owner: {{ $v->business->user?->name }}
                            ({{ $v->business->user?->email }})</p>
                    </div>

                    {{-- Documents --}}
                    <div class="mb-6 space-y-3">
                        <h4 class="text-sm font-semibold text-gray-900">Submitted Documents</h4>
                        @foreach ($v->documents ?? [] as $type => $doc)
                            <div class="flex items-center justify-between rounded-lg border bg-gray-50 p-3">
                                <div>
                                    <span
                                        class="text-sm font-medium text-gray-900">{{ str_replace('_', ' ', ucfirst($type)) }}</span>
                                    <p class="text-xs text-gray-500">{{ $doc['original_name'] ?? 'Unknown' }}</p>
                                </div>
                                <a href="{{ Storage::url($doc['path']) }}" target="_blank"
                                    class="rounded-lg bg-white border px-3 py-1 text-xs font-medium text-gray-700 hover:bg-gray-50">View</a>
                            </div>
                        @endforeach
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Admin notes (optional)</label>
                        <textarea wire:model="adminNotes" rows="2" class="mt-1 block w-full rounded-lg border px-3 py-2 text-sm"></textarea>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button wire:click="reject"
                            class="rounded-lg border border-red-300 px-4 py-2 text-sm font-medium text-red-700 hover:bg-red-50">Reject</button>
                        <button wire:click="approve"
                            class="rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-700">Approve</button>
                    </div>
                </div>
            </div>
        @endif
    @endif
</div>
