{{-- Bold theme · Store products — High-Impact Editorial bento grid --}}
<section id="products" style="background: #131313; padding: 64px 0;">
    <div style="max-width: 1280px; margin: 0 auto; padding: 0 64px;">
        {{-- Section Header --}}
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 48px; padding-bottom: 16px; border-bottom: 4px solid #ffffff;">
            <h2 style="font-family: 'Anton', sans-serif; font-size: 2.5rem; color: #ffffff; text-transform: uppercase;">CURRENT_INVENTORY</h2>
            <span style="font-family: 'Space Mono', monospace; font-size: 10px; text-transform: uppercase; letter-spacing: 0.15em; color: #e2e2e2; opacity: 0.6; margin-bottom: 8px;">FILTER: ALL_UNITS</span>
        </div>

        @if ($products && $products->isNotEmpty())
            @php
                $uniqueCategories = $products->pluck('category')->filter()->unique('id');
            @endphp

            {{-- Category Filter --}}
            @if ($uniqueCategories->isNotEmpty())
                <div style="display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 48px;" x-data="{ activeCat: 'all' }">
                    <button @click="activeCat = 'all'; $dispatch('filter-category', { categoryId: 'all' })"
                            :style="activeCat === 'all' ? 'background: #ffffff; color: #131313; border-color: #ffffff;' : 'background: transparent; color: #e2e2e2; border-color: #444748;'"
                        style="padding: 10px 20px; font-family: 'Space Mono', monospace; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.15em; border: 2px solid; cursor: pointer; transition: all 0.15s;">
                        ALL
                    </button>
                    @foreach ($uniqueCategories as $cat)
                        <button @click="activeCat = '{{ $cat->id }}'; $dispatch('filter-category', { categoryId: '{{ $cat->id }}' })"
                                :style="activeCat === '{{ $cat->id }}' ? 'background: #ffffff; color: #131313; border-color: #ffffff;' : 'background: transparent; color: #e2e2e2; border-color: #444748;'"
                            style="padding: 10px 20px; font-family: 'Space Mono', monospace; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.15em; border: 2px solid; cursor: pointer; transition: all 0.15s;">
                            {{ strtoupper($cat->name) }}
                        </button>
                    @endforeach
                </div>
            @endif

            {{-- Products Bento Grid --}}
            @php $sortedProducts = $products->sortBy('sort_order'); @endphp
            <div x-data="{ currentCategory: 'all' }" @filter-category.window="currentCategory = $event.detail.categoryId">
                {{-- First product: spans 2 columns --}}
                @if ($sortedProducts->isNotEmpty())
                    @php $first = $sortedProducts->first(); @endphp
                    <div x-show="currentCategory === 'all' || currentCategory == '{{ $first->category_id ?? 0 }}'"
                         style="border: 4px solid #ffffff; background: #1f1f1f; margin-bottom: 24px; display: grid; grid-template-columns: 1fr 1fr;">
                        {{-- Image --}}
                        <div style="overflow: hidden; border-right: 4px solid #ffffff; position: relative;">
                            @if ($first->images && count($first->images) > 0)
                                <a href="{{ route('business.product', [$business, $first]) }}" style="display: block;">
                                    <img src="{{ Storage::url($first->images[0]) }}" alt="{{ $first->name }}"
                                         style="width: 100%; aspect-ratio: 4/3; object-fit: cover; transition: transform 0.5s;"
                                         onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                                </a>
                            @else
                                <div class="dot-pattern" style="width: 100%; aspect-ratio: 4/3; display: flex; align-items: center; justify-content: center;">
                                    <span class="material-symbols-outlined" style="color: #444748; font-size: 48px;">inventory_2</span>
                                </div>
                            @endif
                            @if ($first->is_featured)
                                <div style="position: absolute; top: 16px; left: 16px; background: #72ff70; color: #002203; font-family: 'Space Mono', monospace; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; padding: 6px 12px;">
                                    FEATURED
                                </div>
                            @endif
                        </div>
                        {{-- Content --}}
                        <div style="padding: 24px; display: flex; flex-direction: column; justify-content: flex-end;">
                            <span style="font-family: 'Space Mono', monospace; font-size: 10px; color: #e2e2e2; opacity: 0.5; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.15em;">
                                01 / {{ strtoupper($first->category?->name ?? 'GENERAL') }}
                            </span>
                            <h3 style="font-family: 'Anton', sans-serif; font-size: 1.5rem; margin-bottom: 8px; text-transform: uppercase; color: #ffffff; line-height: 1.1;">
                                <a href="{{ route('business.product', [$business, $first]) }}" style="color: inherit; text-decoration: none;" onmouseover="this.style.color='#72ff70'" onmouseout="this.style.color='#ffffff'">
                                    {{ strtoupper($first->name) }}
                                </a>
                            </h3>
                            @if ($first->description)
                                <p style="font-family: 'Archivo Narrow', sans-serif; font-size: 0.875rem; color: #e2e2e2; opacity: 0.7; margin-bottom: 24px; line-height: 1.6;">
                                    {{ Str::limit($first->description, 120) }}
                                </p>
                            @endif
                            <div style="border-top: 2px solid #444748; padding-top: 16px; display: flex; align-items: center; justify-content: space-between;">
                                <span style="font-family: 'Anton', sans-serif; font-size: 1.375rem; color: #ffffff;">
                                    @if ($first->variants && $first->variants->isNotEmpty())
                                        ${{ number_format($first->variants->min('price'), 2) }}+
                                    @else
                                        ${{ number_format($first->price, 2) }}
                                    @endif
                                </span>
                                <a href="{{ route('business.product', [$business, $first]) }}"
                                   style="display: inline-flex; align-items: center; padding: 10px 20px; border: 2px solid #ffffff; color: #ffffff; font-family: 'Space Mono', monospace; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.15em; transition: all 0.15s; text-decoration: none;"
                                   onmouseover="this.style.background='#ffffff'; this.style.color='#131313';"
                                   onmouseout="this.style.background='transparent'; this.style.color='#ffffff';">
                                    ADD_TO_BAG
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Remaining products: 3-column grid --}}
                @if ($sortedProducts->count() > 1)
                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px;">
                        @foreach ($sortedProducts->slice(1) as $i => $product)
                            <div x-show="currentCategory === 'all' || currentCategory == '{{ $product->category_id ?? 0 }}'"
                                 style="border: 4px solid #ffffff; background: #1f1f1f; display: flex; flex-direction: column;">

                                {{-- Product Image --}}
                                @if ($product->images && count($product->images) > 0)
                                    <a href="{{ route('business.product', [$business, $product]) }}" style="display: block; overflow: hidden; border-bottom: 4px solid #ffffff;">
                                        <img src="{{ Storage::url($product->images[0]) }}" alt="{{ $product->name }}"
                                             style="width: 100%; aspect-ratio: 1; object-fit: cover; transition: transform 0.5s;"
                                             onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                                    </a>
                                @else
                                    <div class="dot-pattern" style="width: 100%; aspect-ratio: 1; display: flex; align-items: center; justify-content: center; border-bottom: 4px solid #ffffff;">
                                        <span class="material-symbols-outlined" style="color: #444748; font-size: 48px;">inventory_2</span>
                                    </div>
                                @endif

                                {{-- Product Content --}}
                                <div style="padding: 24px; display: flex; flex-direction: column; flex-grow: 1;">
                                    <span style="font-family: 'Space Mono', monospace; font-size: 10px; color: #e2e2e2; opacity: 0.5; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.15em;">
                                        {{ str_pad($i + 2, 2, '0', STR_PAD_LEFT) }} / {{ strtoupper($product->category?->name ?? 'GENERAL') }}
                                    </span>
                                    <h4 style="font-family: 'Anton', sans-serif; font-size: 1.25rem; margin-bottom: 8px; text-transform: uppercase; color: #ffffff; line-height: 1.1;">
                                        <a href="{{ route('business.product', [$business, $product]) }}" style="color: inherit; text-decoration: none;" onmouseover="this.style.color='#72ff70'" onmouseout="this.style.color='#ffffff'">
                                            {{ strtoupper($product->name) }}
                                        </a>
                                    </h4>

                                    <div style="flex-grow: 1;"></div>

                                    <div style="border-top: 2px solid #444748; padding-top: 16px; display: flex; align-items: center; justify-content: space-between;">
                                        <span style="font-family: 'Anton', sans-serif; font-size: 1.375rem; color: #ffffff;">
                                            @if ($product->variants && $product->variants->isNotEmpty())
                                                ${{ number_format($product->variants->min('price'), 2) }}+
                                            @else
                                                ${{ number_format($product->price, 2) }}
                                            @endif
                                        </span>
                                        <a href="{{ route('business.product', [$business, $product]) }}"
                                           style="display: inline-flex; align-items: center; padding: 10px 20px; border: 2px solid #ffffff; color: #ffffff; font-family: 'Space Mono', monospace; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.15em; transition: all 0.15s; text-decoration: none;"
                                           onmouseover="this.style.background='#ffffff'; this.style.color='#131313';"
                                           onmouseout="this.style.background='transparent'; this.style.color='#ffffff';">
                                            VIEW
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @else
            <div class="dot-pattern" style="text-align: center; padding: 64px 0; color: #e2e2e2; font-family: 'Space Mono', monospace; font-weight: 700; text-transform: uppercase; letter-spacing: 0.15em;">
                <span class="material-symbols-outlined" style="color: #444748; font-size: 48px; display: block; margin-bottom: 16px;">inventory_2</span>
                <p>NO_PRODUCTS_LISTED</p>
            </div>
        @endif
    </div>
</section>
