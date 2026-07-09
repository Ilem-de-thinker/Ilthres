{{--
  Bold theme · Store products — high-contrast editorial product grid.
--}}
<section id="products" style="background: var(--color-background); border-top: 2px solid var(--color-text);">
    <div class="container">
        <h2 class="section-title">Products</h2>
        
        @if ($products && $products->isNotEmpty())
            {{-- Category Filter --}}
            @php
                $uniqueCategories = $products->pluck('category')->filter()->unique('id');
            @endphp
            @if ($uniqueCategories->isNotEmpty())
                <div style="display: flex; flex-wrap: wrap; justify-content: flex-start; gap: 0.5rem; margin-bottom: 3.5rem;" x-data="{ activeCat: 'all' }">
                    <button @click="activeCat = 'all'; $dispatch('filter-category', { categoryId: 'all' })" 
                            :class="activeCat === 'all' ? 'active-filter-bold' : 'inactive-filter-bold'"
                            style="padding: 0.625rem 1.25rem; font-family: var(--font-heading); font-size: 0.8125rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; border: 2px solid var(--color-text); cursor: pointer; transition: all 0.15s; background: transparent; color: var(--color-text);">
                        All Products
                    </button>
                    @foreach ($uniqueCategories as $cat)
                        <button @click="activeCat = '{{ $cat->id }}'; $dispatch('filter-category', { categoryId: '{{ $cat->id }}' })"
                                :class="activeCat === '{{ $cat->id }}' ? 'active-filter-bold' : 'inactive-filter-bold'"
                                style="padding: 0.625rem 1.25rem; font-family: var(--font-heading); font-size: 0.8125rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; border: 2px solid var(--color-text); cursor: pointer; transition: all 0.15s; background: transparent; color: var(--color-text);">
                            {{ $cat->name }}
                        </button>
                    @endforeach
                </div>
                
                <style>
                    .active-filter-bold { 
                        background: var(--color-text) !important; 
                        color: var(--color-background) !important;
                    }
                    .inactive-filter-bold:hover { 
                        background: color-mix(in srgb, var(--color-text) 5%, transparent); 
                    }
                </style>
            @endif

            {{-- Products Grid --}}
            <div class="grid grid-3" x-data="{ currentCategory: 'all' }" @filter-category.window="currentCategory = $event.detail.categoryId">
                @foreach ($products->sortBy('sort_order') as $product)
                    <div x-show="currentCategory === 'all' || currentCategory == '{{ $product->category_id ?? 0 }}'"
                         class="card" style="display: flex; flex-direction: column; height: 100%; position: relative;">
                        
                        {{-- Featured / Sale Badge --}}
                        <div style="position: absolute; top: 1rem; left: 1rem; display: flex; flex-direction: column; gap: 0.375rem; z-index: 10;">
                            @if ($product->is_featured)
                                <span style="background: var(--color-text); color: var(--color-background); font-family: var(--font-heading); font-size: 0.6875rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; padding: 0.375rem 0.75rem; border: 1px solid var(--color-background);">
                                    Featured
                                </span>
                            @endif
                            @if ($product->compare_price && $product->compare_price > $product->price)
                                <span style="background: var(--color-accent); color: #ffffff; font-family: var(--font-heading); font-size: 0.6875rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; padding: 0.375rem 0.75rem;">
                                    Sale
                                </span>
                            @endif
                        </div>

                        {{-- Product Image --}}
                        @if ($product->images && count($product->images) > 0)
                            <a href="{{ route('business.product', [$business, $product]) }}" style="display: block; width: 100%; aspect-ratio: 1; overflow: hidden; border-bottom: 2px solid var(--color-text);">
                                <img src="{{ Storage::url($product->images[0]) }}" alt="{{ $product->name }}"
                                     style="width: 100%; height: 100%; object-fit: cover; border: none; border-radius: 0;">
                            </a>
                        @else
                            <div style="width: 100%; aspect-ratio: 1; background: color-mix(in srgb, var(--color-text) 3%, transparent); display: flex; align-items: center; justify-content: center; color: var(--color-text); font-weight: 800; text-transform: uppercase; font-size: 0.8125rem; border-bottom: 2px solid var(--color-text);">
                                No Image
                            </div>
                        @endif

                        {{-- Product Content --}}
                        <div style="padding: 1.5rem; display: flex; flex-direction: column; flex-grow: 1;">
                            <span style="font-size: 0.75rem; color: var(--color-text); margin-bottom: 0.5rem; display: block; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; opacity: 0.6;">
                                {{ $product->category?->name ?? 'General' }}
                            </span>
                            
                            <h3 style="font-size: 1.25rem; font-weight: 800; margin-bottom: 0.5rem; line-height: 1.2; text-transform: uppercase;">
                                <a href="{{ route('business.product', [$business, $product]) }}" style="color: inherit; text-decoration: none;">
                                    {{ $product->name }}
                                </a>
                            </h3>

                            @if ($product->description)
                                <p style="font-size: 0.875rem; color: var(--color-text); margin-bottom: 1.5rem; line-height: 1.5; flex-grow: 1; opacity: 0.8;">
                                    {{ Str::limit($product->description, 90) }}
                                </p>
                            @else
                                <div style="flex-grow: 1;"></div>
                            @endif

                            {{-- Price & Actions --}}
                            <div style="border-top: 2px solid var(--color-text); padding-top: 1.25rem; display: flex; align-items: center; justify-content: space-between;">
                                <div>
                                    <span style="font-weight: 900; font-size: 1.375rem; display: block; font-family: var(--font-heading);">
                                        @if ($product->variants && $product->variants->isNotEmpty())
                                            {{ number_format($product->variants->min('price'), 2) }}+
                                        @else
                                            {{ number_format($product->price, 2) }}
                                        @endif
                                    </span>
                                    @if ($product->compare_price && $product->compare_price > $product->price)
                                        <span style="font-size: 0.875rem; color: var(--color-accent); text-decoration: line-through; font-weight: 700;">
                                            {{ number_format($product->compare_price, 2) }}
                                        </span>
                                    @endif
                                </div>

                                <a href="{{ route('business.product', [$business, $product]) }}" class="btn" style="padding: 0.5rem 1rem; font-size: 0.75rem; border-width: 2px; box-shadow: none;">
                                    View &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div style="text-align: center; padding: 4rem 0; color: var(--color-text); font-weight: 800; text-transform: uppercase;">
                <p>No products listed yet.</p>
            </div>
        @endif
    </div>
</section>
