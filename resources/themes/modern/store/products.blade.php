{{-- Modern theme · Store products — Lumina SaaS glass product cards --}}
<section id="products" style="padding: 0 0 128px;">
    <div style="max-width: 1280px; margin: 0 auto; padding: 0 40px;">
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 48px;">
            <div>
                <span class="label" style="color: #4648d4; margin-bottom: 8px; display: block;">Products</span>
                <h2 style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: clamp(1.75rem, 3vw, 2.5rem); letter-spacing: -0.03em; line-height: 1.1; color: #1a1a2e;">
                    Featured Collection
                </h2>
            </div>
            @if ($products && $products->isNotEmpty())
                <span style="font-size: 0.875rem; color: #9ca3af;">{{ $products->count() }} {{ Str::plural('item', $products->count()) }}</span>
            @endif
        </div>

        @if ($products && $products->isNotEmpty())
            @php
                $uniqueCategories = $products->pluck('category')->filter()->unique('id');
            @endphp

            @if ($uniqueCategories->isNotEmpty())
                <div style="display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 40px;" x-data="{ activeCat: 'all' }">
                    <button @click="activeCat = 'all'; $dispatch('filter-category', { categoryId: 'all' })"
                        :style="activeCat === 'all' ? 'background: #4648d4; color: #ffffff; border-color: #4648d4;' : 'background: rgba(255,255,255,0.7); color: #6b7280; border-color: rgba(0,0,0,0.08);'"
                        style="padding: 10px 24px; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.8125rem; font-weight: 600; border: 1px solid; border-radius: 999px; cursor: pointer; transition: all 0.2s; backdrop-filter: blur(8px);">
                        All
                    </button>
                    @foreach ($uniqueCategories as $cat)
                        <button @click="activeCat = '{{ $cat->id }}'; $dispatch('filter-category', { categoryId: '{{ $cat->id }}' })"
                            :style="activeCat === '{{ $cat->id }}' ? 'background: #4648d4; color: #ffffff; border-color: #4648d4;' : 'background: rgba(255,255,255,0.7); color: #6b7280; border-color: rgba(0,0,0,0.08);'"
                            style="padding: 10px 24px; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.8125rem; font-weight: 600; border: 1px solid; border-radius: 999px; cursor: pointer; transition: all 0.2s; backdrop-filter: blur(8px);">
                            {{ $cat->name }}
                        </button>
                    @endforeach
                </div>
            @endif

            @php $sortedProducts = $products->sortBy('sort_order'); @endphp
            <div x-data="{ currentCategory: 'all' }" @filter-category.window="currentCategory = $event.detail.categoryId"
                style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px;">
                @foreach ($sortedProducts as $product)
                    <div x-show="currentCategory === 'all' || currentCategory == '{{ $product->category_id ?? 0 }}'"
                        class="glass-card" style="display: flex; flex-direction: column; overflow: hidden;">
                        <a href="{{ route('business.product', [$business, $product]) }}" style="display: block; overflow: hidden;">
                            @if ($product->images && count($product->images) > 0)
                                <img src="{{ Storage::url($product->images[0]) }}" alt="{{ $product->name }}"
                                    style="width: 100%; aspect-ratio: 1; object-fit: cover; border-radius: 16px 16px 0 0; transition: transform 0.5s;"
                                    onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                            @else
                                <div style="width: 100%; aspect-ratio: 1; display: flex; align-items: center; justify-content: center; background: rgba(70,72,212,0.04);">
                                    <span class="material-symbols-outlined" style="color: #4648d4; font-size: 48px;">inventory_2</span>
                                </div>
                            @endif
                        </a>
                        <div style="padding: 24px; display: flex; flex-direction: column; flex-grow: 1;">
                            @if ($product->category)
                                <span style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: #006591; margin-bottom: 8px;">
                                    {{ $product->category->name }}
                                </span>
                            @endif
                            <h3 style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700; font-size: 1.125rem; color: #1a1a2e; margin-bottom: 8px; line-height: 1.3;">
                                <a href="{{ route('business.product', [$business, $product]) }}" style="color: inherit; text-decoration: none;" onmouseover="this.style.color='#4648d4'" onmouseout="this.style.color='#1a1a2e'">
                                    {{ $product->name }}
                                </a>
                            </h3>
                            @if ($product->description)
                                <p style="font-size: 0.875rem; color: #6b7280; line-height: 1.6; margin-bottom: 16px; flex-grow: 1;">
                                    {{ Str::limit($product->description, 100) }}
                                </p>
                            @else
                                <div style="flex-grow: 1;"></div>
                            @endif
                            <div style="display: flex; align-items: center; justify-content: space-between; padding-top: 16px; border-top: 1px solid rgba(0,0,0,0.06);">
                                <span style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700; font-size: 1.25rem; color: #1a1a2e;">
                                    @if ($product->variants && $product->variants->isNotEmpty())
                                        {{ \App\Support\Money\Money::format($product->variants->min('price'), $business->currency_code) }}+
                                    @else
                                        {{ \App\Support\Money\Money::format($product->price, $business->currency_code) }}
                                    @endif
                                </span>
                                <a href="{{ route('business.product', [$business, $product]) }}" class="btn-primary" style="padding: 10px 24px; font-size: 0.8125rem;">
                                    View <span class="material-symbols-outlined" style="font-size: 14px;">arrow_right_alt</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="glass" style="text-align: center; padding: 80px 0;">
                <span class="material-symbols-outlined" style="color: #4648d4; font-size: 48px; display: block; margin-bottom: 16px;">inventory_2</span>
                <p style="font-size: 1rem; color: #9ca3af;">No products listed yet.</p>
            </div>
        @endif
    </div>
</section>
