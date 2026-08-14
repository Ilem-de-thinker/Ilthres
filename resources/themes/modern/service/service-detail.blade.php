{{-- Modern theme · Service detail — Lumina SaaS --}}
<section style="position: relative; overflow: hidden;">
    <div style="position: absolute; inset: 0; overflow: hidden; pointer-events: none;">
        <div style="position: absolute; top: -120px; left: -120px; width: 400px; height: 400px; background: radial-gradient(circle, rgba(70,72,212,0.12) 0%, transparent 70%); border-radius: 50%;"></div>
        <div style="position: absolute; bottom: -80px; right: -80px; width: 300px; height: 300px; background: radial-gradient(circle, rgba(0,101,145,0.1) 0%, transparent 70%); border-radius: 50%;"></div>
    </div>

    <div style="max-width: 1280px; margin: 0 auto; padding: 0 40px; padding-top: 96px; padding-bottom: 64px;">
        <a href="{{ route('business.site', $business) }}#services" style="display: inline-flex; align-items: center; gap: 6px; margin-bottom: 32px; font-size: 0.875rem; font-weight: 500; color: #4648d4; text-decoration: none;" onmouseover="this.style.color='#006591'" onmouseout="this.style.color='#4648d4'">
            <span class="material-symbols-outlined" style="font-size: 16px;">arrow_back</span> Back to Services
        </a>

        <div style="display: grid; grid-template-columns: repeat(12, 1fr); gap: 48px; align-items: center; min-height: 60vh;">
            <div style="grid-column: 1 / 7;">
                <div class="glass-card" style="padding: 40px;">
                    <span class="label" style="color: #4648d4; margin-bottom: 16px; display: block;">Service</span>
                    <h1 style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: clamp(2rem, 4vw, 3.25rem); letter-spacing: -0.03em; line-height: 1.05; color: #1a1a2e; margin-bottom: 24px;">
                        {{ $service->name }}
                    </h1>
                    @if($service->description)
                    <p style="font-size: 1rem; line-height: 1.8; color: #4b5563; margin-bottom: 32px;">
                        {{ $service->description }}
                    </p>
                    @endif
                    @if($service->price)
                    <div style="margin-bottom: 40px; padding-bottom: 32px; border-bottom: 1px solid rgba(0,0,0,0.06);">
                        <span style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: #9ca3af; display: block; margin-bottom: 8px;">Starting from</span>
                        <span style="font-weight: 800; font-size: 2.5rem; color: #1a1a2e;">{{ \App\Support\Money\Money::format($service->price, $business->currency_code) }}</span>
                    </div>
                    @endif
                    <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                        @if ($business->order_mode === 'checkout')
                            <form method="POST" action="{{ route('order.cart.add', ['business' => $business->slug, 'type' => 'service', 'id' => $service->id]) }}">
                                @csrf
                                <button type="submit" class="btn-primary" style="cursor: pointer;">
                                    <span class="material-symbols-outlined" style="font-size: 18px;">shopping_cart</span> Add to Cart
                                </button>
                            </form>
                        @endif
                        @if($business->whatsapp)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $business->whatsapp) }}?text=Hi! I'd like to book "{{ $service->name }}" at {{ \App\Support\Money\Money::format($service->price, $business->currency_code) }}. Is this available?"
                           target="_blank" rel="noopener" class="{{ $business->order_mode === 'checkout' ? 'btn-secondary' : 'btn-primary' }}">
                            <span class="material-symbols-outlined" style="font-size: 18px;">{{ $business->order_mode === 'checkout' ? 'chat' : 'calendar_month' }}</span> {{ $business->order_mode === 'checkout' ? 'WhatsApp' : 'Book Now via WhatsApp' }}
                        </a>
                        <a href="tel:{{ $business->phone }}" class="btn-secondary">
                            <span class="material-symbols-outlined" style="font-size: 18px;">call</span> Call Now
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            <div style="grid-column: 8 / 13;">
                @if($service->image)
                <div class="glass" style="overflow: hidden; aspect-ratio: 4/5;">
                    <img src="{{ Storage::url($service->image) }}" alt="{{ $service->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 16px;">
                </div>
                @else
                <div class="glass" style="aspect-ratio: 4/5; display: flex; align-items: center; justify-content: center;">
                    <span class="material-symbols-outlined" style="color: #4648d4; font-size: 64px;">work</span>
                </div>
                @endif
            </div>
        </div>

        @if($service->has_fixed_price && $service->fixed_price)
        <section style="margin-top: 80px;">
            <div style="display: grid; grid-template-columns: repeat(12, 1fr); gap: 48px;">
                <div style="grid-column: 1 / 5;">
                    <span class="label" style="color: #4648d4; margin-bottom: 8px; display: block;">Pricing</span>
                    <h2 style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 2rem; letter-spacing: -0.03em; color: #1a1a2e;">Price Card</h2>
                </div>
                <div style="grid-column: 6 / 13;">
                    <div class="glass-card" style="padding: 48px;">
                        <span style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: #9ca3af; display: block; margin-bottom: 8px;">Service Price</span>
                        <span style="font-weight: 800; font-size: 3rem; color: #1a1a2e; display: block; margin-bottom: 24px;">{{ \App\Support\Money\Money::format($service->fixed_price, $business->currency_code) }}</span>
                        @if($service->currency_code)
                        <div style="display: flex; align-items: center; gap: 12px; padding-top: 16px; border-top: 1px solid rgba(0,0,0,0.06);">
                            <span style="font-size: 0.875rem; color: #6b7280;">Currency: {{ $service->currency_code }}</span>
                        </div>
                        @endif
                        @if ($business->order_mode === 'checkout')
                        <form method="POST" action="{{ route('order.cart.add', ['business' => $business->slug, 'type' => 'service', 'id' => $service->id]) }}">
                            @csrf
                            <button type="submit" class="btn-primary" style="margin-top: 32px; display: inline-flex; cursor: pointer;">
                                <span class="material-symbols-outlined" style="font-size: 18px;">shopping_cart</span> Add to Cart
                            </button>
                        </form>
                        @elseif($business->whatsapp)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $business->whatsapp) }}?text=Hi! I'd like to book "{{ $service->name }}" at {{ \App\Support\Money\Money::format($service->price, $business->currency_code) }}. Is this available?"
                           target="_blank" rel="noopener" class="btn-primary" style="margin-top: 32px; display: inline-flex;">
                            <span class="material-symbols-outlined" style="font-size: 18px;">calendar_month</span> Book Now
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </section>
        @endif
    </div>

    {{-- Book Now Section --}}
    @if($service->hasAvailability ?? false)
    <section style="padding: 64px 0; background: #f8f9fa;">
        <div style="max-width: 1152px; margin: 0 auto; padding: 0 32px;">
            <h2 style="font-family: 'Inter', sans-serif; font-size: 1.5rem; font-weight: 700; color: #111827; margin-bottom: 32px;">Book This Service</h2>
            <div style="max-width: 640px;">
                <livewire:booking.book-appointment :business="$business" :service="$service" :key="'booking-'.$service->id" />
            </div>
        </div>
    </section>
    @endif

    {{-- Reviews Section --}}
    <section style="padding: 64px 0;">
        <div style="max-width: 1152px; margin: 0 auto; padding: 0 32px;">
            <h2 style="font-family: 'Inter', sans-serif; font-size: 1.5rem; font-weight: 700; color: #111827; margin-bottom: 32px;">Customer Reviews</h2>
            <livewire:review.review-display :businessId="$business->id" :key="'reviews-'.$business->id" />
            @auth
                @if(auth()->user()->canReview($business))
                    <a href="{{ route('review.create', $business) }}" style="display: inline-block; padding: 12px 24px; background: #111827; color: #ffffff; font-family: 'Inter', sans-serif; font-size: 0.875rem; font-weight: 600; border-radius: 8px; text-decoration: none; margin-top: 24px;">Write a Review</a>
                @endif
            @endauth
        </div>
    </section>
</section>
