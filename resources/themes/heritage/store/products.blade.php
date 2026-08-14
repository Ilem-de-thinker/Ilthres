{{-- Heritage theme · Store products --}}
<section id="products" style="padding: 96px 0; border-top: 0.5px solid #d4d0c8;">
    <div style="max-width: 1280px; margin: 0 auto; padding: 0 64px;">
        <div style="text-align: center; margin-bottom: 4rem;">
            <div style="width: 32px; height: 0.5px; background: #735c00; margin: 0 auto 1.5rem;"></div>
            <h2 style="font-family: 'Playfair Display', serif; font-weight: 600; font-size: 2rem; margin-bottom: 0.75rem; letter-spacing: -0.01em;">The Collection</h2>
            <span class="heritage-label" style="font-size: 0.6875rem; color: #735c00;">Curated Selection</span>
        </div>

        @if ($products && $products->isNotEmpty())
            @php $uniqueCategories = $products->pluck('category')->filter()->unique('id'); @endphp
            @if ($uniqueCategories->isNotEmpty())
                <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 1rem; margin-bottom: 3rem;" x-data="{ activeCat: 'all' }">
                    <button @click="activeCat = 'all'; $dispatch('filter-category', { categoryId: 'all' })"
                            :style="activeCat === 'all' ? 'background: #000; color: #fbf9f9; border-color: #000;' : 'background: transparent; color: #000; border-color: #d4d0c8;'"
                        class="heritage-label" style="padding: 0.5rem 1.25rem; font-size: 0.6875rem; border: 0.5px solid; cursor: pointer; transition: all 0.3s;">
                        All
                    </button>
                    @foreach ($uniqueCategories as $cat)
                        <button @click="activeCat = '{{ $cat->id }}'; $dispatch('filter-category', { categoryId: '{{ $cat->id }}' })"
                                :style="activeCat === '{{ $cat->id }}' ? 'background: #000; color: #fbf9f9; border-color: #000;' : 'background: transparent; color: #000; border-color: #d4d0c8;'"
                            class="heritage-label" style="padding: 0.5rem 1.25rem; font-size: 0.6875rem; border: 0.5px solid; cursor: pointer; transition: all 0.3s;">
                            {{ $cat->name }}
                        </button>
                    @endforeach
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3" style="gap: 2rem;" x-data="{ currentCategory: 'all' }" @filter-category.window="currentCategory = $event.detail.categoryId">
                @foreach ($products->sortBy('sort_order') as $product)
                    <div x-show="currentCategory === 'all' || currentCategory == '{{ $product->category_id ?? 0 }}'"
                         style="border: 0.5px solid #d4d0c8; transition: all 0.3s; display: flex; flex-direction: column; position: relative;"
                         onmouseover="this.style.borderColor='#735c00';"
                         onmouseout="this.style.borderColor='#d4d0c8';">

                        @if ($product->is_featured)
                            <div style="position: absolute; top: 1rem; left: 1rem; z-index: 10;">
                                <span class="heritage-label" style="background: #000; color: #fbf9f9; font-size: 0.5625rem; padding: 0.25rem 0.625rem;">Featured</span>
                            </div>
                        @endif

                        @if ($product->images && count($product->images) > 0)
                            <a href="{{ route('business.product', [$business, $product]) }}" style="display: block; width: 100%; aspect-ratio: 1; overflow: hidden; border-bottom: 0.5px solid #d4d0c8;">
                                <img src="{{ Storage::url($product->images[0]) }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover; transition: opacity 0.4s;" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                            </a>
                        @else
                            <div style="width: 100%; aspect-ratio: 1; background: #f5f3ef; display: flex; align-items: center; justify-content: center; border-bottom: 0.5px solid #d4d0c8;">
                                <span class="material-symbols-outlined" style="color: #d4d0c8; font-size: 48px;">inventory_2</span>
                            </div>
                        @endif

                        <div style="padding: 1.5rem; display: flex; flex-direction: column; flex-grow: 1;">
                            <span class="heritage-label" style="font-size: 0.5625rem; color: #735c00; margin-bottom: 0.625rem; display: block;">{{ $product->category?->name ?? 'General' }}</span>
                            <h3 style="font-family: 'Playfair Display', serif; font-size: 1.0625rem; font-weight: 600; margin-bottom: 0.5rem; line-height: 1.3;">
                                <a href="{{ route('business.product', [$business, $product]) }}" style="color: inherit; text-decoration: none;">{{ $product->name }}</a>
                            </h3>
                            @if ($product->description)
                                <p style="font-family: 'Hanken Grotesk', sans-serif; font-size: 0.8125rem; color: #888; margin-bottom: 1.25rem; line-height: 1.6; flex-grow: 1;">{{ Str::limit($product->description, 80) }}</p>
                            @else
                                <div style="flex-grow: 1;"></div>
                            @endif
                            <div style="border-top: 0.5px solid #d4d0c8; padding-top: 1rem; display: flex; align-items: center; justify-content: space-between;">
                                <span style="font-family: 'Hanken Grotesk', sans-serif; font-weight: 600; font-size: 1rem; color: #735c00;">
                                    @if ($product->variants && $product->variants->isNotEmpty()) From {{ number_format($product->variants->min('price'), 2) }} @else {{ number_format($product->price, 2) }} @endif
                                </span>
                                @if ($product->variants && $product->variants->isNotEmpty())
                                    <span class="heritage-label" style="font-size: 0.5625rem; color: #888;">{{ $product->variants->count() }} options</span>
                                @else
                                    <a href="{{ route('business.product', [$business, $product]) }}" class="heritage-label" style="font-size: 0.6875rem; color: #735c00; text-decoration: none;">View &rarr;</a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div style="text-align: center; padding: 4rem 0; border: 0.5px solid #d4d0c8;">
                <span class="material-symbols-outlined" style="font-size: 48px; color: #d4d0c8; display: block; margin-bottom: 1rem;">inventory_2</span>
                <p style="font-family: 'Hanken Grotesk', sans-serif; font-size: 0.875rem; color: #999;">No products listed yet.</p>
            </div>
        @endif
    </div>
</section>
