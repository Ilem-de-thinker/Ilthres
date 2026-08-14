{{-- Modern theme · Services list — Lumina SaaS glass service cards --}}
@if ($services && count($services) > 0)
<section id="services" style="padding: 0 0 128px;">
    <div style="max-width: 1280px; margin: 0 auto; padding: 0 40px;">
        <div style="margin-bottom: 48px;">
            <span class="label" style="color: #4648d4; margin-bottom: 8px; display: block;">Services</span>
            <h2 style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: clamp(1.75rem, 3vw, 2.5rem); letter-spacing: -0.03em; line-height: 1.1; color: #1a1a2e;">
                What We Offer
            </h2>
        </div>

        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px;">
            @foreach ($services as $i => $service)
            <a href="{{ route('business.service', [$business, $service]) }}" class="glass-card" style="display: flex; flex-direction: column; overflow: hidden; text-decoration: none;">
                @if($service->image)
                <div style="overflow: hidden;">
                    <img src="{{ Storage::url($service->image) }}" alt="{{ $service->name }}"
                        style="width: 100%; height: 240px; object-fit: cover; border-radius: 16px 16px 0 0; transition: transform 0.5s;"
                        onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                </div>
                @endif
                <div style="padding: 32px; display: flex; flex-direction: column; flex-grow: 1;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
                        <span style="font-size: 0.75rem; font-weight: 600; color: #4648d4;">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <span style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; background: rgba(70,72,212,0.08); color: #4648d4; border-radius: 8px; transition: all 0.2s;">
                            <span class="material-symbols-outlined" style="font-size: 16px;">arrow_forward</span>
                        </span>
                    </div>
                    <h3 style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700; font-size: 1.125rem; color: #1a1a2e; margin-bottom: 12px;">{{ $service->name }}</h3>
                    @if($service->description)
                    <p style="font-size: 0.875rem; line-height: 1.7; color: #6b7280; flex-grow: 1;">{{ \Illuminate\Support\Str::limit(strip_tags($service->description), 120) }}</p>
                    @endif
                    @if($service->price)
                    <div style="margin-top: 24px; padding-top: 20px; border-top: 1px solid rgba(0,0,0,0.06);">
                        <span style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: #9ca3af; display: block; margin-bottom: 4px;">Starting from</span>
                        <span style="font-weight: 700; font-size: 1.5rem; color: #1a1a2e;">{{ \App\Support\Money\Money::format($service->price, $business->currency_code) }}</span>
                    </div>
                    @endif
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif
