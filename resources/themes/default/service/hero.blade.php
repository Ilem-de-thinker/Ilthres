{{--
  Default theme · Service hero — cover image, tagline, and primary CTA.
--}}
<section style="background: linear-gradient(135deg, {{ $colors['primary'] }}07, {{ $colors['secondary'] }}07); border-bottom: 1px solid rgba(0,0,0,0.04);">
    <div class="container" style="display: flex; align-items: center; gap: 4rem; min-height: 55vh; padding-top: 3rem; padding-bottom: 3rem; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 320px;">
            <h1 style="font-size: 3rem; font-weight: 700; line-height: 1.15; margin-bottom: 1.25rem; letter-spacing: -0.01em;">
                {{ $business->name }}
            </h1>
            @if ($business->description)
                <p style="font-size: 1.125rem; color: rgba(0,0,0,0.65); line-height: 1.6; margin-bottom: 2.25rem;">
                    {{ $business->description }}
                </p>
            @endif
            <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                <a href="#services" class="btn btn-primary">Our Services</a>
                <a href="#contact" class="btn btn-outline">Contact Us</a>
            </div>
        </div>
        @if ($business->cover)
            <div style="flex: 1; min-width: 320px; display: flex; justify-content: center;">
                <img src="{{ Storage::url($business->cover) }}" alt="{{ $business->name }}"
                     style="border-radius: var(--radius); box-shadow: 0 10px 30px rgba(0,0,0,0.06); width: 100%; max-width: 500px; height: 320px; object-fit: cover;">
            </div>
        @endif
    </div>
</section>
