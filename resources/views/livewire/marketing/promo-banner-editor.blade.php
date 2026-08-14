<div>
    @if (! $hasBusiness)
        <div class="rounded-lg border border-dashed border-gray-300 bg-white p-12 text-center">
            <p class="text-sm text-gray-500">Create a business to manage hero banners.</p>
        </div>
        @php return; @endphp
    @endif

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Banner List --}}
        <div class="lg:col-span-2">
            <div class="rounded-xl border border-gray-200 bg-white p-6">
                <h2 class="text-lg font-semibold text-gray-900">Hero Banners</h2>
                <p class="mt-1 text-sm text-gray-500">Manage the hero carousel on your public page. Multiple active hero banners rotate automatically.</p>

                <div class="mt-4 space-y-3">
                    @forelse ($banners as $banner)
                        <div class="flex items-center gap-4 rounded-lg border border-gray-200 p-4">
                            @if ($banner->image)
                                <img src="{{ Storage::url($banner->image) }}" class="h-16 w-28 rounded-lg object-cover">
                            @else
                                <div class="flex h-16 w-28 items-center justify-center rounded-lg bg-gray-100 text-gray-400">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            @endif

                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $banner->message }}</p>
                                    @if ($banner->is_active)
                                        <span class="inline-flex shrink-0 rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700">Active</span>
                                    @else
                                        <span class="inline-flex shrink-0 rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-500">Inactive</span>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-500 mt-0.5">
                                    {{ ucfirst($banner->position) }} · Order {{ $banner->sort_order }}
                                    @if ($banner->cta_text) · CTA: {{ $banner->cta_text }} @endif
                                    @if ($banner->starts_at || $banner->ends_at) · {{ $banner->starts_at?->format('d M') ?? 'Now' }}–{{ $banner->ends_at?->format('d M Y') ?? '∞' }} @endif
                                </p>
                                @if ($banner->products_count || $banner->services_count)
                                    <p class="text-xs text-gray-400 mt-0.5">
                                        @if ($banner->products_count) {{ $banner->products_count }} linked product(s) @endif
                                        @if ($banner->services_count) {{ $banner->services_count }} linked service(s) @endif
                                    </p>
                                @endif
                            </div>

                            <div class="flex items-center gap-2 shrink-0">
                                <button wire:click="edit({{ $banner->id }})" class="text-xs font-medium text-gray-600 hover:text-gray-900">Edit</button>
                            </div>
                        </div>
                    @empty
                        <p class="py-4 text-center text-sm text-gray-400">No hero banners yet. Create one to get started.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Create/Edit Form --}}
        <div>
            <div class="rounded-xl border border-gray-200 bg-white p-6">
                <h2 class="text-sm font-semibold text-gray-900">{{ $editId ? 'Edit' : 'Create' }} Hero Banner</h2>

                <form wire:submit="save" class="mt-4 space-y-4">
                    {{-- Message --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-700">Headline</label>
                        <input wire:model="message" type="text" placeholder="e.g. Summer Collection 2026"
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900 @error('message') border-red-300 @enderror">
                        @error('message') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Image --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-700">Banner Image</label>
                        @if ($image)
                            <div class="relative mt-1">
                                <img src="{{ Storage::url($image) }}" class="h-32 w-full rounded-lg object-cover">
                                <button wire:click="removeImage" type="button"
                                    class="absolute top-1 right-1 rounded-full bg-white/90 p-1 text-gray-500 hover:text-red-600 shadow-sm">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        @else
                            <button wire:click="openImagePicker" type="button"
                                class="mt-1 flex w-full items-center justify-center gap-2 rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 px-4 py-6 text-sm text-gray-500 hover:border-gray-400 hover:bg-gray-100 transition">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Choose image
                            </button>
                        @endif
                    </div>

                    {{-- Position --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-700">Position</label>
                        <select wire:model="position" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                            <option value="hero">Hero (carousel)</option>
                            <option value="top">Top bar</option>
                            <option value="bottom">Bottom bar</option>
                        </select>
                    </div>

                    {{-- Style --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-700">Style @if ($position === 'hero') (optional) @endif</label>
                        <select wire:model="style" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                            @if ($position === 'hero')
                                <option value="">None</option>
                            @endif
                            <option value="announcement">Announcement</option>
                            <option value="sale">Sale</option>
                            <option value="seasonal">Seasonal</option>
                        </select>
                    </div>

                    {{-- Sort Order --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-700">Sort Order</label>
                        <input wire:model="sortOrder" type="number" min="0"
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                    </div>

                    {{-- CTA --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-700">CTA Text</label>
                            <input wire:model="ctaText" type="text" placeholder="Shop now"
                                class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700">CTA URL</label>
                            <input wire:model="ctaUrl" type="url" placeholder="https://..."
                                class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                            @error('ctaUrl') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Link to Product/Service --}}
                    @if ($business->type === 'product_store' || $business->type === 'service_business')
                        <div>
                            <label class="block text-xs font-medium text-gray-700">
                                Link {{ $business->type === 'product_store' ? 'Products' : 'Services' }}
                            </label>
                            <p class="text-xs text-gray-400 mb-1">Connect items to this banner — they'll show in the carousel.</p>

                            {{-- Selected items --}}
                            @if ($business->type === 'product_store' && count($selectedProductsData))
                                <div class="space-y-1 mb-2">
                                    @foreach ($selectedProductsData as $product)
                                        <div class="flex items-center gap-2 rounded-lg bg-gray-50 px-2 py-1.5">
                                            @if ($product->images[0] ?? null)
                                                <img src="{{ Storage::url($product->images[0]) }}" class="h-6 w-6 rounded object-cover">
                                            @endif
                                            <span class="flex-1 text-xs text-gray-700 truncate">{{ $product->name }}</span>
                                            <button wire:click="removeProduct({{ $product->id }})" type="button" class="text-gray-400 hover:text-red-500">
                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            @if ($business->type === 'service_business' && count($selectedServicesData))
                                <div class="space-y-1 mb-2">
                                    @foreach ($selectedServicesData as $service)
                                        <div class="flex items-center gap-2 rounded-lg bg-gray-50 px-2 py-1.5">
                                            @if ($service->images[0] ?? null)
                                                <img src="{{ Storage::url($service->images[0]) }}" class="h-6 w-6 rounded object-cover">
                                            @endif
                                            <span class="flex-1 text-xs text-gray-700 truncate">{{ $service->name }}</span>
                                            <button wire:click="removeService({{ $service->id }})" type="button" class="text-gray-400 hover:text-red-500">
                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            {{-- Search --}}
                            <div class="relative">
                                <input wire:model.live="searchQuery" type="text" placeholder="Search {{ $business->type === 'product_store' ? 'products' : 'services' }}..."
                                    class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                                @if (count($searchResults))
                                    <div class="absolute z-10 mt-1 w-full rounded-lg border border-gray-200 bg-white shadow-lg max-h-48 overflow-y-auto">
                                        @foreach ($searchResults as $result)
                                            <button type="button"
                                                wire:click="{{ $result['type'] === 'product' ? 'addProduct' : 'addService' }}({{ $result['id'] }})"
                                                class="flex w-full items-center gap-2 px-3 py-2 text-left text-sm hover:bg-gray-50">
                                                @if ($result['image'])
                                                    <img src="{{ Storage::url($result['image']) }}" class="h-6 w-6 rounded object-cover">
                                                @endif
                                                <span class="text-gray-700">{{ $result['name'] }}</span>
                                            </button>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- Schedule --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-700">Start</label>
                            <input wire:model="startsAt" type="date" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700">End</label>
                            <input wire:model="endsAt" type="date" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                            @error('endsAt') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Active --}}
                    <label class="flex items-center gap-2">
                        <input wire:model="isActive" type="checkbox" class="rounded border-gray-300 text-gray-900 focus:ring-gray-900">
                        <span class="text-sm text-gray-700">Active</span>
                    </label>

                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800">
                            {{ $editId ? 'Update' : 'Create' }} Banner
                        </button>
                        @if ($editId)
                            <button type="button" wire:click="resetForm"
                                class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Cancel</button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Image Picker Modal --}}
    @if ($showImagePicker)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" wire:click.self="closeImagePicker">
            <div class="flex w-full max-w-xl flex-col overflow-hidden rounded-xl bg-white shadow-2xl" wire:click.stop>
                <div class="flex items-center justify-between border-b border-gray-200 px-5 py-3.5 shrink-0">
                    <h3 class="text-sm font-semibold text-gray-900">Choose banner image</h3>
                    <button wire:click="closeImagePicker" type="button" class="rounded-full p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="overflow-y-auto p-4" style="height:380px">
                    <div class="flex flex-wrap gap-3">
                        @forelse ($mediaItems as $media)
                            <button wire:click="bannerImageSelected('{{ $media->path }}')" type="button"
                                class="overflow-hidden rounded-lg border-2 border-gray-200 hover:border-gray-900 transition"
                                style="width:120px;height:80px">
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
