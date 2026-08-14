{{-- Bold theme · Store product detail — High-Impact Editorial: 12-col grid --}}
@section('title', $product->name . ' — ' . $business->name)

<div class="min-h-screen flex flex-col">
    @include('themes::bold.store.header')

    <main class="flex-1" style="padding-top: 96px; padding-bottom: 64px;">
        <div style="max-width: 1280px; margin: 0 auto; padding: 0 64px;">
            {{-- Breadcrumb --}}
            <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 48px; padding-bottom: 16px; border-bottom: 2px solid #444748;">
                <a href="{{ route('business.site', $business) }}" style="font-family: 'Space Mono', monospace; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.15em; color: #72ff70; text-decoration: none;" onmouseover="this.style.color='#ffffff'" onmouseout="this.style.color='#72ff70'">
                    INDEX
                </a>
                <span class="material-symbols-outlined" style="font-size: 14px; color: #444748;">arrow_forward_ios</span>
                @if($product->category)
                    <span style="font-family: 'Space Mono', monospace; font-size: 10px; text-transform: uppercase; letter-spacing: 0.15em; color: #e2e2e2;">{{ strtoupper($product->category->name) }}</span>
                    <span class="material-symbols-outlined" style="font-size: 14px; color: #444748;">arrow_forward_ios</span>
                @endif
                <span style="font-family: 'Space Mono', monospace; font-size: 10px; text-transform: uppercase; letter-spacing: 0.15em; color: #ffffff;">{{ strtoupper($product->name) }}</span>
            </div>

            {{-- Product Grid: 12-col --}}
            <div style="display: grid; grid-template-columns: repeat(12, 1fr); gap: 24px;">
                {{-- Left: Product Gallery (7 cols) --}}
                <div style="grid-column: 1 / 8; display: flex; flex-direction: column; gap: 24px;">
                    {{-- Main Image --}}
                    <div style="border: 4px solid #ffffff; position: relative; overflow: hidden;">
                        @if ($product->images && count($product->images) > 0)
                            <img src="{{ Storage::url($product->images[0]) }}" alt="{{ $product->name }}"
                                 style="width: 100%; aspect-ratio: 4/5; object-fit: cover; filter: contrast(1.1) grayscale(0.2) brightness(0.9);">
                        @else
                            <div class="dot-pattern" style="width: 100%; aspect-ratio: 4/5; display: flex; align-items: center; justify-content: center;">
                                <span class="material-symbols-outlined" style="color: #444748; font-size: 64px;">inventory_2</span>
                            </div>
                        @endif
                        <div style="position: absolute; bottom: 24px; left: 24px; background: #131313; border: 2px solid #ffffff; padding: 8px 16px;">
                            <span style="font-family: 'Space Mono', monospace; font-size: 10px; letter-spacing: 0.15em; text-transform: uppercase; color: #e2e2e2;">LOOK_01 // DETAIL_VIEW</span>
                        </div>
                    </div>

                    {{-- Thumbnail Images --}}
                    @if ($product->images && count($product->images) > 1)
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                            @foreach (array_slice($product->images->toArray(), 0, 2) as $img)
                                <div style="border: 2px solid #444748; overflow: hidden; background: #353535;">
                                    <img src="{{ Storage::url($img) }}" alt=""
                                         style="width: 100%; aspect-ratio: 1; object-fit: cover; filter: contrast(1.1) grayscale(0.2) brightness(0.9); opacity: 0.8; transition: opacity 0.15s; cursor: pointer;"
                                         onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.8'">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Right: Product Info (5 cols) --}}
                <div style="grid-column: 8 / 13; display: flex; flex-direction: column; gap: 32px;">
                    {{-- Header --}}
                    <div style="border-bottom: 4px solid #ffffff; padding-bottom: 32px;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
                            <span style="font-family: 'Space Mono', monospace; font-size: 10px; letter-spacing: 0.15em; text-transform: uppercase; color: #72ff70;">ST_ITEM_{{ str_pad($product->id, 4, '0', STR_PAD_LEFT) }}</span>
                            @if ($product->is_featured)
                                <span style="border: 2px solid #72ff70; padding: 4px 8px; font-family: 'Space Mono', monospace; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.15em; color: #72ff70;">Featured</span>
                            @endif
                        </div>
                        <h1 style="font-family: 'Anton', sans-serif; font-size: clamp(2rem, 4vw, 3.5rem); line-height: 0.95; margin-bottom: 24px; text-transform: uppercase; color: #ffffff;">
                            {{ $product->name }}
                        </h1>
                        @if ($product->description)
                            <p style="font-family: 'Archivo Narrow', sans-serif; font-size: 1rem; line-height: 1.7; color: #e2e2e2; opacity: 0.8; max-width: 480px;">
                                {{ $product->description }}
                            </p>
                        @endif
                    </div>

                    {{-- Price --}}
                    <div style="border-bottom: 2px solid #444748; padding-bottom: 24px;">
                        <div style="display: flex; align-items: baseline; gap: 12px;">
                            @if ($product->variants && $product->variants->isNotEmpty())
                                <span style="font-family: 'Anton', sans-serif; font-size: 2.5rem; color: #ffffff;">${{ number_format($product->variants->min('price'), 2) }}+</span>
                            @else
                                <span style="font-family: 'Anton', sans-serif; font-size: 2.5rem; color: #ffffff;">${{ number_format($product->price, 2) }}</span>
                            @endif
                            @if ($product->compare_price && $product->compare_price > $product->price)
                                <span style="font-family: 'Space Mono', monospace; font-size: 1rem; color: #72ff70; text-decoration: line-through; font-weight: 700;">${{ number_format($product->compare_price, 2) }}</span>
                            @endif
                        </div>
                    </div>

                    {{-- Variants --}}
                    @if ($product->variants && $product->variants->isNotEmpty())
                        <div>
                            @foreach ($product->variants as $variant)
                                <div style="margin-bottom: 24px;">
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
                                        <span style="font-family: 'Space Mono', monospace; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.15em; color: #ffffff;">{{ $variant['name'] }}</span>
                                    </div>
                                    <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                                        @foreach ($variant['options'] ?? [] as $option)
                                            <div style="display: flex; align-items: center; gap: 8px; padding: 8px 12px; border: 2px solid #444748; font-size: 0.875rem; color: #e2e2e2; transition: border-color 0.15s; cursor: pointer;" onmouseover="this.style.borderColor='#ffffff'" onmouseout="this.style.borderColor='#444748'">
                                                @if (!empty($option['image']))
                                                    <img src="{{ Storage::url($option['image']) }}" alt="" style="width: 24px; height: 24px; object-fit: cover; border: 1px solid #444748;">
                                                @endif
                                                <span>{{ $option['value'] }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- CTA Buttons --}}
                    <div style="display: flex; flex-wrap: wrap; gap: 16px;">
                        @if ($business->order_mode === 'checkout')
                            <form method="POST" action="{{ route('order.cart.add', ['business' => $business->slug, 'type' => 'product', 'id' => $product->id]) }}" style="flex: 1; display: flex;">
                                @csrf
                                <button type="submit"
                                    style="display: inline-flex; align-items: center; gap: 8px; padding: 16px 40px; background: #ffffff; color: #131313; font-family: 'Space Mono', monospace; font-size: 14px; font-weight: 700; letter-spacing: 0.2em; text-transform: uppercase; border: 4px solid #ffffff; transition: all 0.15s; text-decoration: none; width: 100%; justify-content: center; cursor: pointer;"
                                    onmouseover="this.style.background='#72ff70'; this.style.borderColor='#72ff70';"
                                    onmouseout="this.style.background='#ffffff'; this.style.borderColor='#ffffff';">
                                    <span class="material-symbols-outlined" style="font-size: 16px;">shopping_bag</span> ADD TO CART
                                </button>
                            </form>
                        @endif
                        @if ($business->whatsapp)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $business->whatsapp) }}?text=Hi! I'm interested in "{{ $product->name }}" priced at {{ \App\Support\Money\Money::format($product->price, $business->currency_code) }}. Is it available?"
                               target="_blank"
                               style="display: inline-flex; align-items: center; gap: 8px; padding: 16px 40px; background: {{ $business->order_mode === 'checkout' ? 'transparent' : '#ffffff' }}; color: {{ $business->order_mode === 'checkout' ? '#ffffff' : '#131313' }}; font-family: 'Space Mono', monospace; font-size: 14px; font-weight: 700; letter-spacing: 0.2em; text-transform: uppercase; border: 4px solid #ffffff; transition: all 0.15s; text-decoration: none; flex: 1; justify-content: center;"
                               onmouseover="this.style.background='#72ff70'; this.style.borderColor='#72ff70'; this.style.color='#002203';"
                               onmouseout="this.style.background='{{ $business->order_mode === 'checkout' ? 'transparent' : '#ffffff' }}'; this.style.borderColor='#ffffff'; this.style.color='{{ $business->order_mode === 'checkout' ? '#ffffff' : '#131313' }}';"
                               onclick="navigator.sendBeacon('{{ route('track.click', [$business->id, 'whatsapp_click']) }}?source=product&url='+encodeURIComponent(location.href));">
                                <span class="material-symbols-outlined" style="font-size: 16px;">chat</span> INQUIRE
                            </a>
                        @endif
                        @if ($business->phone)
                            <a href="tel:{{ $business->phone }}"
                               style="display: inline-flex; align-items: center; gap: 8px; padding: 16px 40px; border: 4px solid #ffffff; color: #ffffff; font-family: 'Space Mono', monospace; font-size: 14px; font-weight: 700; letter-spacing: 0.2em; text-transform: uppercase; transition: all 0.15s; text-decoration: none; flex: 1; justify-content: center;"
                               onmouseover="this.style.background='rgba(255,255,255,0.1)';"
                               onmouseout="this.style.background='transparent';">
                                <span class="material-symbols-outlined" style="font-size: 16px;">call</span> CALL_NOW
                            </a>
                        @endif
                    </div>

                    {{-- Accordion Details --}}
                    <div style="border-top: 2px solid #444748; margin-top: 8px;">
                        <div style="border-bottom: 2px solid #444748; padding: 16px 0; display: flex; justify-content: space-between; align-items: center; cursor: pointer;">
                            <span style="font-family: 'Space Mono', monospace; font-size: 14px; font-weight: 700; letter-spacing: 0.2em; text-transform: uppercase; color: #ffffff;">Specifications</span>
                            <span class="material-symbols-outlined" style="color: #e2e2e2;">add</span>
                        </div>
                        <div style="border-bottom: 2px solid #444748; padding: 16px 0; display: flex; justify-content: space-between; align-items: center; cursor: pointer;">
                            <span style="font-family: 'Space Mono', monospace; font-size: 14px; font-weight: 700; letter-spacing: 0.2em; text-transform: uppercase; color: #ffffff;">Shipping & Returns</span>
                            <span class="material-symbols-outlined" style="color: #e2e2e2;">add</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @include('themes::bold.store.footer')
</div>
