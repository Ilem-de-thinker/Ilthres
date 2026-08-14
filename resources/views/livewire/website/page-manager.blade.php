<div class="space-y-6">
    {{-- Business selector --}}
    <div class="rounded-lg border border-gray-200 bg-white p-4">
        <label for="selectedBusinessId" class="block text-sm font-medium text-gray-700">Select business</label>
        <select wire:model="selectedBusinessId" id="selectedBusinessId"
            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
            <option value="">Choose a business</option>
            @foreach ($businesses as $biz)
                <option value="{{ $biz->id }}">{{ $biz->name }}</option>
            @endforeach
        </select>
    </div>

    @if (! $website)
        <div class="rounded-lg border border-dashed border-gray-300 bg-white p-12 text-center">
            <p class="text-sm text-gray-500">Select a business above to manage its website pages.</p>
        </div>
        @php return; @endphp
    @endif

    @if ($successMessage)
        <div class="rounded-lg border border-green-200 bg-green-50 p-3 text-sm text-green-700">
            {{ $successMessage }}
            <button wire:click="$set('successMessage', null)" class="ml-2 font-medium">&times;</button>
        </div>
    @endif

    @if ($errorMessage)
        <div class="rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700">{{ $errorMessage }}</div>
    @endif

    {{-- Add page form --}}
    <div class="rounded-lg border border-gray-200 bg-white p-6">
        <h2 class="text-lg font-semibold text-gray-900">Add New Page</h2>
        <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div>
                <label class="block text-sm font-medium text-gray-700">Title</label>
                <input wire:model="newPageTitle" type="text"
                    class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900"
                    placeholder="About Us">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Slug</label>
                <input wire:model="newPageSlug" type="text"
                    class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900"
                    placeholder="about-us">
            </div>
            <div class="flex items-end">
                <button wire:click="addPage"
                    class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800">
                    Add Page
                </button>
            </div>
        </div>
    </div>

    {{-- Pages list --}}
    <div class="rounded-lg border border-gray-200 bg-white">
        <div class="border-b border-gray-200 px-6 py-4">
            <h2 class="text-lg font-semibold text-gray-900">Pages ({{ $pages->count() }})</h2>
            <p class="mt-0.5 text-xs text-gray-400">Add pages above, then click "Edit sections" to add content like hero banners, about text, product grids, and contact info.</p>
        </div>

        @if ($pages->isEmpty())
            <div class="p-12 text-center">
                <p class="text-sm text-gray-500">No pages yet. Add your first page above.</p>
            </div>
        @else
            <ul class="divide-y divide-gray-200">
                @foreach ($pages as $page)
                    <li class="px-6 py-4">
                        @if ($editingPageId === $page->id)
                            <div class="flex items-center gap-3">
                                <input wire:model="editTitle" type="text"
                                    class="block w-48 rounded-lg border border-gray-300 px-3 py-1.5 text-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                                <input wire:model="editSlug" type="text"
                                    class="block w-32 rounded-lg border border-gray-300 px-3 py-1.5 text-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                                <button wire:click="savePage({{ $page->id }})"
                                    class="rounded-lg bg-gray-900 px-3 py-1.5 text-xs font-medium text-white hover:bg-gray-800">Save</button>
                                <button wire:click="cancelEditing"
                                    class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50">Cancel</button>
                            </div>
                        @else
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <span class="text-sm text-gray-400">{{ $page->sort_order }}.</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $page->title }}</span>
                                    <span class="text-xs text-gray-400">/{{ $page->slug }}</span>
                                    @if (! $page->is_active)
                                        <span class="rounded-full bg-yellow-100 px-2 py-0.5 text-xs font-medium text-yellow-800">Hidden</span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-1">
                                    <button wire:click="movePageUp({{ $page->id }})"
                                        class="rounded p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-600" title="Move up">&#9650;</button>
                                    <button wire:click="movePageDown({{ $page->id }})"
                                        class="rounded p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-600" title="Move down">&#9660;</button>
                                    <button wire:click="togglePage({{ $page->id }})"
                                        class="rounded p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-600" title="{{ $page->is_active ? 'Hide' : 'Show' }}">
                                        {{ $page->is_active ? '&#128065;' : '&#128064;' }}
                                    </button>
                                    <a href="{{ route('dashboard.website.sections', $page->id) }}"
                                        class="inline-flex items-center gap-1 rounded p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-600" title="Edit sections">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                                        <span class="text-xs">Sections</span>
                                    </a>
                                    <button wire:click="startEditing({{ $page->id }})"
                                        class="rounded p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-600" title="Edit">&#9998;</button>
                                    <button wire:click="deletePage({{ $page->id }})"
                                        wire:confirm="Are you sure you want to delete this page?"
                                        class="rounded p-1 text-red-400 hover:bg-red-50 hover:text-red-600" title="Delete">&#128465;</button>
                                </div>
                            </div>
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
