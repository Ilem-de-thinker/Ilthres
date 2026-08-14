{{-- Heritage theme · Store product detail page --}}
@section('title', $product->name . ' — ' . $business->name)

<style>
    .pd-split { display: grid; grid-template-columns: 7fr 5fr; gap: 3rem; }
    @media (max-width: 768px) { .pd-split { grid-template-columns: 1fr; } }
</style>

<div class="min-h-screen flex flex-col">
    @include('themes::heritage.store.header')

    <main class="flex-1" style="padding-top: 8rem; padding-bottom: 6rem;">
        <div style="max-width: 1280px; margin: 0 auto; padding: 0 64px;">
            <a href="{{ route('business.site', $business) }}" style="display: inline-flex; align-items: center; gap: 8px; font-family: 'Hanken Grotesk', sans-serif; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; color: #735c00; text-decoration: none; margin-bottom: 3rem; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.6'" onmouseout="this.style.opacity='1'">
                <span class="material-symbols-outlined" style="font-size: 16px;">arrow_back</span> Back to Store
            </a>

            <div class="pd-split">
                <div>
                    @if ($product->images && count($product->images) > 0)
                        <div style="padding: 16px; border: 0.5px solid #d4d0c8;">
                            <img src="{{ Storage::url($product->images[0]) }}" alt="{{ $product->name }}" style="width: 100%; aspect-ratio: 1; object-fit: cover; display: block;">
                        </div>
                        @if (count($product->images) > 1)
                            <div style="display: flex; gap: 0.5rem; margin-top: 0.75rem;">
                                @foreach ($product->images as $img)
                                    <img src="{{ Storage::url($img) }}" alt="" style="width: 4.5rem; height: 4.5rem; object-fit: cover; border: 0.5px solid #d4d0c8; cursor: pointer; transition: border-color 0.2s; padding: 4px;" onmouseover="this.style.borderColor='#735c00'" onmouseout="this.style.borderColor='#d4d0c8'">
                                @endforeach
                            </div>
                        @endif
                    @else
                        <div style="width: 100%; aspect-ratio: 1; background: #f5f3ef; border: 0.5px solid #d4d0c8; display: flex; align-items: center; justify-content: center;">
                            <span class="material-symbols-outlined" style="color: #d4d0c8; font-size: 64px;">inventory_2</span>
                        </div>
                    @endif
                </div>
                <div>
                    @if($product->category)
                        <span class="heritage-label" style="font-size: 0.625rem; color: #735c00; display: block; margin-bottom: 1rem; padding: 0.375rem 0.875rem; border: 0.5px solid #735c00; display: inline-block;">{{ $product->category->name }}</span>
                    @endif
                    <h1 style="font-family: 'Playfair Display', serif; font-style: italic; font-weight: 500; font-size: 2rem; margin-bottom: 1rem; letter-spacing: -0.01em;">{{ $product->name }}</h1>
                    <div style="margin-bottom: 2rem;">
                        @if($product->variants && $product->variants->isNotEmpty())
                            <span style="font-family: 'Hanken Grotesk', sans-serif; font-size: 1.5rem; font-weight: 600; color: #735c00;">${{ number_format($product->variants->min('price'), 2) }}+</span>
                        @else
                            <span style="font-family: 'Hanken Grotesk', sans-serif; font-size: 1.5rem; font-weight: 600; color: #735c00;">${{ number_format($product->price, 2) }}</span>
                        @endif
                        @if($product->compare_price && $product->compare_price > $product->price)
                            <span style="font-family: 'Hanken Grotesk', sans-serif; font-size: 1rem; color: #bbb; text-decoration: line-through; margin-left: 0.75rem;">${{ number_format($product->compare_price, 2) }}</span>
                        @endif
                    </div>

                    @if($product->description)
                        <div style="font-family: 'Hanken Grotesk', sans-serif; font-size: 0.9375rem; line-height: 1.7; color: #555; margin-bottom: 2.5rem;">{!! $product->description !!}</div>
                    @endif

                    @if($product->variants && $product->variants->isNotEmpty())
                        <div style="margin-bottom: 2rem;">
                            <h3 class="heritage-label" style="font-size: 0.625rem; margin-bottom: 1rem; color: #000;">Options</h3>
                            <div style="display: flex; flex-direction: column; gap: 1rem;">
                                @foreach($product->variants as $variant)
                                    <div>
                                        <p class="heritage-label" style="font-size: 0.5625rem; color: #888; margin-bottom: 0.5rem;">{{ $variant['name'] }}</p>
                                        <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                                            @foreach($variant['options'] ?? [] as $option)
                                                <div style="display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 0.875rem; border: 0.5px solid #d4d0c8; font-family: 'Hanken Grotesk', sans-serif; font-size: 0.8125rem; transition: border-color 0.2s; cursor: pointer;" onmouseover="this.style.borderColor='#735c00'" onmouseout="this.style.borderColor='#d4d0c8'">
                                                    @if(!empty($option['image']))
                                                        <img src="{{ Storage::url($option['image']) }}" alt="" style="width: 1.25rem; height: 1.25rem; object-fit: cover;">
                                                    @endif
                                                    <span>{{ $option['value'] }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div style="margin-top: 2.5rem; display: flex; flex-wrap: wrap; gap: 1rem;">
                        @if ($business->order_mode === 'checkout')
                            <form method="POST" action="{{ route('order.cart.add', ['business' => $business->slug, 'type' => 'product', 'id' => $product->id]) }}" style="display: inline-flex;">
                                @csrf
                                <button type="submit"
                                    class="heritage-btn-primary"
                                    style="display: inline-flex; align-items: center; gap: 8px; text-decoration: none; cursor: pointer;">
                                    <span class="material-symbols-outlined" style="font-size: 18px;">shopping_bag</span> Add to Cart
                                </button>
                            </form>
                        @endif
                        @if($business->whatsapp)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $business->whatsapp) }}?text=Hi! I'm interested in "{{ $product->name }}" priced at {{ \App\Support\Money\Money::format($product->price, $business->currency_code) }}. Is it available?" target="_blank"
                               class="{{ $business->order_mode === 'checkout' ? 'heritage-btn-secondary' : 'heritage-btn-primary' }}"
                               style="display: inline-flex; align-items: center; gap: 8px; text-decoration: none;"
                               onclick="navigator.sendBeacon('{{ route('track.click', [$business->id, 'whatsapp_click']) }}?source=product&url='+encodeURIComponent(location.href));">
                                <span class="material-symbols-outlined" style="font-size: 18px;">chat</span> {{ $business->order_mode === 'checkout' ? 'WhatsApp' : 'Add to Bag' }}
                            </a>
                        @endif
                        @if($business->phone)
                            <a href="tel:{{ $business->phone }}"
                               class="heritage-btn-secondary"
                               style="display: inline-flex; align-items: center; gap: 8px; text-decoration: none;">
                                <span class="material-symbols-outlined" style="font-size: 18px;">call</span> Call Now
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>

    @include('themes::heritage.store.footer')
</div>
