<div>
    @if ($saved)
        <div class="mb-4 rounded-lg bg-green-50 border border-green-200 p-3 text-sm text-green-700">Variants saved.</div>
    @endif

    <div class="rounded-lg border border-gray-200 bg-white p-6">
        <div class="flex items-center justify-between">
            <h2 class="text-sm font-semibold text-gray-900">Variant groups</h2>
            <button wire:click="addVariant" type="button"
                class="rounded-lg bg-gray-900 px-4 py-1.5 text-xs font-medium text-white hover:bg-gray-800">+ Add group</button>
        </div>

        @if (count($variants) === 0)
            <p class="mt-4 text-sm text-gray-400">No variant groups yet. Add size, color, or other options.</p>
        @endif

        <div class="mt-4 space-y-4">
            @foreach ($variants as $vIndex => $variant)
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                    <div class="flex items-center gap-3">
                        <div class="flex-1">
                            <label class="block text-xs font-medium text-gray-500">Group name</label>
                            <input wire:model="variants.{{ $vIndex }}.name" type="text" placeholder="e.g. Size, Color, Material"
                                class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                        </div>
                        <button wire:click="removeVariant({{ $vIndex }})" type="button"
                            class="mt-5 rounded-lg border border-red-200 px-3 py-2 text-xs font-medium text-red-600 hover:bg-red-50">Remove</button>
                    </div>

                    <div class="mt-3">
                        <label class="block text-xs font-medium text-gray-500">Options</label>
                        <div class="mt-1 space-y-2">
                            @foreach ($variant['options'] ?? [] as $oIndex => $option)
                                <div class="flex items-center gap-2">
                                    {{-- Image preview --}}
                                    <button wire:click="openImagePicker({{ $vIndex }}, {{ $oIndex }})" type="button"
                                        class="shrink-0 overflow-hidden rounded-lg border border-gray-300 bg-white hover:border-gray-900 transition"
                                        style="width:48px;height:48px">
                                        @if ($option['image'])
                                            <img src="{{ Storage::url($option['image']) }}" class="h-full w-full object-cover">
                                        @else
                                            <div class="flex h-full w-full items-center justify-center text-gray-300">
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/></svg>
                                            </div>
                                        @endif
                                    </button>
                                    {{-- Value --}}
                                    <input wire:model="variants.{{ $vIndex }}.options.{{ $oIndex }}.value" type="text" placeholder="e.g. Red, Small, Cotton"
                                        class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                                    {{-- Remove --}}
                                    <button wire:click="removeOption({{ $vIndex }}, {{ $oIndex }})" type="button"
                                        class="rounded p-1.5 text-gray-400 hover:text-red-500">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                            @endforeach
                            <button wire:click="addOption({{ $vIndex }})" type="button"
                                class="text-sm text-gray-500 hover:text-gray-700">+ Add option</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('dashboard.products.show', $product) }}"
                class="rounded-lg border border-gray-300 bg-white px-5 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Back</a>
            <button wire:click="save" type="button"
                class="rounded-lg bg-gray-900 px-6 py-2 text-sm font-semibold text-white hover:bg-gray-800">Save variants</button>
        </div>
    </div>

    {{-- Image picker modal --}}
    @if ($showImagePicker)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" wire:click.self="closeImagePicker">
            <div class="flex w-full max-w-xl flex-col overflow-hidden rounded-xl bg-white shadow-2xl" wire:click.stop>
                <div class="flex items-center justify-between border-b border-gray-200 px-5 py-3.5 shrink-0">
                    <h3 class="text-sm font-semibold text-gray-900">Choose image for this option</h3>
                    <button wire:click="closeImagePicker" type="button" class="rounded-full p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="overflow-y-auto p-4" style="height:380px">
                    <div class="flex flex-wrap gap-3">
                        @forelse ($mediaItems as $media)
                            <button wire:click="setOptionImage('{{ $media->path }}')" type="button"
                                class="overflow-hidden rounded-lg border-2 border-gray-200 hover:border-gray-900 transition"
                                style="width:80px;height:80px">
                                <img src="{{ Storage::url($media->path) }}" class="h-full w-full object-cover">
                            </button>
                        @empty
                            <div class="w-full py-10 text-center text-sm text-gray-400">No images in your media library.</div>
                        @endforelse
                    </div>
                </div>
                <div class="flex items-center justify-end border-t border-gray-200 px-5 py-3.5 shrink-0">
                    <button wire:click="closeImagePicker" type="button" class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Cancel</button>
                </div>
            </div>
        </div>
    @endif
</div>
