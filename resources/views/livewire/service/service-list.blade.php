<div>
    {{-- Business selector --}}
    <div class="mb-6 rounded-lg border border-gray-200 bg-gray-50 p-4">
        <label for="service-biz-select" class="block text-sm font-medium text-gray-700">Select business</label>
        <select wire:model.live="selectedBusinessId" id="service-biz-select"
            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
            <option value="">Choose a business</option>
            @foreach ($businesses as $biz)
                <option value="{{ $biz->id }}">{{ $biz->name }} (Service)</option>
            @endforeach
        </select>
    </div>

    @if (! $business)
        <div class="rounded-lg border border-dashed border-gray-300 bg-white p-12 text-center">
            <p class="text-sm text-gray-500">Select a business above to view its services.</p>
        </div>
        @php return; @endphp
    @endif

    {{-- Filters + Add button --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-3">
            <select wire:model.live="statusFilter"
                class="rounded-lg border border-gray-300 px-3 py-2 text-xs shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                <option value="">All statuses</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
            <input wire:model.live.debounce.300ms="search" type="search" placeholder="Search services..."
                class="rounded-lg border border-gray-300 px-3 py-2 text-xs shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900 w-48">
        </div>
        <a href="{{ route('dashboard.services.create') }}"
            class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800">
            + Add service
        </a>
    </div>

    {{-- Grid --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
        @forelse ($services ?? [] as $item)
            <a href="{{ route('dashboard.services.show', $item) }}" class="group relative rounded-lg border border-gray-200 bg-white overflow-hidden hover:shadow-md transition">
                <div class="aspect-[4/3] bg-gray-100 flex items-center justify-center">
                    @if ($item->images && count($item->images) > 0)
                        <img src="{{ Storage::url($item->images[0]) }}" alt="{{ $item->name }}"
                            class="h-full w-full object-cover">
                    @else
                        <svg class="h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    @endif
                </div>

                <div class="p-3">
                    <div class="flex items-start justify-between gap-2">
                        <p class="truncate text-sm font-medium text-gray-900" title="{{ $item->name }}">{{ $item->name }}</p>
                        <div class="flex shrink-0 gap-1">
                            @if (! $item->is_active)
                                <span class="rounded-full bg-gray-100 px-2 py-0.5 text-[10px] font-medium text-gray-500">Draft</span>
                            @elseif ($item->is_public)
                                <span class="rounded-full bg-green-100 px-2 py-0.5 text-[10px] font-medium text-green-700">Public</span>
                            @else
                                <span class="rounded-full bg-gray-100 px-2 py-0.5 text-[10px] font-medium text-gray-500">Private</span>
                            @endif
                        </div>
                    </div>
                    <p class="mt-1 text-sm font-semibold text-gray-900">{{ number_format($item->price, 2) }} {{ $item->currency }}</p>
                    @if ($item->duration_minutes)
                        <p class="text-xs text-gray-400">{{ $item->duration_minutes }} min</p>
                    @endif
                </div>

                {{-- Hover actions --}}
                <div class="absolute right-1 top-1 flex gap-1 opacity-0 group-hover:opacity-100 transition" onclick="event.stopPropagation()">
                    <a href="{{ route('dashboard.services.edit', $item) }}"
                        class="rounded-full bg-white/80 p-1.5 text-gray-600 hover:bg-white hover:text-gray-900"
                        title="Edit">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </a>
                    <button wire:click="confirmDelete({{ $item->id }})" type="button"
                        class="rounded-full bg-white/80 p-1.5 text-red-600 hover:bg-white hover:text-red-800"
                        title="Delete">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
            </a>
        @empty
            <div class="col-span-full rounded-lg border border-dashed border-gray-300 bg-white p-12 text-center">
                <p class="text-sm text-gray-500">No services yet.</p>
                <a href="{{ route('dashboard.services.create') }}" class="mt-3 inline-block text-sm font-medium text-gray-900 hover:text-gray-700">+ Add your first service</a>
            </div>
        @endforelse
    </div>

    @if ($services)
        <div class="mt-6">{{ $services->links() }}</div>
    @endif

    @if ($showDeleteConfirm)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40" wire:click.self="cancelDelete">
            <div class="mx-4 w-full max-w-sm rounded-xl bg-white p-6 shadow-xl">
                <h3 class="text-lg font-semibold text-gray-900">Delete service?</h3>
                <p class="mt-2 text-sm text-gray-600">This cannot be undone. The service will be permanently removed.</p>
                <div class="mt-6 flex justify-end gap-3">
                    <button wire:click="cancelDelete" class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Cancel</button>
                    <button wire:click="delete" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">Delete</button>
                </div>
            </div>
        </div>
    @endif
</div>
