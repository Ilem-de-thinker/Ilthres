<div>
    <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
        {{-- Images --}}
        <div>
            <div class="aspect-square overflow-hidden rounded-xl bg-gray-100">
                @if ($selectedImage)
                    <img src="{{ Storage::url($selectedImage) }}" class="h-full w-full object-cover">
                @else
                    <div class="flex h-full items-center justify-center text-gray-300">
                        <svg class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                @endif
            </div>
            @if (count($product->images ?? []) > 1)
                <div class="mt-3 flex gap-2 overflow-x-auto">
                    @foreach ($product->images as $img)
                        <button wire:click="selectImage('{{ $img }}')" type="button"
                            class="h-16 w-16 shrink-0 overflow-hidden rounded-lg border-2 transition {{ $selectedImage === $img ? 'border-gray-900' : 'border-gray-200 hover:border-gray-400' }}">
                            <img src="{{ Storage::url($img) }}" class="h-full w-full object-cover">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Details --}}
        <div>
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">{{ $product->name }}</h2>
                    <p class="mt-1 text-sm text-gray-500">{{ $product->slug }}</p>
                </div>
                @if ($product->is_active)
                    <span class="rounded-full bg-green-50 px-3 py-1 text-xs font-medium text-green-700">Active</span>
                @else
                    <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-500">Draft</span>
                @endif
            </div>

            <div class="mt-6 space-y-4">
                <div>
                    <span class="text-xs font-medium text-gray-500">Price</span>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($product->price, 2) }} {{ $product->currency }}</p>
                    @if ($product->compare_price)
                        <p class="text-sm text-gray-400 line-through">{{ number_format($product->compare_price, 2) }} {{ $product->currency }}</p>
                    @endif
                </div>

                @if ($product->description)
                    <div>
                        <span class="text-xs font-medium text-gray-500">Description</span>
                        <p class="mt-1 text-sm text-gray-700 whitespace-pre-wrap">{{ $product->description }}</p>
                    </div>
                @endif

                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-xs font-medium text-gray-500">Created</span>
                        <p class="text-gray-900">{{ $product->created_at->format('M j, Y') }}</p>
                    </div>
                    <div>
                        <span class="text-xs font-medium text-gray-500">Updated</span>
                        <p class="text-gray-900">{{ $product->updated_at->format('M j, Y') }}</p>
                    </div>
                    <div>
                        <span class="text-xs font-medium text-gray-500">Sort order</span>
                        <p class="text-gray-900">{{ $product->sort_order }}</p>
                    </div>
                    <div>
                        <span class="text-xs font-medium text-gray-500">Featured</span>
                        <p class="text-gray-900">{{ $product->is_featured ? 'Yes' : 'No' }}</p>
                    </div>
                </div>
            </div>

            {{-- Variants summary --}}
            @if (count($product->variants ?? []) > 0)
                <div class="mt-6">
                    <span class="text-xs font-medium text-gray-500">Variants ({{ count($product->variants) }} groups)</span>
                    <div class="mt-2 space-y-2">
                        @foreach ($product->variants as $variant)
                            <div class="rounded-lg border border-gray-200 bg-gray-50 p-3">
                                <p class="text-sm font-medium text-gray-900">{{ $variant['name'] ?: 'Unnamed' }}</p>
                                <div class="mt-1 flex flex-wrap gap-1.5">
                                    @foreach ($variant['options'] ?? [] as $option)
                                        @php $value = is_string($option) ? $option : ($option['value'] ?? ''); @endphp
                                        <span class="inline-flex items-center gap-1.5 rounded bg-white px-2 py-0.5 text-xs text-gray-600 border border-gray-200">
                                            @if (is_array($option) && ! empty($option['image']))
                                                <img src="{{ Storage::url($option['image']) }}" class="h-4 w-4 rounded object-cover">
                                            @endif
                                            {{ $value }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Actions --}}
            <div class="mt-8 flex items-center gap-3">
                <a href="{{ route('dashboard.products.edit', $product) }}"
                    class="rounded-lg bg-gray-900 px-5 py-2 text-sm font-semibold text-white hover:bg-gray-800">Edit</a>
                <a href="{{ route('dashboard.products.variants', $product) }}"
                    class="rounded-lg border border-gray-300 bg-white px-5 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Manage variants</a>
                <button wire:click="toggleActive" type="button"
                    class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    {{ $product->is_active ? 'Deactivate' : 'Activate' }}
                </button>
                <button wire:click="confirmDelete" type="button"
                    class="rounded-lg border border-red-200 bg-white px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50">Delete</button>
            </div>
        </div>
    </div>

    @if ($showDeleteConfirm)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40" wire:click.self="cancelDelete">
            <div class="mx-4 w-full max-w-sm rounded-xl bg-white p-6 shadow-xl">
                <h3 class="text-lg font-semibold text-gray-900">Delete product?</h3>
                <p class="mt-2 text-sm text-gray-600">This cannot be undone.</p>
                <div class="mt-6 flex justify-end gap-3">
                    <button wire:click="cancelDelete" class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Cancel</button>
                    <button wire:click="delete" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">Delete</button>
                </div>
            </div>
        </div>
    @endif
</div>
