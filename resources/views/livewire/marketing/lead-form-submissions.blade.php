<div>
    @if (! $hasBusiness)
        <div class="rounded-lg border border-dashed border-gray-300 bg-white p-12 text-center">
            <p class="text-sm text-gray-500">Create a business to view submissions.</p>
        </div>
        @php return; @endphp
    @endif

    <div class="rounded-xl border border-gray-200 bg-white">
        <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
            <h2 class="text-lg font-semibold text-gray-900">Submissions</h2>
            <div class="flex gap-2">
                <select wire:model.live="formFilter" class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs">
                    <option value="">All Forms</option>
                    @foreach ($forms as $f)
                        <option value="{{ $f->id }}">{{ $f->name }}</option>
                    @endforeach
                </select>
                <select wire:model.live="statusFilter" class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs">
                    <option value="">All Status</option>
                    <option value="new">New</option>
                    <option value="read">Read</option>
                    <option value="contacted">Contacted</option>
                    <option value="converted">Converted</option>
                    <option value="archived">Archived</option>
                </select>
            </div>
        </div>

        <div class="divide-y divide-gray-100">
            @forelse ($submissions as $submission)
                <div wire:key="{{ $submission->id }}" class="flex items-center justify-between px-6 py-4">
                    <div class="min-w-0 flex-1">
                        <button wire:click="viewDetail({{ $submission->id }})" class="text-left">
                            <p class="truncate text-sm font-medium text-gray-900 hover:text-gray-600">
                                {{ $submission->data['name'] ?? $submission->data['email'] ?? 'Submission #'.$submission->id }}
                            </p>
                        </button>
                        <p class="text-xs text-gray-500">{{ $submission->form->name }} &middot; {{ $submission->created_at->diffForHumans() }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        @php
                            $colors = ['new' => 'bg-blue-100 text-blue-700', 'read' => 'bg-gray-100 text-gray-700', 'contacted' => 'bg-yellow-100 text-yellow-700', 'converted' => 'bg-green-100 text-green-700', 'archived' => 'bg-gray-100 text-gray-500'];
                        @endphp
                        <select wire:change="updateStatus({{ $submission->id }}, $event.target.value)" class="rounded-lg border border-gray-300 px-2 py-1 text-xs">
                            @foreach (['new', 'read', 'contacted', 'converted', 'archived'] as $s)
                                <option value="{{ $s }}" @selected($submission->status === $s)>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @empty
                <p class="px-6 py-8 text-center text-sm text-gray-400">No submissions yet.</p>
            @endforelse
        </div>

        @if ($submissions->hasPages())
            <div class="border-t border-gray-200 px-6 py-3">
                {{ $submissions->links() }}
            </div>
        @endif
    </div>

    @if ($detail)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
            <div class="rounded-xl bg-white p-6 shadow-xl max-w-lg w-full mx-4 max-h-[80vh] overflow-y-auto">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-900">Submission Details</h3>
                    <button wire:click="closeDetail" class="text-xs text-gray-500 hover:text-gray-700">Close</button>
                </div>
                <div class="mt-4 space-y-3">
                    <div class="text-xs text-gray-500">Form: {{ $detail->form->name }} &middot; {{ $detail->created_at->format('d M Y, g:i A') }}</div>
                    @foreach ($detail->data as $key => $value)
                        <div>
                            <span class="text-xs font-medium text-gray-700">{{ ucfirst($key) }}:</span>
                            <span class="text-xs text-gray-600">{{ is_array($value) ? json_encode($value) : $value }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>