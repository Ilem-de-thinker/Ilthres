<div>
    @if (! $hasBusiness)
        <div class="rounded-lg border border-dashed border-gray-300 bg-white p-12 text-center">
            <p class="text-sm text-gray-500">Create a business to manage lead forms.</p>
        </div>
        @php return; @endphp
    @endif

    <div class="rounded-xl border border-gray-200 bg-white">
        <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Lead Forms</h2>
                <p class="text-sm text-gray-500">{{ count($forms) }} form(s) &mdash; {{ $forms->sum('submissions_count') }} total submissions</p>
            </div>
            <a href="{{ route('dashboard.marketing.leads.create') }}" class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800">Create Form</a>
        </div>

        <div class="divide-y divide-gray-100">
            @forelse ($forms as $form)
                <div class="flex items-center justify-between px-6 py-4">
                    <div>
                        <a href="{{ route('dashboard.marketing.leads.submissions', $form) }}" class="text-sm font-medium text-gray-900 hover:text-gray-600">{{ $form->name }}</a>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $form->fields_count }} field(s) &middot; {{ $form->submissions_count }} submission(s)</p>
                    </div>
                    <div class="flex items-center gap-2">
                        @if ($form->is_active)
                            <span class="inline-flex rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700">Active</span>
                        @else
                            <span class="inline-flex rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-500">Inactive</span>
                        @endif
                        <a href="{{ route('dashboard.marketing.leads.edit', $form) }}" class="text-xs font-medium text-gray-600 hover:text-gray-900">Edit</a>
                        <button wire:click="confirmDelete({{ $form->id }})" class="text-xs font-medium text-red-600 hover:text-red-700">Delete</button>
                    </div>
                </div>
            @empty
                <p class="px-6 py-8 text-center text-sm text-gray-400">No forms yet.</p>
            @endforelse
        </div>
    </div>

    @if ($showDeleteConfirm)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
            <div class="rounded-xl bg-white p-6 shadow-xl max-w-sm">
                <h3 class="text-sm font-semibold text-gray-900">Delete form?</h3>
                <p class="mt-1 text-xs text-gray-500">All submissions will also be removed.</p>
                <div class="mt-4 flex justify-end gap-2">
                    <button wire:click="cancelDelete" class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50">Cancel</button>
                    <button wire:click="delete" class="rounded-lg bg-red-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-red-700">Delete</button>
                </div>
            </div>
        </div>
    @endif
</div>
