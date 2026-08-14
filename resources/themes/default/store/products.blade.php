{{-- Default theme · Store products — Serene Ivory: editorial grid --}}
<section id="products" style="padding: 96px 0; background: #fcf9f2;">
    <div style="max-width: 1280px; margin: 0 auto; padding: 0 64px;">
        {{-- Section Header --}}
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 48px;">
            <div>
                <span class="section-label" style="color: #6c5e06; display: block; margin-bottom: 12px;">COLLECTION</span>
                <h2 style="font-family: 'Playfair Display', serif; font-style: italic; font-size: clamp(1.75rem, 3.5vw, 2.75rem); color: #1c1c18;">Our Products</h2>
            </div>
            @if ($products && $products->isNotEmpty())
                <a href="#" style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #454843; text-decoration: none; transition: color 0.2s; display: inline-flex; align-items: center; gap: 6px;" onmouseover="this.style.color='#6c5e06'" onmouseout="this.style.color='#454843'">
                    View All <span class="material-symbols-outlined" style="font-size: 16px;">arrow_forward</span>
                </a>
            @endif
        </div>

        @if ($products && $products->isNotEmpty())
            @php
                $uniqueCategories = $products->pluck('category')->filter()->unique('id');
            @endphp

            {{-- Category Filter --}}
            @if ($uniqueCategories->isNotEmpty())
                <div style="display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 48px;" x-data="{ activeCat: 'all' }">
                    <button @click="activeCat = 'all'; $dispatch('filter-category', { categoryId: 'all' })"
                            :style="activeCat === 'all' ? 'background: #1c1c18; color: #fcf9f2; border-color: #1c1c18;' : 'background: transparent; color: #454843; border-color: #E5E0D5;'"
                        style="padding: 8px 20px; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.6875rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; border: 0.5px solid; cursor: pointer; transition: all 0.2s; border-radius: 0.25rem;">
                        ALL
                    </button>
                    @foreach ($uniqueCategories as $cat)
                        <button @click="activeCat = '{{ $cat->id }}'; $dispatch('filter-category', { categoryId: '{{ $cat->id }}' })"
                                :style="activeCat === '{{ $cat->id }}' ? 'background: #1c1c18; color: #fcf9f2; border-color: #1c1c18;' : 'background: transparent; color: #454843; border-color: #E5E0D5;'"
                            style="padding: 8px 20px; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.6875rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; border: 0.5px solid; cursor: pointer; transition: all 0.2s; border-radius: 0.25rem;">
                            {{ strtoupper($cat->name) }}
                        </button>
                    @endforeach
                </div>
            @endif

            {{-- Products Grid --}}
            @php $sortedProducts = $products->sortBy('sort_order'); @endphp
            <div x-data="{ currentCategory: 'all' }" @filter-category.window="currentCategory = $event.detail.categoryId">
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 32px;">
                    @foreach ($sortedProducts as $product)
                        <div x-show="currentCategory === 'all' || currentCategory == '{{ $product->category_id ?? 0 }}'"
                             class="subtle-shadow" style="background: #fcf9f2; border-radius: 0.5rem; overflow: hidden; display: flex; flex-direction: column; transition: transform 0.2s;"
                             onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='none'">

                            {{-- Product Image --}}
                            <div class="thin-frame" style="border-radius: 0; border-bottom: none; border-left: 0.5px solid #E5E0D5; border-right: 0.5px solid #E5E0D5; border-top: 0.5px solid #E5E0D5; overflow: hidden;">
                                @if ($product->images && count($product->images) > 0)
                                    <a href="{{ route('business.product', [$business, $product]) }}" style="display: block; overflow: hidden;">
                                        <img src="{{ Storage::url($product->images[0]) }}" alt="{{ $product->name }}"
                                             style="width: 100%; aspect-ratio: 4/5; object-fit: cover; transition: transform 0.5s;"
                                             onmouseover="this.style.transform='scale(1.03)'" onmouseout="this.style.transform='scale(1)'">
                                    </a>
                                @else
                                    <div style="width: 100%; aspect-ratio: 4/5; background: #f1eee7; display: flex; align-items: center; justify-content: center;">
                                        <span class="material-symbols-outlined" style="color: #c5c7c1; font-size: 48px;">inventory_2</span>
                                    </div>
                                @endif
                            </div>

                            {{-- Product Content --}}
                            <div style="padding: 20px; display: flex; flex-direction: column; flex-grow: 1;">
                                <span class="section-label" style="color: #757873; margin-bottom: 8px; display: block;">
                                    {{ strtoupper($product->category?->name ?? 'GENERAL') }}
                                </span>
                                <h3 style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.9375rem; font-weight: 600; margin-bottom: 12px; color: #1c1c18;">
                                    <a href="{{ route('business.product', [$business, $product]) }}" style="color: inherit; text-decoration: none;" onmouseover="this.style.color='#6c5e06'" onmouseout="this.style.color='#1c1c18'">
                                        {{ $product->name }}
                                    </a>
                                </h3>
                                <div style="flex-grow: 1;"></div>
                                <div style="border-top: 0.5px solid #E5E0D5; padding-top: 12px; display: flex; align-items: center; justify-content: space-between;">
                                    <span style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1rem; font-weight: 600; color: #1c1c18;">
                                        @if ($product->variants && $product->variants->isNotEmpty())
                                            {{ \App\Support\Money\Money::format($product->variants->min('price'), $business->currency_code) }}+
                                        @else
                                            {{ \App\Support\Money\Money::format($product->price, $business->currency_code) }}
                                        @endif
                                    </span>
                                    <a href="{{ route('business.product', [$business, $product]) }}"
                                       style="display: inline-flex; align-items: center; padding: 8px 16px; border: 0.5px solid #E5E0D5; color: #454843; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.6875rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; transition: all 0.2s; text-decoration: none; border-radius: 0.25rem;"
                                       onmouseover="this.style.background='#1c1c18'; this.style.color='#fcf9f2'; this.style.borderColor='#1c1c18';"
                                       onmouseout="this.style.background='transparent'; this.style.color='#454843'; this.style.borderColor='#E5E0D5';">
                                        VIEW
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div style="text-align: center; padding: 64px 0;">
                <span class="material-symbols-outlined" style="color: #c5c7c1; font-size: 48px; display: block; margin-bottom: 16px;">inventory_2</span>
                <p style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.875rem; color: #757873;">No products listed yet.</p>
            </div>
        @endif
    </div>
</section>
