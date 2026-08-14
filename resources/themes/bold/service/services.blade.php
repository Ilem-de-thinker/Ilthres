{{-- Bold theme · Services list — High-Impact Editorial: 12-col grid --}}
@if ($services && count($services) > 0)
<section id="services" style="padding: 80px 0; border-bottom: 4px solid #ffffff;">
    <div style="max-width: 1280px; margin: 0 auto; padding: 0 64px;">
        <div style="display: flex; align-items: flex-end; justify-content: space-between; padding-bottom: 16px; border-bottom: 4px solid #ffffff; margin-bottom: 48px;">
            <div>
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                    <span style="font-family: 'Space Mono', monospace; font-size: 10px; letter-spacing: 0.15em; text-transform: uppercase; color: #72ff70;">SECTION_03</span>
                    <div style="width: 40px; height: 1px; background: #ffffff;"></div>
                </div>
                <h2 style="font-family: 'Anton', sans-serif; font-size: clamp(2rem, 4vw, 3.5rem); text-transform: uppercase; color: #ffffff;">OUR_SERVICES</h2>
            </div>
            <span style="font-family: 'Space Mono', monospace; font-size: 10px; text-transform: uppercase; letter-spacing: 0.15em; color: #e2e2e2; opacity: 0.6;">{{ count($services) }} @choice('item|items', count($services))</span>
        </div>

        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px;">
            @foreach ($services as $i => $service)
            <a href="{{ route('business.service', [$business, $service]) }}" style="display: block; border: 4px solid #ffffff; overflow: hidden; transition: all 0.15s; text-decoration: none;"
               onmouseover="this.style.transform='translateY(-4px)'; this.querySelector('.svc-bg').style.background='#ffffff'; this.querySelector('.svc-bg').style.color='#131313';"
               onmouseout="this.style.transform='none'; this.querySelector('.svc-bg').style.background='#131313'; this.querySelector('.svc-bg').style.color='#ffffff';">
                @if($service->image)
                <div style="border-bottom: 4px solid #ffffff; height: 240px; overflow: hidden;">
                    <img src="{{ Storage::url($service->image) }}" alt="{{ $service->name }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                </div>
                @endif
                <div style="padding: 32px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
                        <span style="font-family: 'Space Mono', monospace; font-size: 10px; letter-spacing: 0.15em; text-transform: uppercase; color: #e2e2e2; opacity: 0.5;">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <span class="svc-bg" style="display: flex; align-items: center; justify-content: center; width: 32px; height: 32px; border: 2px solid #ffffff; color: #ffffff; font-size: 18px; background: #131313; transition: all 0.15s;">
                            <span class="material-symbols-outlined" style="font-size: 18px;">arrow_forward</span>
                        </span>
                    </div>
                    <h3 style="font-family: 'Anton', sans-serif; font-size: 1.25rem; text-transform: uppercase; color: #ffffff; margin-bottom: 12px;">{{ $service->name }}</h3>
                    @if($service->description)
                    <p style="font-family: 'Archivo Narrow', sans-serif; font-size: 0.875rem; line-height: 1.7; color: #e2e2e2; opacity: 0.7;">{{ \Illuminate\Support\Str::limit(strip_tags($service->description), 120) }}</p>
                    @endif
                    @if($service->price)
                    <div style="margin-top: 24px; padding-top: 16px; border-top: 2px solid #444748;">
                        <span style="font-family: 'Space Mono', monospace; font-size: 10px; letter-spacing: 0.15em; text-transform: uppercase; color: #e2e2e2; opacity: 0.5; display: block; margin-bottom: 4px;">FROM</span>
                        <span style="font-family: 'Anton', sans-serif; font-size: 1.5rem; color: #72ff70;">{{ \App\Support\Money\Money::format($service->price, $business->currency_code) }}</span>
                    </div>
                    @endif
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif
