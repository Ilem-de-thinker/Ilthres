{{-- Modern theme · Store product detail — Lumina SaaS glass product detail --}}
@section('title', $product->name . ' — ' . $business->name)

@push('styles')
<style>
    body { background: linear-gradient(135deg, #f0f2ff 0%, #e8f4ff 50%, #fff 100%); color: #1a1a2e; font-family: 'Plus Jakarta Sans', sans-serif; }
    h1, h2, h3, h4 { font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; letter-spacing: -0.025em; }
    ::selection { background: #4648d4; color: #ffffff; }
    .glass { background: rgba(255,255,255,0.7); backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.2); border-radius: 16px; }
    .glass-card { background: rgba(255,255,255,0.7); backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.2); border-radius: 16px; transition: all 0.3s ease; }
    .glass-card:hover { transform: translateY(-4px); box-shadow: 0 24px 48px -12px rgba(70,72,212,0.15), 0 8px 24px -4px rgba(0,0,0,0.08); }
    .btn-primary { display: inline-flex; align-items: center; gap: 8px; padding: 16px 40px; background: linear-gradient(135deg, #4648d4, #006591); color: #ffffff; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 24px rgba(70,72,212,0.3); text-decoration: none; }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 32px rgba(70,72,212,0.4); }
    ::-webkit-scrollbar { width: 6px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: #4648d4; border-radius: 3px; }
</style>
@endpush

<div class="min-h-screen flex flex-col">
    @include('themes::modern.store.header')

    <main class="flex-1" style="padding-top: 96px; padding-bottom: 64px;">
        <div style="max-width: 1280px; margin: 0 auto; padding: 0 40px;">
            {{-- Breadcrumb --}}
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 48px;">
                <a href="{{ route('business.site', $business) }}" style="font-size: 0.875rem; color: #4648d4; text-decoration: none; font-weight: 500;" onmouseover="this.style.color='#006591'" onmouseout="this.style.color='#4648d4'">Home</a>
                <span class="material-symbols-outlined" style="font-size: 14px; color: #d1d5db;">chevron_right</span>
                @if($product->category)
                    <span style="font-size: 0.875rem; color: #6b7280;">{{ $product->category->name }}</span>
                    <span class="material-symbols-outlined" style="font-size: 14px; color: #d1d5db;">chevron_right</span>
                @endif
                <span style="font-size: 0.875rem; color: #1a1a2e; font-weight: 600;">{{ $product->name }}</span>
            </div>

            {{-- Product Grid --}}
            <div style="display: grid; grid-template-columns: repeat(12, 1fr); gap: 48px;">
                {{-- Left: Images (7 cols) --}}
                <div style="grid-column: 1 / 8;">
                    <div class="glass" style="overflow: hidden; margin-bottom: 24px;">
                        @if ($product->images && count($product->images) > 0)
                            <img src="{{ Storage::url($product->images[0]) }}" alt="{{ $product->name }}"
                                style="width: 100%; aspect-ratio: 4/5; object-fit: cover; border-radius: 16px;">
                        @else
                            <div style="width: 100%; aspect-ratio: 4/5; display: flex; align-items: center; justify-content: center; background: rgba(70,72,212,0.04);">
                                <span class="material-symbols-outlined" style="color: #4648d4; font-size: 64px;">inventory_2</span>
                            </div>
                        @endif
                    </div>

                    @if ($product->images && count($product->images) > 1)
                        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px;">
                            @foreach (array_slice($product->images->toArray(), 0, 3) as $img)
                                <div class="glass" style="overflow: hidden; cursor: pointer;">
                                    <img src="{{ Storage::url($img) }}" alt=""
                                        style="width: 100%; aspect-ratio: 1; object-fit: cover; border-radius: 16px; transition: opacity 0.2s;"
                                        onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Right: Product Info (5 cols) --}}
                <div style="grid-column: 8 / 13;">
                    <div class="glass-card" style="padding: 40px;">
                        {{-- Header --}}
                        <div style="margin-bottom: 32px; padding-bottom: 24px; border-bottom: 1px solid rgba(0,0,0,0.06);">
                            @if($product->category)
                                <span style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: #006591; margin-bottom: 8px; display: block;">{{ $product->category->name }}</span>
                            @endif
                            <h1 style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: clamp(1.5rem, 3vw, 2.25rem); letter-spacing: -0.03em; color: #1a1a2e; margin-bottom: 16px;">
                                {{ $product->name }}
                            </h1>

                            {{-- Price --}}
                            <div style="display: flex; align-items: baseline; gap: 12px;">
                                @if ($product->variants && $product->variants->isNotEmpty())
                                    <span style="font-weight: 700; font-size: 2rem; color: #1a1a2e;">{{ \App\Support\Money\Money::format($product->variants->min('price'), $business->currency_code) }}+</span>
                                @else
                                    <span style="font-weight: 700; font-size: 2rem; color: #1a1a2e;">{{ \App\Support\Money\Money::format($product->price, $business->currency_code) }}</span>
                                @endif
                                @if ($product->compare_price && $product->compare_price > $product->price)
                                    <span style="font-size: 1.125rem; color: #b90538; text-decoration: line-through; font-weight: 500;">{{ \App\Support\Money\Money::format($product->compare_price, $business->currency_code) }}</span>
                                @endif
                            </div>
                        </div>

                        {{-- Description --}}
                        @if ($product->description)
                            <p style="font-size: 0.9375rem; line-height: 1.7; color: #4b5563; margin-bottom: 32px;">
                                {{ $product->description }}
                            </p>
                        @endif

                        {{-- Variants --}}
                        @if ($product->variants && $product->variants->isNotEmpty())
                            <div style="margin-bottom: 32px;">
                                @foreach ($product->variants as $variant)
                                    <div style="margin-bottom: 20px;">
                                        <span style="display: block; font-size: 0.8125rem; font-weight: 600; color: #1a1a2e; margin-bottom: 10px;">{{ $variant['name'] }}</span>
                                        <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                                            @foreach ($variant['options'] ?? [] as $option)
                                                <div style="display: flex; align-items: center; gap: 8px; padding: 10px 16px; background: rgba(255,255,255,0.7); border: 1px solid rgba(0,0,0,0.08); border-radius: 999px; font-size: 0.875rem; color: #1a1a2e; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.borderColor='#4648d4'" onmouseout="this.style.borderColor='rgba(0,0,0,0.08)'">
                                                    @if (!empty($option['image']))
                                                        <img src="{{ Storage::url($option['image']) }}" alt="" style="width: 20px; height: 20px; object-fit: cover; border-radius: 50%;">
                                                    @endif
                                                    <span>{{ $option['value'] }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        {{-- CTAs --}}
                        <div style="display: flex; flex-direction: column; gap: 12px;">
                            @if ($business->order_mode === 'checkout')
                                <form method="POST" action="{{ route('order.cart.add', ['business' => $business->slug, 'type' => 'product', 'id' => $product->id]) }}">
                                    @csrf
                                    <button type="submit" class="btn-primary" style="justify-content: center; width: 100%; cursor: pointer;">
                                        <span class="material-symbols-outlined" style="font-size: 18px;">shopping_bag</span> Add to Cart
                                    </button>
                                </form>
                            @endif
                            @if ($business->whatsapp)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $business->whatsapp) }}?text=Hi! I'm interested in "{{ $product->name }}" priced at {{ \App\Support\Money\Money::format($product->price, $business->currency_code) }}. Is it available?"
                                   target="_blank" class="{{ $business->order_mode === 'checkout' ? 'btn-secondary' : 'btn-primary' }}" style="justify-content: center; width: 100%;" onclick="navigator.sendBeacon('{{ route('track.click', [$business->id, 'whatsapp_click']) }}?source=product&url='+encodeURIComponent(location.href));">
                                    <span class="material-symbols-outlined" style="font-size: 18px;">{{ $business->order_mode === 'checkout' ? 'chat' : 'shopping_bag' }}</span> {{ $business->order_mode === 'checkout' ? 'WhatsApp Inquiry' : 'Add to Bag' }}
                                </a>
                            @endif
                            @if ($business->phone)
                                <a href="tel:{{ $business->phone }}" class="btn-secondary" style="justify-content: center; width: 100%;">
                                    <span class="material-symbols-outlined" style="font-size: 18px;">call</span> Call Now
                                </a>
                            @endif
                        </div>

                        {{-- Accordion Details --}}
                        <div style="margin-top: 40px; padding-top: 24px; border-top: 1px solid rgba(0,0,0,0.06);">
                            <div style="border-bottom: 1px solid rgba(0,0,0,0.06); padding: 16px 0; display: flex; justify-content: space-between; align-items: center; cursor: pointer;">
                                <span style="font-weight: 600; font-size: 0.875rem; color: #1a1a2e;">Specifications</span>
                                <span class="material-symbols-outlined" style="color: #9ca3af; font-size: 18px;">add</span>
                            </div>
                            <div style="padding: 16px 0; display: flex; justify-content: space-between; align-items: center; cursor: pointer;">
                                <span style="font-weight: 600; font-size: 0.875rem; color: #1a1a2e;">Shipping & Returns</span>
                                <span class="material-symbols-outlined" style="color: #9ca3af; font-size: 18px;">add</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Related Products --}}
            @if ($products && $products->where('id', '!=', $product->id)->isNotEmpty())
                @php $related = $products->where('id', '!=', $product->id)->take(3); @endphp
                <section style="margin-top: 80px;">
                    <div style="margin-bottom: 32px;">
                        <span class="label" style="color: #4648d4; margin-bottom: 8px; display: block;">Related</span>
                        <h2 style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 1.75rem; letter-spacing: -0.03em; color: #1a1a2e;">You Might Also Like</h2>
                    </div>
                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px;">
                        @foreach ($related as $rel)
                            <a href="{{ route('business.product', [$business, $rel]) }}" class="glass-card" style="display: flex; flex-direction: column; overflow: hidden; text-decoration: none;">
                                @if ($rel->images && count($rel->images) > 0)
                                    <div style="overflow: hidden;">
                                        <img src="{{ Storage::url($rel->images[0]) }}" alt="{{ $rel->name }}"
                                            style="width: 100%; aspect-ratio: 1; object-fit: cover; border-radius: 16px 16px 0 0; transition: transform 0.5s;"
                                            onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                                    </div>
                                @endif
                                <div style="padding: 20px;">
                                    <h3 style="font-weight: 700; font-size: 1rem; color: #1a1a2e; margin-bottom: 4px;">{{ $rel->name }}</h3>
                                    <span style="font-weight: 700; font-size: 1.125rem; color: #4648d4;">
                                        @if ($rel->variants && $rel->variants->isNotEmpty())
                                            {{ \App\Support\Money\Money::format($rel->variants->min('price'), $business->currency_code) }}+
                                        @else
                                            {{ \App\Support\Money\Money::format($rel->price, $business->currency_code) }}
                                        @endif
                                    </span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>
    </main>

    @include('themes::modern.store.footer')
</div>
