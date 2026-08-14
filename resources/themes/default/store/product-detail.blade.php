{{-- Default theme · Store product detail — Serene Ivory --}}
@section('title', $product->name . ' — ' . $business->name)

<style>
    body { background: #fcf9f2; color: #1c1c18; font-family: 'Plus Jakarta Sans', sans-serif; }
    h1, h2, h3, h4 { font-family: 'Playfair Display', serif; line-height: 1.15; }
    ::selection { background: #6c5e06; color: #fff; }
    :root { --c-primary: {{ $colors['primary'] ?? '#1c1c18' }}; --c-secondary: {{ $colors['secondary'] ?? '#5e5f5c' }}; --c-bg: {{ $colors['background'] ?? '#fcf9f2' }}; --c-text: {{ $colors['text'] ?? '#1c1c18' }}; --c-accent: {{ $colors['accent'] ?? '#6c5e06' }}; }
    .thin-frame { padding: 16px; border: 0.5px solid #E5E0D5; }
    .subtle-shadow { box-shadow: 0px 10px 40px rgba(0,0,0,0.03); }
    .section-label { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.6875rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
    ::-webkit-scrollbar { width: 6px; }
    ::-webkit-scrollbar-track { background: #f1eee7; }
    ::-webkit-scrollbar-thumb { background: #c5c7c1; border-radius: 3px; }
</style>

<div class="min-h-screen flex flex-col">
    @include('themes::default.store.header')

    <main class="flex-1" style="padding-top: 112px; padding-bottom: 96px;">
        <div style="max-width: 1280px; margin: 0 auto; padding: 0 64px;">
            {{-- Breadcrumb --}}
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 48px;">
                <a href="{{ route('business.site', $business) }}" style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #6c5e06; text-decoration: none;" onmouseover="this.style.color='#1c1c18'" onmouseout="this.style.color='#6c5e06'">
                    HOME
                </a>
                <span class="material-symbols-outlined" style="font-size: 14px; color: #c5c7c1;">chevron_right</span>
                @if($product->category)
                    <span style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: #757873;">{{ strtoupper($product->category->name) }}</span>
                    <span class="material-symbols-outlined" style="font-size: 14px; color: #c5c7c1;">chevron_right</span>
                @endif
                <span style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: #1c1c18;">{{ $product->name }}</span>
            </div>

            {{-- Product Grid: 12-col --}}
            <div style="display: grid; grid-template-columns: repeat(12, 1fr); gap: 32px;">
                {{-- Left: Product Gallery (7 cols) --}}
                <div style="grid-column: 1 / 8; display: flex; flex-direction: column; gap: 16px;">
                    {{-- Main Image --}}
                    <div class="thin-frame" style="border-radius: 0.5rem; overflow: hidden;">
                        @if ($product->images && count($product->images) > 0)
                            <img src="{{ Storage::url($product->images[0]) }}" alt="{{ $product->name }}"
                                 style="width: 100%; aspect-ratio: 4/5; object-fit: cover; display: block;">
                        @else
                            <div style="width: 100%; aspect-ratio: 4/5; background: #f1eee7; display: flex; align-items: center; justify-content: center;">
                                <span class="material-symbols-outlined" style="color: #c5c7c1; font-size: 64px;">inventory_2</span>
                            </div>
                        @endif
                    </div>

                    {{-- Thumbnail Images --}}
                    @if ($product->images && count($product->images) > 1)
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                            @foreach (array_slice($product->images->toArray(), 0, 2) as $img)
                                <div class="thin-frame" style="overflow: hidden; border-radius: 0.5rem; padding: 8px;">
                                    <img src="{{ Storage::url($img) }}" alt=""
                                         style="width: 100%; aspect-ratio: 1; object-fit: cover; display: block; border-radius: 0.25rem; cursor: pointer; transition: opacity 0.2s;"
                                         onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Right: Product Info (5 cols) --}}
                <div style="grid-column: 8 / 13; display: flex; flex-direction: column; gap: 24px;">
                    {{-- Header --}}
                    <div style="padding-bottom: 24px; border-bottom: 0.5px solid #E5E0D5;">
                        @if ($product->is_featured)
                            <span class="section-label" style="color: #6c5e06; margin-bottom: 12px; display: inline-block;">FEATURED</span>
                        @endif
                        <h1 style="font-family: 'Playfair Display', serif; font-style: italic; font-size: clamp(1.75rem, 3.5vw, 2.75rem); color: #1c1c18; margin-bottom: 16px;">
                            {{ $product->name }}
                        </h1>
                        @if ($product->description)
                            <p style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.9375rem; line-height: 1.7; color: #454843;">
                                {{ $product->description }}
                            </p>
                        @endif
                    </div>

                    {{-- Price --}}
                    <div style="padding-bottom: 24px; border-bottom: 0.5px solid #E5E0D5;">
                        <div style="display: flex; align-items: baseline; gap: 12px;">
                            @if ($product->variants && $product->variants->isNotEmpty())
                                <span style="font-family: 'Playfair Display', serif; font-size: 2rem; color: #1c1c18;">{{ \App\Support\Money\Money::format($product->variants->min('price'), $business->currency_code) }}+</span>
                            @else
                                <span style="font-family: 'Playfair Display', serif; font-size: 2rem; color: #1c1c18;">{{ \App\Support\Money\Money::format($product->price, $business->currency_code) }}</span>
                            @endif
                            @if ($product->compare_price && $product->compare_price > $product->price)
                                <span style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1rem; color: #757873; text-decoration: line-through;">{{ \App\Support\Money\Money::format($product->compare_price, $business->currency_code) }}</span>
                            @endif
                        </div>
                    </div>

                    {{-- Variants --}}
                    @if ($product->variants && $product->variants->isNotEmpty())
                        <div>
                            @foreach ($product->variants as $variant)
                                <div style="margin-bottom: 20px;">
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                        <span class="section-label" style="color: #1c1c18;">{{ $variant['name'] }}</span>
                                    </div>
                                    <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                                        @foreach ($variant['options'] ?? [] as $option)
                                            <div style="display: flex; align-items: center; gap: 8px; padding: 8px 14px; border: 0.5px solid #E5E0D5; font-size: 0.8125rem; color: #454843; transition: all 0.2s; cursor: pointer; border-radius: 0.25rem;" onmouseover="this.style.borderColor='#1c1c18'" onmouseout="this.style.borderColor='#E5E0D5'">
                                                @if (!empty($option['image']))
                                                    <img src="{{ Storage::url($option['image']) }}" alt="" style="width: 20px; height: 20px; object-fit: cover; border-radius: 0.25rem;">
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
                    <div style="display: flex; flex-wrap: wrap; gap: 12px;">
                        @if ($business->order_mode === 'checkout')
                            <form method="POST" action="{{ route('order.cart.add', ['business' => $business->slug, 'type' => 'product', 'id' => $product->id]) }}" style="flex: 1; display: flex;">
                                @csrf
                                <button type="submit"
                                    style="display: inline-flex; align-items: center; gap: 8px; padding: 16px 40px; background: #1c1c18; color: #fcf9f2; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.6875rem; font-weight: 600; letter-spacing: 0.05em; text-transform: uppercase; border: 0.5px solid #1c1c18; border-radius: 0.25rem; transition: all 0.2s; text-decoration: none; width: 100%; justify-content: center; cursor: pointer;"
                                    onmouseover="this.style.background='#454843'; this.style.borderColor='#454843';"
                                    onmouseout="this.style.background='#1c1c18'; this.style.borderColor='#1c1c18';">
                                    <span class="material-symbols-outlined" style="font-size: 16px;">shopping_bag</span> ADD TO CART
                                </button>
                            </form>
                        @endif
                        @if ($business->whatsapp)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $business->whatsapp) }}?text=Hi! I'm interested in "{{ $product->name }}" priced at {{ \App\Support\Money\Money::format($product->price, $business->currency_code) }}. Is it available?"
                               target="_blank"
                               style="display: inline-flex; align-items: center; gap: 8px; padding: 16px 40px; border: 0.5px solid #5e5f5c; color: #1c1c18; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.6875rem; font-weight: 600; letter-spacing: 0.05em; text-transform: uppercase; transition: all 0.2s; text-decoration: none; flex: 1; justify-content: center; border-radius: 0.25rem;"
                               onmouseover="this.style.background='#1c1c18'; this.style.color='#fcf9f2'; this.style.borderColor='#1c1c18';"
                               onmouseout="this.style.background='transparent'; this.style.color='#1c1c18'; this.style.borderColor='#5e5f5c';"
                               onclick="navigator.sendBeacon('{{ route('track.click', [$business->id, 'whatsapp_click']) }}?source=product&url='+encodeURIComponent(location.href));">
                                <span class="material-symbols-outlined" style="font-size: 16px;">chat</span> INQUIRE
                            </a>
                        @endif
                        @if ($business->phone)
                            <a href="tel:{{ $business->phone }}"
                               style="display: inline-flex; align-items: center; gap: 8px; padding: 16px 40px; border: 0.5px solid #5e5f5c; color: #1c1c18; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.6875rem; font-weight: 600; letter-spacing: 0.05em; text-transform: uppercase; transition: all 0.2s; text-decoration: none; flex: 1; justify-content: center; border-radius: 0.25rem;"
                               onmouseover="this.style.background='#1c1c18'; this.style.color='#fcf9f2'; this.style.borderColor='#1c1c18';"
                               onmouseout="this.style.background='transparent'; this.style.color='#1c1c18'; this.style.borderColor='#5e5f5c';">
                                <span class="material-symbols-outlined" style="font-size: 16px;">call</span> CALL NOW
                            </a>
                        @endif
                    </div>

                    {{-- Accordion Details --}}
                    <div style="border-top: 0.5px solid #E5E0D5; margin-top: 8px;">
                        <div style="border-bottom: 0.5px solid #E5E0D5; padding: 16px 0; display: flex; justify-content: space-between; align-items: center; cursor: pointer;">
                            <span class="section-label" style="color: #1c1c18;">Specifications</span>
                            <span class="material-symbols-outlined" style="color: #757873; font-size: 20px;">add</span>
                        </div>
                        <div style="border-bottom: 0.5px solid #E5E0D5; padding: 16px 0; display: flex; justify-content: space-between; align-items: center; cursor: pointer;">
                            <span class="section-label" style="color: #1c1c18;">Shipping & Returns</span>
                            <span class="material-symbols-outlined" style="color: #757873; font-size: 20px;">add</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @include('themes::default.store.footer')
</div>
