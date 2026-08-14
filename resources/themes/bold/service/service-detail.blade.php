{{-- Bold theme · Service detail — High-Impact Editorial --}}
<div style="min-height: 100vh; background: #131313; color: #e2e2e2;">
    {{-- Hero --}}
    <section style="padding-top: 96px; border-bottom: 4px solid #ffffff;">
        <div style="max-width: 1280px; margin: 0 auto; padding: 0 64px; display: grid; grid-template-columns: repeat(12, 1fr); gap: 64px; min-height: 60vh; align-items: center;">
            <div style="grid-column: 1 / 8;">
                <a href="{{ route('business.site', $business) }}#services" style="display: inline-flex; align-items: center; gap: 6px; margin-bottom: 32px; font-family: 'Space Mono', monospace; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.15em; color: #e2e2e2; text-decoration: none;" onmouseover="this.style.color='#72ff70'" onmouseout="this.style.color='#e2e2e2'">
                    <span class="material-symbols-outlined" style="font-size: 16px;">arrow_back</span> BACK_TO_SERVICES
                </a>
                <h1 style="font-family: 'Anton', sans-serif; font-size: clamp(2.5rem, 6vw, 5.25rem); text-transform: uppercase; color: #ffffff; margin-bottom: 24px;">{{ $service->name }}</h1>
                @if($service->description)
                <p style="font-family: 'Archivo Narrow', sans-serif; font-size: 1.125rem; line-height: 1.7; color: #e2e2e2; opacity: 0.85; max-width: 540px; margin-bottom: 32px;">{{ $service->description }}</p>
                @endif
                @if($service->price)
                <div style="display: flex; align-items: baseline; gap: 12px; margin-bottom: 40px;">
                    <span style="font-family: 'Space Mono', monospace; font-size: 10px; letter-spacing: 0.15em; text-transform: uppercase; color: #e2e2e2; opacity: 0.5;">FROM</span>
                    <span style="font-family: 'Anton', sans-serif; font-size: 2.5rem; color: #72ff70;">{{ \App\Support\Money\Money::format($service->price, $business->currency_code) }}</span>
                </div>
                @endif
                <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                    @if ($business->order_mode === 'checkout')
                        <form method="POST" action="{{ route('order.cart.add', ['business' => $business->slug, 'type' => 'service', 'id' => $service->id]) }}">
                            @csrf
                            <button type="submit"
                                style="display: inline-flex; align-items: center; gap: 8px; padding: 16px 40px; background: #72ff70; color: #002203; font-family: 'Space Mono', monospace; font-size: 14px; font-weight: 700; letter-spacing: 0.2em; text-transform: uppercase; border: 4px solid #72ff70; transition: all 0.15s; text-decoration: none; cursor: pointer;"
                                onmouseover="this.style.background='#ffffff'; this.style.borderColor='#ffffff';"
                                onmouseout="this.style.background='#72ff70'; this.style.borderColor='#72ff70';">
                                <span class="material-symbols-outlined" style="font-size: 18px;">shopping_cart</span> ADD_TO_CART
                            </button>
                        </form>
                    @endif
                    @if($business->whatsapp)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $business->whatsapp) }}?text=Hi! I'd like to book "{{ $service->name }}" at {{ \App\Support\Money\Money::format($service->price, $business->currency_code) }}. Is this available?"
                       target="_blank" rel="noopener"
                       style="display: inline-flex; align-items: center; gap: 8px; padding: 16px 40px; background: {{ $business->order_mode === 'checkout' ? 'transparent' : '#72ff70' }}; color: {{ $business->order_mode === 'checkout' ? '#ffffff' : '#002203' }}; font-family: 'Space Mono', monospace; font-size: 14px; font-weight: 700; letter-spacing: 0.2em; text-transform: uppercase; border: 4px solid {{ $business->order_mode === 'checkout' ? '#ffffff' : '#72ff70' }}; transition: all 0.15s; text-decoration: none;"
                       onmouseover="this.style.background='#ffffff'; this.style.borderColor='#ffffff'; this.style.color='#131313';"
                       onmouseout="this.style.background='{{ $business->order_mode === 'checkout' ? 'transparent' : '#72ff70' }}'; this.style.borderColor='{{ $business->order_mode === 'checkout' ? '#ffffff' : '#72ff70' }}'; this.style.color='{{ $business->order_mode === 'checkout' ? '#ffffff' : '#002203' }}';">
                        <span class="material-symbols-outlined" style="font-size: 18px;">chat</span> WHATSAPP
                    </a>
                    <a href="tel:{{ $business->phone }}"
                       style="display: inline-flex; align-items: center; gap: 8px; padding: 16px 40px; background: transparent; color: #ffffff; font-family: 'Space Mono', monospace; font-size: 14px; font-weight: 700; letter-spacing: 0.2em; text-transform: uppercase; border: 4px solid #ffffff; transition: all 0.15s; text-decoration: none;"
                       onmouseover="this.style.background='rgba(255,255,255,0.1)';"
                       onmouseout="this.style.background='transparent';">
                        <span class="material-symbols-outlined" style="font-size: 18px;">call</span> CALL_NOW
                    </a>
                    @endif
                </div>
            </div>
            <div style="grid-column: 8 / 13;">
                @if($service->image)
                <div style="aspect-ratio: 4/5; border: 4px solid #ffffff; overflow: hidden;">
                    <img src="{{ Storage::url($service->image) }}" alt="{{ $service->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                @else
                <div class="dot-pattern" style="aspect-ratio: 4/5; border: 4px solid #ffffff;"></div>
                @endif
            </div>
        </div>
    </section>

    @if($service->has_fixed_price && $service->fixed_price)
    {{-- Pricing --}}
    <section style="padding: 64px 0; border-bottom: 4px solid #ffffff;">
        <div style="max-width: 1280px; margin: 0 auto; padding: 0 64px; display: grid; grid-template-columns: repeat(12, 1fr); gap: 48px;">
            <div style="grid-column: 1 / 5;">
                <span style="font-family: 'Space Mono', monospace; font-size: 10px; letter-spacing: 0.15em; text-transform: uppercase; color: #72ff70; display: block; margin-bottom: 16px;">PRICING</span>
                <h2 style="font-family: 'Anton', sans-serif; font-size: 2rem; text-transform: uppercase; color: #ffffff;">PRICE_CARD</h2>
            </div>
            <div style="grid-column: 6 / 13;">
                <div style="border: 4px solid #ffffff; padding: 48px;">
                    <span style="font-family: 'Space Mono', monospace; font-size: 10px; letter-spacing: 0.15em; text-transform: uppercase; color: #e2e2e2; opacity: 0.5; display: block; margin-bottom: 8px;">SERVICE_PRICE</span>
                    <span style="font-family: 'Anton', sans-serif; font-size: 3rem; color: #72ff70; display: block; margin-bottom: 24px;">{{ \App\Support\Money\Money::format($service->fixed_price, $business->currency_code) }}</span>
                    @if($service->currency_code)
                    <div style="display: flex; align-items: center; gap: 12px; padding-top: 16px; border-top: 2px solid #444748;">
                        <span style="font-family: 'Space Mono', monospace; font-size: 12px; color: #e2e2e2; opacity: 0.7;">Currency: {{ $service->currency_code }}</span>
                    </div>
                    @endif
                    @if($business->order_mode === 'checkout')
                    <form method="POST" action="{{ route('order.cart.add', ['business' => $business->slug, 'type' => 'service', 'id' => $service->id]) }}">
                        @csrf
                        <button type="submit"
                            style="display: inline-flex; align-items: center; gap: 8px; padding: 16px 40px; background: #ffffff; color: #131313; font-family: 'Space Mono', monospace; font-size: 14px; font-weight: 700; letter-spacing: 0.2em; text-transform: uppercase; border: 4px solid #ffffff; margin-top: 32px; transition: all 0.15s; text-decoration: none; cursor: pointer;"
                            onmouseover="this.style.background='#72ff70'; this.style.borderColor='#72ff70';"
                            onmouseout="this.style.background='#ffffff'; this.style.borderColor='#ffffff';">
                            ADD_TO_CART
                        </button>
                    </form>
                    @elseif($business->whatsapp)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $business->whatsapp) }}?text=Hi! I'd like to book "{{ $service->name }}" at {{ \App\Support\Money\Money::format($service->price, $business->currency_code) }}. Is this available?"
                       target="_blank" rel="noopener"
                       style="display: inline-flex; align-items: center; gap: 8px; padding: 16px 40px; background: #ffffff; color: #131313; font-family: 'Space Mono', monospace; font-size: 14px; font-weight: 700; letter-spacing: 0.2em; text-transform: uppercase; border: 4px solid #ffffff; margin-top: 32px; transition: all 0.15s; text-decoration: none;"
                       onmouseover="this.style.background='#72ff70'; this.style.borderColor='#72ff70';"
                       onmouseout="this.style.background='#ffffff'; this.style.borderColor='#ffffff';">
                        INQUIRE
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- Book Now Section --}}
    @if($service->hasAvailability ?? false)
    <section style="padding: 64px 0; border-bottom: 4px solid #ffffff;">
        <div style="max-width: 1280px; margin: 0 auto; padding: 0 64px;">
            <span style="font-family: 'Space Mono', monospace; font-size: 10px; letter-spacing: 0.15em; text-transform: uppercase; color: #72ff70; display: block; margin-bottom: 16px;">BOOK_SERVICE</span>
            <h2 style="font-family: 'Anton', sans-serif; font-size: 2rem; text-transform: uppercase; color: #ffffff; margin-bottom: 32px;">SELECT_A_TIME</h2>
            <div style="max-width: 640px;">
                <livewire:booking.book-appointment :business="$business" :service="$service" :key="'booking-'.$service->id" />
            </div>
        </div>
    </section>
    @endif

    {{-- Reviews Section --}}
    <section style="padding: 64px 0;">
        <div style="max-width: 1280px; margin: 0 auto; padding: 0 64px;">
            <span style="font-family: 'Space Mono', monospace; font-size: 10px; letter-spacing: 0.15em; text-transform: uppercase; color: #72ff70; display: block; margin-bottom: 16px;">REVIEWS</span>
            <h2 style="font-family: 'Anton', sans-serif; font-size: 2rem; text-transform: uppercase; color: #ffffff; margin-bottom: 32px;">WHAT_CUSTOMERS_SAY</h2>
            <livewire:review.review-display :businessId="$business->id" :key="'reviews-'.$business->id" />
            @auth
                @if(auth()->user()->canReview($business))
                    <a href="{{ route('review.create', $business) }}" style="display: inline-flex; align-items: center; gap: 8px; padding: 12px 24px; border: 4px solid #ffffff; color: #ffffff; font-family: 'Space Mono', monospace; font-size: 14px; font-weight: 700; letter-spacing: 0.2em; text-transform: uppercase; text-decoration: none; margin-top: 24px;" onmouseover="this.style.background='#72ff70'; this.style.borderColor='#72ff70'; this.style.color='#002203';" onmouseout="this.style.background='transparent'; this.style.borderColor='#ffffff'; this.style.color='#ffffff';">
                        WRITE_A_REVIEW
                    </a>
                @endif
            @endauth
        </div>
    </section>
</div>
