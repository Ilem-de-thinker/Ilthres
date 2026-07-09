{{--
  Default theme · Store products — clean, structured, minimal product showcase.
--}}
<section id="products">
    <div class="container">
        <h2 class="section-title">Products</h2>
        
        @if ($products && $products->isNotEmpty())
            {{-- Category Filter Pills --}}
            @php
                $uniqueCategories = $products->pluck('category')->filter()->unique('id');
            @endphp
            @if ($uniqueCategories->isNotEmpty())
                <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 0.5rem; margin-bottom: 3rem;" x-data="{ activeCat: 'all' }">
                    <button @click="activeCat = 'all'; $dispatch('filter-category', { categoryId: 'all' })" 
                            :class="activeCat === 'all' ? 'active-filter' : 'inactive-filter'"
                            style="padding: 0.5rem 1.25rem; font-size: 0.8125rem; font-weight: 500; border: 1px solid color-mix(in srgb, var(--color-text) 12%, transparent); border-radius: var(--radius); cursor: pointer; transition: all 0.15s; background: transparent; color: var(--color-text);">
                        All Products
                    </button>
                    @foreach ($uniqueCategories as $cat)
                        <button @click="activeCat = '{{ $cat->id }}'; $dispatch('filter-category', { categoryId: '{{ $cat->id }}' })"
                                :class="activeCat === '{{ $cat->id }}' ? 'active-filter' : 'inactive-filter'"
                                style="padding: 0.5rem 1.25rem; font-size: 0.8125rem; font-weight: 500; border: 1px solid color-mix(in srgb, var(--color-text) 12%, transparent); border-radius: var(--radius); cursor: pointer; transition: all 0.15s; background: transparent; color: var(--color-text);">
                            {{ $cat->name }}
                        </button>
                    @endforeach
                </div>
                
                <style>
                    .active-filter { background: var(--color-primary) !important; color: var(--color-background) !important; border-color: var(--color-primary) !important; }
                    .inactive-filter:hover { background: color-mix(in srgb, var(--color-text) 4%, transparent); }
                </style>
            @endif

            {{-- Products Grid --}}
            <div class="grid grid-4" x-data="{ currentCategory: 'all' }" @filter-category.window="currentCategory = $event.detail.categoryId">
                @foreach ($products->sortBy('sort_order') as $product)
                    <div x-show="currentCategory === 'all' || currentCategory == '{{ $product->category_id ?? 0 }}'"
                         style="background: var(--color-background); display: flex; flex-direction: column; height: 100%; text-decoration: none; position: relative;">
                        
                        {{-- Featured Badge --}}
                        @if ($product->is_featured)
                            <span style="position: absolute; top: 0.75rem; left: 0.75rem; background: var(--color-primary); color: var(--color-background); font-size: 0.625rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; padding: 0.25rem 0.5rem; border-radius: 2px; z-index: 10;">
                                Featured
                            </span>
                        @endif

                        {{-- Product Image --}}
                        @if ($product->images && count($product->images) > 0)
                            <a href="{{ route('business.product', [$business, $product]) }}" style="display: block; width: 100%; aspect-ratio: 1; overflow: hidden; background: color-mix(in srgb, var(--color-text) 2%, transparent); border: 1px solid color-mix(in srgb, var(--color-text) 6%, transparent); border-radius: var(--radius);">
                                <img src="{{ Storage::url($product->images[0]) }}" alt="{{ $product->name }}"
                                     style="width: 100%; height: 100%; object-fit: cover; transition: opacity 0.2s ease;"
                                     onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                            </a>
                        @else
                            <div style="width: 100%; aspect-ratio: 1; background: color-mix(in srgb, var(--color-text) 3%, transparent); border: 1px solid color-mix(in srgb, var(--color-text) 6%, transparent); border-radius: var(--radius); display: flex; align-items: center; justify-content: center; color: color-mix(in srgb, var(--color-text) 30%, transparent); font-size: 0.8125rem;">
                                No image
                            </div>
                        @endif

                        {{-- Product Content --}}
                        <div style="padding: 1rem 0; display: flex; flex-direction: column; flex-grow: 1;">
                            <span style="font-size: 0.75rem; color: color-mix(in srgb, var(--color-text) 50%, transparent); margin-bottom: 0.25rem; display: block; font-weight: 500;">
                                {{ $product->category?->name ?? 'General' }}
                            </span>
                            
                            <h3 style="font-size: 0.9375rem; font-weight: 500; margin-bottom: 0.375rem; line-height: 1.4;">
                                <a href="{{ route('business.product', [$business, $product]) }}" style="color: var(--color-text); text-decoration: none;">
                                    {{ $product->name }}
                                </a>
                            </h3>

                            @if ($product->description)
                                <p style="font-size: 0.8125rem; color: color-mix(in srgb, var(--color-text) 60%, transparent); margin-bottom: 0.75rem; line-height: 1.5; flex-grow: 1;">
                                    {{ Str::limit($product->description, 70) }}
                                </p>
                            @else
                                <div style="flex-grow: 1;"></div>
                            @endif

                            {{-- Price & Info --}}
                            <div style="display: flex; align-items: baseline; gap: 0.5rem; margin-top: auto;">
                                <span style="font-weight: 600; font-size: 0.9375rem; color: var(--color-text);">
                                    @if ($product->variants && $product->variants->isNotEmpty())
                                        From {{ number_format($product->variants->min('price'), 2) }}
                                    @else
                                        {{ number_format($product->price, 2) }}
                                    @endif
                                </span>
                                @if ($product->compare_price && $product->compare_price > $product->price)
                                    <span style="font-size: 0.8125rem; color: color-mix(in srgb, var(--color-text) 40%, transparent); text-decoration: line-through;">
                                        {{ number_format($product->compare_price, 2) }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div style="text-align: center; padding: 4rem 0; color: color-mix(in srgb, var(--color-text) 40%, transparent);">
                <p>No products listed yet.</p>
            </div>
        @endif
    </div>
</section>
