{{-- Heritage theme · Service detail page --}}
@section('title', $service->name . ' — ' . $business->name)

<div class="min-h-screen flex flex-col">
    @include('themes::heritage.service.header')
    <main class="flex-1" style="padding-top: 8rem; padding-bottom: 6rem;">
        <div style="max-width: 800px; margin: 0 auto; padding: 0 64px;">
            <a href="{{ route('business.site', $business) }}#services" style="display: inline-flex; align-items: center; gap: 8px; font-family: 'Hanken Grotesk', sans-serif; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; color: #735c00; text-decoration: none; margin-bottom: 3rem; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.6'" onmouseout="this.style.opacity='1'">
                <span class="material-symbols-outlined" style="font-size: 16px;">arrow_back</span> Back to Services
            </a>

            <div style="margin-bottom: 1.5rem;">
                <span class="heritage-label" style="font-size: 0.5625rem; color: #735c00; padding: 0.375rem 0.875rem; border: 0.5px solid #735c00; display: inline-flex; align-items: center; gap: 6px;">
                    <span class="material-symbols-outlined" style="font-size: 14px;">{{ $service->icon ?? 'design_services' }}</span> Service
                </span>
            </div>

            <h1 style="font-family: 'Playfair Display', serif; font-style: italic; font-weight: 500; font-size: 2.25rem; margin-bottom: 1.5rem; letter-spacing: -0.01em;">{{ $service->name }}</h1>

            <div style="display: flex; gap: 1rem; margin-bottom: 2.5rem;">
                @if($service->price)
                    <div style="padding: 1.25rem; border: 0.5px solid #d4d0c8; min-width: 120px;">
                        <div style="font-family: 'Hanken Grotesk', sans-serif; font-size: 1.375rem; font-weight: 600; color: #735c00;">${{ number_format($service->price, 2) }}</div>
                        <div class="heritage-label" style="font-size: 0.5625rem; color: #888; margin-top: 0.25rem;">Price</div>
                    </div>
                @endif
                @if($service->duration_minutes)
                    <div style="padding: 1.25rem; border: 0.5px solid #d4d0c8; min-width: 120px;">
                        <div style="font-family: 'Hanken Grotesk', sans-serif; font-size: 1.375rem; font-weight: 600; color: #000;">{{ $service->duration_minutes }}</div>
                        <div class="heritage-label" style="font-size: 0.5625rem; color: #888; margin-top: 0.25rem;">Minutes</div>
                    </div>
                @endif
            </div>

            @if($service->description)
                <div style="font-family: 'Hanken Grotesk', sans-serif; font-size: 1rem; line-height: 1.7; color: #444; margin-bottom: 2.5rem;">{!! $service->description !!}</div>
            @endif

            @if($service->features && count($service->features) > 0)
                <div style="margin-bottom: 2.5rem;">
                    <h3 style="font-family: 'Playfair Display', serif; font-size: 1.125rem; font-weight: 600; margin-bottom: 1.5rem;">What's Included</h3>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
                        @foreach($service->features as $feature)
                            <div style="display: flex; align-items: flex-start; gap: 0.75rem; padding: 1rem; border: 0.5px solid #d4d0c8;">
                                <span class="material-symbols-outlined" style="color: #735c00; font-size: 18px; margin-top: 2px;">check_circle</span>
                                <span style="font-family: 'Hanken Grotesk', sans-serif; font-size: 0.875rem; color: #000;">{{ $feature }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                @if ($business->order_mode === 'checkout')
                    <form method="POST" action="{{ route('order.cart.add', ['business' => $business->slug, 'type' => 'service', 'id' => $service->id]) }}" style="display: inline-flex;">
                        @csrf
                        <button type="submit"
                            class="heritage-btn-primary"
                            style="display: inline-flex; align-items: center; gap: 8px; text-decoration: none; cursor: pointer;">
                            Add to Cart <span class="material-symbols-outlined" style="font-size: 18px;">arrow_forward</span>
                        </button>
                    </form>
                @endif
                @if($business->whatsapp)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $business->whatsapp) }}?text=Hi! I'd like to book "{{ $service->name }}" at {{ \App\Support\Money\Money::format($service->price, $business->currency_code) }}. Is this available?" target="_blank"
                       class="{{ $business->order_mode === 'checkout' ? 'heritage-btn-secondary' : 'heritage-btn-primary' }}"
                       style="display: inline-flex; align-items: center; gap: 8px; text-decoration: none;"
                       onclick="navigator.sendBeacon('{{ route('track.click', [$business->id, 'whatsapp_click']) }}?source=service&url='+encodeURIComponent(location.href));">
                        {{ $business->order_mode === 'checkout' ? 'WhatsApp' : 'Book Now' }} <span class="material-symbols-outlined" style="font-size: 18px;">arrow_forward</span>
                    </a>
                @endif
                <a href="{{ route('business.site', $business) }}#services" class="heritage-btn-secondary" style="display: inline-flex; align-items: center; gap: 8px; text-decoration: none;">All Services</a>
            </div>
        </div>
    </main>
    @include('themes::heritage.service.footer')

    {{-- Book Now Section --}}
    @if($service->hasAvailability ?? false)
    <section style="padding: 48px 0; border-top: 1px solid #d4c5a0;">
        <div style="max-width: 960px; margin: 0 auto; padding: 0 24px;">
            <h2 style="font-family: 'DM Serif Display', serif; font-size: 1.75rem; color: #2c2417; margin-bottom: 24px;">Book This Service</h2>
            <livewire:booking.book-appointment :business="$business" :service="$service" :key="'booking-'.$service->id" />
        </div>
    </section>
    @endif

    {{-- Reviews Section --}}
    <section style="padding: 48px 0; border-top: 1px solid #d4c5a0;">
        <div style="max-width: 960px; margin: 0 auto; padding: 0 24px;">
            <h2 style="font-family: 'DM Serif Display', serif; font-size: 1.75rem; color: #2c2417; margin-bottom: 24px;">What Customers Say</h2>
            <livewire:review.review-display :businessId="$business->id" :key="'reviews-'.$business->id" />
            @auth
                @if(auth()->user()->canReview($business))
                    <a href="{{ route('review.create', $business) }}" style="display: inline-block; padding: 12px 24px; border: 1px solid #8b7355; color: #2c2417; font-family: 'Cormorant Garamond', serif; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.05em; text-decoration: none; margin-top: 24px;">Write a Review</a>
                @endif
            @endauth
        </div>
    </section>
</div>
