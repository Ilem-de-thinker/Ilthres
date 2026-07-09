<div>
    <form wire:submit="save" class="space-y-4">
        <div class="rounded-lg border border-gray-200 bg-white p-6">
            <h2 class="text-sm font-semibold text-gray-900">Details</h2>
            <div class="mt-4 space-y-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600">Product name</label>
                    <input wire:model="name" type="text" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                    @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600">Description</label>
                    <textarea wire:model="description" rows="3" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900"></textarea>
                    @error('description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-600">Price</label>
                        <div class="mt-1 flex rounded-lg border border-gray-300 shadow-sm focus-within:border-gray-900 focus-within:ring-1 focus-within:ring-gray-900">
                            <input wire:model="price" type="number" step="0.01" min="0" class="block w-full rounded-l-lg border-0 px-3 py-2 text-sm focus:ring-0">
                            <span class="flex items-center rounded-r-lg border-l border-gray-300 bg-gray-50 px-3 text-xs text-gray-500">{{ $currency }}</span>
                        </div>
                        @error('price') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600">Compare price</label>
                        <div class="mt-1 flex rounded-lg border border-gray-300 shadow-sm focus-within:border-gray-900 focus-within:ring-1 focus-within:ring-gray-900">
                            <input wire:model="comparePrice" type="number" step="0.01" min="0" class="block w-full rounded-l-lg border-0 px-3 py-2 text-sm focus:ring-0">
                            <span class="flex items-center rounded-r-lg border-l border-gray-300 bg-gray-50 px-3 text-xs text-gray-500">{{ $currency }}</span>
                        </div>
                        @error('comparePrice') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-lg border border-gray-200 bg-white p-6">
            <h2 class="text-sm font-semibold text-gray-900">Images</h2>
            <div class="mt-4 flex flex-wrap gap-2">
                @foreach ($images as $index => $path)
                    <div class="group relative overflow-hidden rounded-lg border border-gray-200" style="width:80px;height:80px">
                        <img src="{{ Storage::url($path) }}" class="h-full w-full object-cover">
                        <button wire:click="removeImage({{ $index }})" type="button" class="absolute right-0.5 top-0.5 rounded-full bg-black/50 p-0.5 text-white opacity-0 group-hover:opacity-100 transition">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                @endforeach
                <button wire:click="openMediaPicker" type="button" class="flex items-center justify-center rounded-lg border-2 border-dashed border-gray-300 text-gray-400 hover:border-gray-900 hover:text-gray-600 transition" style="width:80px;height:80px">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/></svg>
                </button>
            </div>
        </div>

        <div class="rounded-lg border border-gray-200 bg-white p-6">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-semibold text-gray-900">Variants</h2>
                <button wire:click="addVariant" type="button" class="text-xs font-medium text-gray-900 hover:text-gray-700">+ Add group</button>
            </div>
            <div class="mt-4 space-y-3">
                @foreach ($variants as $vIndex => $variant)
                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-3">
                        <div class="flex items-center gap-2">
                            <input wire:model="variants.{{ $vIndex }}.name" type="text" placeholder="e.g. Color" class="block w-full rounded border border-gray-300 px-2 py-1 text-xs shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                            <button wire:click="removeVariant({{ $vIndex }})" type="button" class="rounded p-1 text-red-500 hover:bg-red-50"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                        </div>
                        <div class="mt-2 space-y-1">
                            @foreach ($variant['options'] ?? [] as $oIndex => $option)
                                <div class="flex items-center gap-2">
                                    <input wire:model="variants.{{ $vIndex }}.options.{{ $oIndex }}.value" type="text" placeholder="e.g. Red" class="block w-full rounded border border-gray-300 px-2 py-1 text-xs shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                                    <button wire:click="removeVariantOption({{ $vIndex }}, {{ $oIndex }})" type="button" class="rounded p-1 text-gray-400 hover:text-red-500"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                                </div>
                            @endforeach
                            <button wire:click="addVariantOption({{ $vIndex }})" type="button" class="text-xs text-gray-500 hover:text-gray-700">+ Add option</button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="flex items-center gap-6 px-1">
            <label class="flex items-center gap-2"><input wire:model="isActive" type="checkbox" class="rounded border-gray-300 text-gray-900 focus:ring-gray-900"><span class="text-sm text-gray-700">Active</span></label>
            <label class="flex items-center gap-2"><input wire:model="isFeatured" type="checkbox" class="rounded border-gray-300 text-gray-900 focus:ring-gray-900"><span class="text-sm text-gray-700">Featured</span></label>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('dashboard.products') }}" class="rounded-lg border border-gray-300 bg-white px-5 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Cancel</a>
            <button type="submit" class="rounded-lg bg-gray-900 px-6 py-2 text-sm font-semibold text-white hover:bg-gray-800">Save product</button>
        </div>
    </form>

    {{-- Media picker modal --}}
    @if ($showMediaPicker)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" wire:click.self="closeMediaPicker">
            <div class="flex w-full max-w-xl flex-col overflow-hidden rounded-xl bg-white shadow-2xl" wire:click.stop>
                <div class="flex items-center justify-between border-b border-gray-200 px-5 py-3.5 shrink-0">
                    <h3 class="text-sm font-semibold text-gray-900">Select images</h3>
                    <button wire:click="closeMediaPicker" type="button" class="rounded-full p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="overflow-y-auto p-4" style="height:380px">
                    <div class="mb-4 rounded-lg border-2 border-dashed border-gray-300 p-3">
                        <div class="flex items-center gap-2">
                            <input wire:model="mediaUpload" type="file" accept="image/*" class="block w-full text-xs text-gray-500 file:mr-2 file:rounded file:border-0 file:bg-gray-900 file:px-3 file:py-1.5 file:text-xs file:font-medium file:text-white hover:file:bg-gray-800 file:cursor-pointer cursor-pointer">
                            <button wire:click="uploadMedia" type="button" class="shrink-0 rounded bg-gray-900 px-3 py-1.5 text-xs font-medium text-white hover:bg-gray-800">Upload</button>
                        </div>
                        @error('mediaUpload') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="mb-2 text-xs text-gray-500">{{ count($pendingImages) }} selected</div>
                    <div class="flex flex-wrap gap-2">
                        @forelse ($mediaItems as $media)
                            <button wire:click="toggleImage('{{ $media->path }}')" type="button"
                                class="relative overflow-hidden rounded-lg border-2 transition focus:outline-none {{ in_array($media->path, $pendingImages) ? 'border-gray-900 ring-2 ring-gray-900' : 'border-gray-200 hover:border-gray-400' }}"
                                style="width:80px;height:80px">
                                <img src="{{ Storage::url($media->path) }}" class="h-full w-full object-cover">
                                @if (in_array($media->path, $pendingImages))
                                    <div class="absolute inset-0 bg-black/20 flex items-center justify-center"><svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg></div>
                                @endif
                            </button>
                        @empty
                            <div class="w-full py-10 text-center"><svg class="mx-auto h-8 w-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg><p class="mt-2 text-sm text-gray-400">No images yet.</p></div>
                        @endforelse
                    </div>
                </div>
                <div class="flex items-center justify-end gap-2 border-t border-gray-200 px-5 py-3.5 shrink-0">
                    <button wire:click="closeMediaPicker" type="button" class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Cancel</button>
                    <button wire:click="applyMediaPicker" type="button" class="rounded-lg bg-gray-900 px-5 py-2 text-sm font-semibold text-white hover:bg-gray-800">Apply</button>
                </div>
            </div>
        </div>
    @endif
</div>
