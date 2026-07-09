{{--
  Modern theme · Store products — premium product grid with soft shadows and refined details.
--}}
<section id="products" style="background: #ffffff; border-top: 1px solid color-mix(in srgb, var(--color-text) 5%, transparent);">
    <div class="container">
        <h2 class="section-title">Explore Products</h2>
        <p class="section-subtitle">Discover our highly curated collection</p>
        
        @if ($products && $products->isNotEmpty())
            {{-- Category Filter Pills --}}
            @php
                $uniqueCategories = $products->pluck('category')->filter()->unique('id');
            @endphp
            @if ($uniqueCategories->isNotEmpty())
                <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 0.75rem; margin-bottom: 3rem;" x-data="{ activeCat: 'all' }">
                    <button @click="activeCat = 'all'; $dispatch('filter-category', { categoryId: 'all' })" 
                            :class="activeCat === 'all' ? 'active-filter-modern' : 'inactive-filter-modern'"
                            style="padding: 0.625rem 1.25rem; font-size: 0.8125rem; font-family: var(--font-heading); font-weight: 600; border: 1px solid color-mix(in srgb, var(--color-text) 8%, transparent); border-radius: 9999px; cursor: pointer; transition: all 0.2s; background: transparent; color: var(--color-text);">
                        All Products
                    </button>
                    @foreach ($uniqueCategories as $cat)
                        <button @click="activeCat = '{{ $cat->id }}'; $dispatch('filter-category', { categoryId: '{{ $cat->id }}' })"
                                :class="activeCat === '{{ $cat->id }}' ? 'active-filter-modern' : 'inactive-filter-modern'"
                                style="padding: 0.625rem 1.25rem; font-size: 0.8125rem; font-family: var(--font-heading); font-weight: 600; border: 1px solid color-mix(in srgb, var(--color-text) 8%, transparent); border-radius: 9999px; cursor: pointer; transition: all 0.2s; background: transparent; color: var(--color-text);">
                            {{ $cat->name }}
                        </button>
                    @endforeach
                </div>
                
                <style>
                    .active-filter-modern { 
                        background: var(--color-primary) !important; 
                        color: var(--color-background) !important; 
                        border-color: var(--color-primary) !important;
                        box-shadow: 0 4px 12px color-mix(in srgb, var(--color-primary) 15%, transparent);
                    }
                    .inactive-filter-modern:hover { 
                        background: color-mix(in srgb, var(--color-text) 4%, transparent); 
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
                                <span style="background: var(--color-primary); color: var(--color-background); font-size: 0.6875rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; padding: 0.375rem 0.75rem; border-radius: 9999px;">
                                    Featured
                                </span>
                            @endif
                            @if ($product->compare_price && $product->compare_price > $product->price)
                                <span style="background: var(--color-accent); color: #ffffff; font-size: 0.6875rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; padding: 0.375rem 0.75rem; border-radius: 9999px;">
                                    Sale
                                </span>
                            @endif
                        </div>

                        {{-- Product Image --}}
                        @if ($product->images && count($product->images) > 0)
                            <a href="{{ route('business.product', [$business, $product]) }}" style="display: block; width: 100%; aspect-ratio: 1; overflow: hidden; position: relative;">
                                <img src="{{ Storage::url($product->images[0]) }}" alt="{{ $product->name }}"
                                     style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;"
                                     onmouseover="this.style.transform='scale(1.03)'" onmouseout="this.style.transform='scale(1)'">
                            </a>
                        @else
                            <div style="width: 100%; aspect-ratio: 1; background: color-mix(in srgb, var(--color-text) 3%, transparent); display: flex; align-items: center; justify-content: center; color: color-mix(in srgb, var(--color-text) 30%, transparent); font-size: 0.875rem;">
                                No image available
                            </div>
                        @endif

                        {{-- Product Content --}}
                        <div style="padding: 1.5rem; display: flex; flex-direction: column; flex-grow: 1;">
                            <span style="font-size: 0.75rem; color: var(--color-primary); margin-bottom: 0.5rem; display: block; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">
                                {{ $product->category?->name ?? 'General' }}
                            </span>
                            
                            <h3 style="font-size: 1.125rem; font-weight: 700; margin-bottom: 0.5rem; line-height: 1.3;">
                                <a href="{{ route('business.product', [$business, $product]) }}" style="color: inherit; text-decoration: none;">
                                    {{ $product->name }}
                                </a>
                            </h3>

                            @if ($product->description)
                                <p style="font-size: 0.875rem; color: color-mix(in srgb, var(--color-text) 60%, transparent); margin-bottom: 1.5rem; line-height: 1.5; flex-grow: 1;">
                                    {{ Str::limit($product->description, 90) }}
                                </p>
                            @else
                                <div style="flex-grow: 1;"></div>
                            @endif

                            {{-- Price & Actions --}}
                            <div style="border-top: 1px solid color-mix(in srgb, var(--color-text) 6%, transparent); padding-top: 1.25rem; display: flex; align-items: center; justify-content: space-between;">
                                <div>
                                    <span style="font-weight: 800; font-size: 1.25rem; color: var(--color-text);">
                                        @if ($product->variants && $product->variants->isNotEmpty())
                                            From {{ number_format($product->variants->min('price'), 2) }}
                                        @else
                                            {{ number_format($product->price, 2) }}
                                        @endif
                                    </span>
                                    @if ($product->compare_price && $product->compare_price > $product->price)
                                        <span style="font-size: 0.875rem; color: color-mix(in srgb, var(--color-text) 40%, transparent); text-decoration: line-through; margin-left: 0.375rem; font-weight: 500;">
                                            {{ number_format($product->compare_price, 2) }}
                                        </span>
                                    @endif
                                </div>

                                @if ($product->variants && $product->variants->isNotEmpty())
                                    <span style="font-size: 0.75rem; background: color-mix(in srgb, var(--color-primary) 8%, transparent); color: var(--color-primary); font-weight: 600; padding: 0.25rem 0.625rem; border-radius: 9999px;">
                                        {{ $product->variants->count() }} options
                                    </span>
                                @else
                                    <a href="{{ route('business.product', [$business, $product]) }}" style="font-size: 0.875rem; font-weight: 600; color: var(--color-primary);">
                                        Details &rarr;
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div style="text-align: center; padding: 4rem 0; color: color-mix(in srgb, var(--color-text) 30%, transparent);">
                <p>No products listed yet.</p>
            </div>
        @endif
    </div>
</section>
