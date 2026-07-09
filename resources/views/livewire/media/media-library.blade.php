{{--
  Media library grid — paginated grid with business selector,
  collection filter, search, and delete confirmation.
--}}
<div>
    {{-- Business selector --}}
    <div class="mb-6 rounded-lg border border-gray-200 bg-gray-50 p-4">
        <label for="media-biz-select" class="block text-sm font-medium text-gray-700">Select business</label>
        <select wire:model.live="selectedBusinessId" id="media-biz-select"
            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
            <option value="">Choose a business</option>
            @foreach ($businesses as $biz)
                <option value="{{ $biz->id }}">{{ $biz->name }} ({{ $biz->isProductStore() ? 'Store' : 'Service' }})</option>
            @endforeach
        </select>
    </div>

    @if (! $business)
        <div class="rounded-lg border border-dashed border-gray-300 bg-white p-12 text-center">
            <p class="text-sm text-gray-500">Select a business above to view its media library.</p>
        </div>
        @php return; @endphp
    @endif

    {{-- Upload section. --}}
    <div class="mb-8">
        <h2 class="text-lg font-semibold text-gray-900">Upload new files</h2>
        <p class="mt-1 text-sm text-gray-500">{{ $usedMedia }} of {{ $maxMedia }} uploads used</p>

        <div class="mt-2 h-2 w-full max-w-md overflow-hidden rounded-full bg-gray-200">
            <div class="h-full rounded-full bg-gray-900 transition-all"
                 style="width: {{ $maxMedia > 0 ? min(($usedMedia / $maxMedia) * 100, 100) : 0 }}%">
            </div>
        </div>

        <div class="mt-4">
            @livewire(\App\Domains\Media\Livewire\UploadMedia::class, key('upload-' . $business->id))
        </div>
    </div>

    {{-- Filters. --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-3">
            <select wire:model.live="collectionFilter"
                class="rounded-lg border border-gray-300 px-3 py-2 text-xs shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                <option value="">All collections</option>
                @foreach ($collections as $coll)
                    <option value="{{ $coll }}">{{ $coll }}</option>
                @endforeach
            </select>
            <input wire:model.live.debounce.300ms="search" type="search" placeholder="Search files..."
                class="rounded-lg border border-gray-300 px-3 py-2 text-xs shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900 w-48">
        </div>
        <p class="text-xs text-gray-500">{{ $mediaItems?->total() ?? 0 }} file{{ ($mediaItems?->total() ?? 0) !== 1 ? 's' : '' }}</p>
    </div>

    {{-- Grid. --}}
    <div class="mt-4 grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6">
        @forelse ($mediaItems ?? [] as $item)
            <div wire:click="expandMedia({{ $item->id }})"
                class="group relative cursor-pointer rounded-lg border border-gray-200 bg-white overflow-hidden hover:shadow-md transition">
                @if (str_starts_with($item->mime_type, 'image/'))
                    <div class="aspect-square bg-gray-100">
                        <img src="{{ Storage::url($item->path) }}" alt="{{ $item->original_name }}"
                            class="h-full w-full object-cover">
                    </div>
                @else
                    <div class="flex aspect-square items-center justify-center bg-gray-50 text-3xl text-gray-400">📄</div>
                @endif

                <div class="p-2">
                    <p class="truncate text-xs font-medium text-gray-900" title="{{ $item->original_name }}">{{ $item->original_name }}</p>
                    <p class="text-[10px] text-gray-400">{{ number_format($item->size / 1024, 1) }} KB</p>
                </div>

                <button wire:click.stop="confirmDelete({{ $item->id }})" type="button"
                    class="absolute right-1 top-1 rounded-full bg-white/80 p-1 text-red-600 opacity-0 group-hover:opacity-100 hover:bg-red-50 transition">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>

                <button onclick="event.stopPropagation(); navigator.clipboard.writeText('{{ Storage::url($item->path) }}')" type="button"
                    class="absolute right-8 top-1 rounded-full bg-white/80 p-1 text-gray-600 opacity-0 group-hover:opacity-100 hover:bg-gray-100 transition"
                    title="Copy URL">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                </button>
            </div>
        @empty
            <div class="col-span-full rounded-lg border border-dashed border-gray-300 bg-white p-12 text-center">
                <p class="text-sm text-gray-500">No files uploaded yet.</p>
            </div>
        @endforelse
    </div>

    @if ($mediaItems)
        <div class="mt-6">{{ $mediaItems->links() }}</div>
    @endif

    @if ($showExpanded && $expandedMedia)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" wire:click.self="closeExpanded">
            <div class="mx-4 flex w-full max-w-2xl flex-col overflow-hidden rounded-xl bg-white shadow-2xl">
                {{-- Header --}}
                <div class="flex items-center justify-between border-b border-gray-200 px-5 py-3">
                    <h3 class="truncate text-sm font-semibold text-gray-900">{{ $expandedMedia->original_name }}</h3>
                    <button wire:click="closeExpanded" type="button" class="rounded-full p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                {{-- Preview --}}
                <div class="flex items-center justify-center bg-gray-100 p-6">
                    @if (str_starts_with($expandedMedia->mime_type, 'image/'))
                        <img src="{{ Storage::url($expandedMedia->path) }}" alt="{{ $expandedMedia->original_name }}"
                            class="max-h-96 rounded-lg object-contain">
                    @else
                        <div class="flex h-48 w-48 items-center justify-center rounded-lg bg-white text-5xl text-gray-400">📄</div>
                    @endif
                </div>

                {{-- Details --}}
                <div class="grid grid-cols-2 gap-x-6 gap-y-3 px-5 py-4 text-sm">
                    <div>
                        <span class="text-xs font-medium text-gray-500">File name</span>
                        <p class="text-gray-900 break-all">{{ $expandedMedia->original_name }}</p>
                    </div>
                    <div>
                        <span class="text-xs font-medium text-gray-500">Size</span>
                        <p class="text-gray-900">{{ number_format($expandedMedia->size / 1024, 1) }} KB</p>
                    </div>
                    <div>
                        <span class="text-xs font-medium text-gray-500">Type</span>
                        <p class="text-gray-900">{{ $expandedMedia->mime_type }}</p>
                    </div>
                    <div>
                        <span class="text-xs font-medium text-gray-500">Collection</span>
                        <p class="text-gray-900">{{ $expandedMedia->collection ?? '—' }}</p>
                    </div>
                    <div>
                        <span class="text-xs font-medium text-gray-500">Uploaded</span>
                        <p class="text-gray-900">{{ $expandedMedia->created_at->format('M j, Y g:i A') }}</p>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-end gap-3 border-t border-gray-200 px-5 py-3">
                    <button onclick="navigator.clipboard.writeText('{{ Storage::url($expandedMedia->path) }}')"
                        class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Copy URL
                    </button>
                    <button wire:click="confirmDelete({{ $expandedMedia->id }})"
                        class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if ($showDeleteConfirm)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40" wire:click.self="cancelDelete">
            <div class="mx-4 w-full max-w-sm rounded-xl bg-white p-6 shadow-xl">
                <h3 class="text-lg font-semibold text-gray-900">Delete file?</h3>
                <p class="mt-2 text-sm text-gray-600">This cannot be undone. The file will be permanently removed.</p>
                <div class="mt-6 flex justify-end gap-3">
                    <button wire:click="cancelDelete" class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Cancel</button>
                    <button wire:click="delete" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">Delete</button>
                </div>
            </div>
        </div>
    @endif
</div>
