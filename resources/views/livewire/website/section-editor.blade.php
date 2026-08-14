<div class="space-y-6">
    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-gray-500">
        <a href="{{ route('dashboard.website') }}" class="hover:text-gray-900">Pages</a>
        <span>/</span>
        <span class="font-medium text-gray-900">{{ $page->title }}</span>
    </div>

    @if ($successMessage)
        <div class="rounded-lg border border-green-200 bg-green-50 p-3 text-sm text-green-700">
            {{ $successMessage }}
            <button wire:click="$set('successMessage', null)" class="ml-2 font-medium">&times;</button>
        </div>
    @endif

    @if ($errorMessage)
        <div class="rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700">{{ $errorMessage }}</div>
    @endif

    {{-- Add section form --}}
    <div class="rounded-lg border border-gray-200 bg-white p-6">
        <h2 class="text-lg font-semibold text-gray-900">Add Section</h2>
        <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div>
                <label class="block text-sm font-medium text-gray-700">Type</label>
                <select wire:model="newSectionType"
                    class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                    @foreach ($sectionTypes as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Title (optional)</label>
                <input wire:model="newSectionTitle" type="text"
                    class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900"
                    placeholder="Section heading">
            </div>
            <div class="flex items-end">
                <button wire:click="addSection"
                    class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800">
                    Add Section
                </button>
            </div>
        </div>
    </div>

    {{-- Sections list --}}
    <div class="rounded-lg border border-gray-200 bg-white">
        <div class="border-b border-gray-200 px-6 py-4">
            <h2 class="text-lg font-semibold text-gray-900">Sections ({{ $sections->count() }})</h2>
        </div>

        @if ($sections->isEmpty())
            <div class="p-12 text-center">
                <p class="text-sm text-gray-500">No sections yet. Add your first section above.</p>
            </div>
        @else
            <ul class="divide-y divide-gray-200">
                @foreach ($sections as $section)
                    <li class="px-6 py-4">
                        @if ($editingSectionId === $section->id)
                            <div class="space-y-4">
                                <div class="flex items-center gap-3">
                                    <span class="text-sm font-medium text-gray-700">{{ $sectionTypes[$section->type] ?? $section->type }}</span>
                                    <input wire:model="editTitle" type="text" placeholder="Section title"
                                        class="block w-48 rounded-lg border border-gray-300 px-3 py-1.5 text-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                                </div>

                                {{-- Dynamic fields based on section type --}}
                                @if ($section->type === 'hero')
                                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-500">Heading</label>
                                            <input wire:model="editData.heading" type="text" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-1.5 text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-500">Subheading</label>
                                            <input wire:model="editData.subheading" type="text" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-1.5 text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-500">CTA Text</label>
                                            <input wire:model="editData.cta_text" type="text" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-1.5 text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-500">CTA Link</label>
                                            <input wire:model="editData.cta_link" type="text" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-1.5 text-sm">
                                        </div>
                                    </div>
                                @elseif ($section->type === 'about' || $section->type === 'text')
                                    <div class="grid grid-cols-1 gap-3">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-500">Heading</label>
                                            <input wire:model="editData.heading" type="text" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-1.5 text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-500">Body</label>
                                            <textarea wire:model="editData.body" rows="4" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-1.5 text-sm"></textarea>
                                        </div>
                                    </div>
                                @elseif ($section->type === 'contact')
                                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-500">Heading</label>
                                            <input wire:model="editData.heading" type="text" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-1.5 text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-500">Phone</label>
                                            <input wire:model="editData.phone" type="text" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-1.5 text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-500">Email</label>
                                            <input wire:model="editData.email" type="text" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-1.5 text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-500">WhatsApp</label>
                                            <input wire:model="editData.whatsapp" type="text" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-1.5 text-sm">
                                        </div>
                                        <div class="sm:col-span-2">
                                            <label class="block text-xs font-medium text-gray-500">Address</label>
                                            <input wire:model="editData.address" type="text" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-1.5 text-sm">
                                        </div>
                                    </div>
                                @elseif ($section->type === 'faq')
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500">Heading</label>
                                        <input wire:model="editData.heading" type="text" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-1.5 text-sm">
                                    </div>
                                    <p class="text-xs text-gray-400 mt-1">FAQ items are managed as JSON. Edit the items array in the data field.</p>
                                @elseif ($section->type === 'cta')
                                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-500">Heading</label>
                                            <input wire:model="editData.heading" type="text" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-1.5 text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-500">Button Text</label>
                                            <input wire:model="editData.button_text" type="text" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-1.5 text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-500">Button Link</label>
                                            <input wire:model="editData.button_link" type="text" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-1.5 text-sm">
                                        </div>
                                    </div>
                                @else
                                    <div class="rounded-lg border border-dashed border-gray-300 p-4">
                                        <p class="text-xs text-gray-400">Content editing for {{ $sectionTypes[$section->type] ?? $section->type }} sections coming soon.</p>
                                        @if (isset($section->data))
                                            <pre class="mt-2 overflow-x-auto text-xs text-gray-500">{{ json_encode($section->data, JSON_PRETTY_PRINT) }}</pre>
                                        @endif
                                    </div>
                                @endif

                                <div class="flex items-center gap-2">
                                    <button wire:click="saveSection({{ $section->id }})"
                                        class="rounded-lg bg-gray-900 px-3 py-1.5 text-xs font-medium text-white hover:bg-gray-800">Save</button>
                                    <button wire:click="cancelEditing"
                                        class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50">Cancel</button>
                                </div>
                            </div>
                        @else
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <span class="text-sm text-gray-400">{{ $section->sort_order }}.</span>
                                    <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-700">
                                        {{ $sectionTypes[$section->type] ?? $section->type }}
                                    </span>
                                    <span class="text-sm font-medium text-gray-900">{{ $section->title ?? 'Untitled' }}</span>
                                    @if (! $section->is_active)
                                        <span class="rounded-full bg-yellow-100 px-2 py-0.5 text-xs font-medium text-yellow-800">Hidden</span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-1">
                                    <button wire:click="moveSectionUp({{ $section->id }})"
                                        class="rounded p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-600" title="Move up">&#9650;</button>
                                    <button wire:click="moveSectionDown({{ $section->id }})"
                                        class="rounded p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-600" title="Move down">&#9660;</button>
                                    <button wire:click="toggleSection({{ $section->id }})"
                                        class="rounded p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-600" title="{{ $section->is_active ? 'Hide' : 'Show' }}">
                                        {{ $section->is_active ? '&#128065;' : '&#128064;' }}
                                    </button>
                                    <button wire:click="startEditing({{ $section->id }})"
                                        class="rounded p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-600" title="Edit">&#9998;</button>
                                    <button wire:click="deleteSection({{ $section->id }})"
                                        wire:confirm="Are you sure you want to delete this section?"
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
