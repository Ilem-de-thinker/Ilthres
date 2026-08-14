{{-- Default theme · Service detail — Serene Ivory --}}
@section('title', $service->name . ' — ' . $business->name)

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
    @include('themes::default.service.header')

    <main class="flex-1" style="padding-top: 112px; padding-bottom: 96px;">
        <div style="max-width: 1280px; margin: 0 auto; padding: 0 64px;">
            {{-- Breadcrumb --}}
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 48px;">
                <a href="{{ route('business.site', $business) }}#services" style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #6c5e06; text-decoration: none;" onmouseover="this.style.color='#1c1c18'" onmouseout="this.style.color='#6c5e06'">
                    <span class="material-symbols-outlined" style="font-size: 16px; vertical-align: middle;">arrow_back</span> BACK TO SERVICES
                </a>
            </div>

            {{-- Service Grid: 12-col --}}
            <div style="display: grid; grid-template-columns: repeat(12, 1fr); gap: 32px;">
                {{-- Left: Service Info (7 cols) --}}
                <div style="grid-column: 1 / 8; display: flex; flex-direction: column; gap: 24px;">
                    <div>
                        <span class="section-label" style="color: #6c5e06; margin-bottom: 12px; display: inline-block;">SERVICE</span>
                        <h1 style="font-family: 'Playfair Display', serif; font-style: italic; font-size: clamp(2rem, 4vw, 3rem); color: #1c1c18; margin-bottom: 16px;">
                            {{ $service->name }}
                        </h1>
                        @if($service->description)
                        <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.9375rem; line-height: 1.8; color: #454843;">
                            {!! $service->description !!}
                        </div>
                        @endif
                    </div>

                    {{-- Price Display --}}
                    @if($service->price)
                    <div class="thin-frame" style="border-radius: 0.5rem;">
                        <span class="section-label" style="color: #757873; display: block; margin-bottom: 8px;">FROM</span>
                        <span style="font-family: 'Playfair Display', serif; font-size: 2rem; color: #1c1c18; display: block; margin-bottom: 16px;">{{ \App\Support\Money\Money::format($service->price, $business->currency_code) }}</span>
                        @if ($business->order_mode === 'checkout')
                            <form method="POST" action="{{ route('order.cart.add', ['business' => $business->slug, 'type' => 'service', 'id' => $service->id]) }}">
                                @csrf
                                <button type="submit"
                                    style="display: inline-flex; align-items: center; gap: 8px; padding: 16px 40px; background: #1c1c18; color: #fcf9f2; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.6875rem; font-weight: 600; letter-spacing: 0.05em; text-transform: uppercase; border: 0.5px solid #1c1c18; border-radius: 0.25rem; transition: all 0.2s; text-decoration: none; cursor: pointer;"
                                    onmouseover="this.style.background='#454843'; this.style.borderColor='#454843';"
                                    onmouseout="this.style.background='#1c1c18'; this.style.borderColor='#1c1c18';">
                                    <span class="material-symbols-outlined" style="font-size: 16px;">shopping_cart</span> ADD TO CART
                                </button>
                            </form>
                        @elseif($business->whatsapp)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $business->whatsapp) }}?text=Hi! I'd like to book "{{ $service->name }}" at {{ \App\Support\Money\Money::format($service->price, $business->currency_code) }}. Is this available?"
                           target="_blank" rel="noopener"
                           style="display: inline-flex; align-items: center; gap: 8px; padding: 16px 40px; background: #1c1c18; color: #fcf9f2; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.6875rem; font-weight: 600; letter-spacing: 0.05em; text-transform: uppercase; border: 0.5px solid #1c1c18; border-radius: 0.25rem; transition: all 0.2s; text-decoration: none;"
                           onmouseover="this.style.background='#454843'; this.style.borderColor='#454843';"
                           onmouseout="this.style.background='#1c1c18'; this.style.borderColor='#1c1c18';">
                            <span class="material-symbols-outlined" style="font-size: 16px;">chat</span> INQUIRE
                        </a>
                        @endif
                    </div>
                    @endif

                    {{-- Book Now Button --}}
                    @if($service->hasAvailability ?? false)
                    <div class="thin-frame" style="border-radius: 0.5rem;">
                        <span class="section-label" style="color: #757873; display: block; margin-bottom: 8px;">BOOK THIS SERVICE</span>
                        <p style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.875rem; color: #454843; margin-bottom: 16px;">Select a date and time that works for you.</p>
                        <livewire:booking.book-appointment :business="$business" :service="$service" :key="'booking-'.$service->id" />
                    </div>
                    @endif

                    {{-- CTA Buttons --}}
                    <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                        @if($business->whatsapp)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $business->whatsapp) }}?text=Hi! I'd like to book "{{ $service->name }}" at {{ \App\Support\Money\Money::format($service->price, $business->currency_code) }}. Is this available?"
                           target="_blank" rel="noopener"
                           style="display: inline-flex; align-items: center; gap: 8px; padding: 16px 40px; background: {{ $business->order_mode === 'checkout' ? 'transparent' : '#1c1c18' }}; color: {{ $business->order_mode === 'checkout' ? '#1c1c18' : '#fcf9f2' }}; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.6875rem; font-weight: 600; letter-spacing: 0.05em; text-transform: uppercase; border: 0.5px solid {{ $business->order_mode === 'checkout' ? '#5e5f5c' : '#1c1c18' }}; border-radius: 0.25rem; transition: all 0.2s; text-decoration: none;"
                           onmouseover="this.style.background='#1c1c18'; this.style.color='#fcf9f2'; this.style.borderColor='#1c1c18';"
                           onmouseout="this.style.background='{{ $business->order_mode === 'checkout' ? 'transparent' : '#1c1c18' }}'; this.style.color='{{ $business->order_mode === 'checkout' ? '#1c1c18' : '#fcf9f2' }}'; this.style.borderColor='{{ $business->order_mode === 'checkout' ? '#5e5f5c' : '#1c1c18' }}';">
                            <span class="material-symbols-outlined" style="font-size: 16px;">chat</span> WHATSAPP
                        </a>
                        <a href="tel:{{ $business->phone }}"
                           style="display: inline-flex; align-items: center; gap: 8px; padding: 16px 40px; border: 0.5px solid #5e5f5c; color: #1c1c18; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.6875rem; font-weight: 600; letter-spacing: 0.05em; text-transform: uppercase; transition: all 0.2s; text-decoration: none; border-radius: 0.25rem;"
                           onmouseover="this.style.background='#1c1c18'; this.style.color='#fcf9f2'; this.style.borderColor='#1c1c18';"
                           onmouseout="this.style.background='transparent'; this.style.color='#1c1c18'; this.style.borderColor='#5e5f5c';">
                            <span class="material-symbols-outlined" style="font-size: 16px;">call</span> CALL NOW
                        </a>
                        @endif
                    </div>
                </div>

                {{-- Right: Service Image (5 cols) --}}
                <div style="grid-column: 8 / 13;">
                    @if($service->image)
                    <div class="thin-frame" style="border-radius: 0.5rem; overflow: hidden;">
                        <img src="{{ Storage::url($service->image) }}" alt="{{ $service->name }}" style="width: 100%; aspect-ratio: 4/5; object-fit: cover; display: block;">
                    </div>
                    @else
                    <div class="thin-frame" style="aspect-ratio: 4/5; border-radius: 0.5rem; background: #f1eee7; display: flex; align-items: center; justify-content: center;">
                        <span class="material-symbols-outlined" style="color: #c5c7c1; font-size: 64px;">design_services</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Reviews Section --}}
            <div style="margin-top: 64px; margin-bottom: 64px;">
                <span class="section-label" style="color: #6c5e06; margin-bottom: 16px; display: block;">REVIEWS</span>
                <h2 style="font-family: 'Playfair Display', serif; font-style: italic; font-size: clamp(1.5rem, 3vw, 2rem); color: #1c1c18; margin-bottom: 32px;">What Customers Say</h2>
                <livewire:review.review-display :businessId="$business->id" :key="'reviews-'.$business->id" />
                @auth
                    @if(auth()->user()->canReview($business))
                        <a href="{{ route('review.create', $business) }}" style="display: inline-flex; align-items: center; gap: 8px; padding: 12px 24px; border: 0.5px solid #5e5f5c; color: #1c1c18; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.6875rem; font-weight: 600; letter-spacing: 0.05em; text-transform: uppercase; border-radius: 0.25rem; text-decoration: none; margin-top: 24px;">
                            <span class="material-symbols-outlined" style="font-size: 16px;">rate_review</span> WRITE A REVIEW
                        </a>
                    @endif
                @endauth
            </div>
        </div>
    </main>

    @include('themes::default.service.footer')
</div>
