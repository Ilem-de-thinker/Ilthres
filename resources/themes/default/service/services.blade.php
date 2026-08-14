{{-- Default theme · Service services listing — Serene Ivory --}}
@if ($services && count($services) > 0)
<section id="services" style="padding: 96px 0; background: #f1eee7;">
    <div style="max-width: 1280px; margin: 0 auto; padding: 0 64px;">
        <div style="margin-bottom: 48px;">
            <span class="section-label" style="color: #6c5e06; display: block; margin-bottom: 12px;">WHAT WE OFFER</span>
            <h2 style="font-family: 'Playfair Display', serif; font-style: italic; font-size: clamp(1.75rem, 3.5vw, 2.75rem); color: #1c1c18;">Our Services</h2>
        </div>

        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 32px;">
            @foreach ($services as $i => $service)
            <a href="{{ route('business.service', [$business, $service]) }}" class="subtle-shadow" style="display: block; background: #fcf9f2; border-radius: 0.5rem; overflow: hidden; transition: transform 0.2s; text-decoration: none;"
               onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='none'">
                @if($service->image)
                <div style="overflow: hidden;">
                    <img src="{{ Storage::url($service->image) }}" alt="{{ $service->name }}" style="width: 100%; height: 220px; object-fit: cover; transition: transform 0.5s; display: block;" onmouseover="this.style.transform='scale(1.03)'" onmouseout="this.style.transform='scale(1)'">
                </div>
                @endif
                <div style="padding: 28px;">
                    <span class="section-label" style="color: #757873; margin-bottom: 12px; display: block;">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                    <h3 style="font-family: 'Playfair Display', serif; font-style: italic; font-size: 1.125rem; color: #1c1c18; margin-bottom: 10px;">{{ $service->name }}</h3>
                    @if($service->description)
                    <p style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.8125rem; line-height: 1.7; color: #454843;">{{ \Illuminate\Support\Str::limit(strip_tags($service->description), 120) }}</p>
                    @endif
                    @if($service->price)
                    <div style="margin-top: 20px; padding-top: 16px; border-top: 0.5px solid #E5E0D5;">
                        <span class="section-label" style="color: #757873; display: block; margin-bottom: 4px;">FROM</span>
                        <span style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.125rem; font-weight: 600; color: #6c5e06;">{{ \App\Support\Money\Money::format($service->price, $business->currency_code) }}</span>
                    </div>
                    @endif
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@else
<section id="services" style="padding: 96px 0; background: #f1eee7;">
    <div style="max-width: 1280px; margin: 0 auto; padding: 0 64px; text-align: center; padding-top: 48px; padding-bottom: 48px;">
        <span class="material-symbols-outlined" style="color: #c5c7c1; font-size: 48px; display: block; margin-bottom: 16px;">design_services</span>
        <p style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.875rem; color: #757873;">No services listed yet.</p>
    </div>
</section>
@endif
