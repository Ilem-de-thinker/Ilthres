{{-- Heritage theme · Service services listing --}}
<section id="services" style="padding: 96px 0; border-top: 0.5px solid #d4d0c8;">
    <div style="max-width: 1280px; margin: 0 auto; padding: 0 64px;">
        <div style="text-align: center; margin-bottom: 4rem;">
            <div style="width: 32px; height: 0.5px; background: #735c00; margin: 0 auto 1.5rem;"></div>
            <h2 style="font-family: 'Playfair Display', serif; font-weight: 600; font-size: 2rem; margin-bottom: 0.75rem; letter-spacing: -0.01em;">Our Services</h2>
            <span class="heritage-label" style="font-size: 0.6875rem; color: #735c00;">Professional Excellence</span>
        </div>

        @if ($services && $services->isNotEmpty())
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                @foreach($services as $service)
                <a href="{{ route('business.service', [$business, $service]) }}"
                   style="display: flex; flex-direction: column; justify-content: space-between; padding: 2rem; border: 0.5px solid #d4d0c8; transition: all 0.3s; text-decoration: none; overflow: hidden;"
                   onmouseover="this.style.borderColor='#735c00';"
                   onmouseout="this.style.borderColor='#d4d0c8';">
                    <div>
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                            <span class="heritage-label" style="font-size: 0.5625rem; color: #735c00;">{{ $service->category?->name ?? 'Service' }}</span>
                            @if($service->duration_minutes)
                                <span class="heritage-label" style="font-size: 0.5625rem; color: #888;">{{ $service->duration_minutes }} min</span>
                            @endif
                        </div>
                        <h3 style="font-family: 'Playfair Display', serif; font-size: 1.125rem; font-weight: 600; margin-bottom: 0.75rem; line-height: 1.3; color: var(--c-text);">{{ $service->name }}</h3>
                        @if($service->description)
                            <p style="font-family: 'Hanken Grotesk', sans-serif; font-size: 0.8125rem; color: #888; margin-bottom: 1.5rem; line-height: 1.6;">{{ Str::limit($service->description, 120) }}</p>
                        @endif
                    </div>
                    <div style="border-top: 0.5px solid #d4d0c8; padding-top: 1.25rem; display: flex; align-items: center; justify-content: space-between;">
                        <div>
                            @if($service->price)
                                <span style="font-family: 'Hanken Grotesk', sans-serif; font-weight: 600; font-size: 1rem; color: #735c00;">${{ number_format($service->price, 2) }}</span>
                            @else
                                <span class="heritage-label" style="font-size: 0.6875rem; color: #bbb;">Contact for quote</span>
                            @endif
                        </div>
                        <span class="heritage-label" style="font-size: 0.6875rem; color: #735c00;">Details &rarr;</span>
                    </div>
                </a>
                @endforeach
            </div>
        @else
            <div style="text-align: center; padding: 4rem 0; border: 0.5px solid #d4d0c8;">
                <span class="material-symbols-outlined" style="font-size: 48px; color: #d4d0c8; display: block; margin-bottom: 1rem;">design_services</span>
                <p style="font-family: 'Hanken Grotesk', sans-serif; font-size: 0.875rem; color: #999;">No services listed yet.</p>
            </div>
        @endif
    </div>
</section>
