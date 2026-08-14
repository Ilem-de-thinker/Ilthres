{{-- Default theme · Store about — Serene Ivory --}}
<section id="about" style="padding: 96px 0; background: #fcf9f2;">
    <div style="max-width: 1280px; margin: 0 auto; padding: 0 64px; display: grid; grid-template-columns: repeat(12, 1fr); gap: 32px; align-items: start;">
        {{-- Left: Heading + About text --}}
        <div style="grid-column: 1 / 7;">
            <span class="section-label" style="color: #6c5e06; display: block; margin-bottom: 16px;">ABOUT US</span>
            <h2 style="font-family: 'Playfair Display', serif; font-style: italic; font-size: clamp(1.75rem, 3.5vw, 2.75rem); color: #1c1c18; margin-bottom: 24px; line-height: 1.15;">
                Our Story
            </h2>
            <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.9375rem; line-height: 1.8; color: #454843; margin-bottom: 24px;">
                {!! $business->about ?: ($business->description ?: 'Welcome to ' . $business->name . '.') !!}
            </div>
            @if ($business->category)
                <span style="display: inline-block; padding: 6px 16px; border: 0.5px solid #E5E0D5; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.6875rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #454843; border-radius: 0.25rem;">
                    {{ $business->category->name }}
                </span>
            @endif
        </div>

        {{-- Right: Map in thin-frame --}}
        <div style="grid-column: 7 / 13;">
            @if ($business->latitude && $business->longitude)
                <div class="thin-frame" style="overflow: hidden; border-radius: 0.5rem;">
                    <div id="about-map-{{ $business->id }}" style="width: 100%; height: 320px;"></div>
                </div>
                <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
                <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        var map = L.map('about-map-{{ $business->id }}').setView([{{ $business->latitude }}, {{ $business->longitude }}], 15);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '&copy; OpenStreetMap contributors'
                        }).addTo(map);
                        L.marker([{{ $business->latitude }}, {{ $business->longitude }}]).addTo(map);
                    });
                </script>
            @else
                <div class="thin-frame" style="padding: 48px; text-align: center; border-radius: 0.5rem;">
                    <span class="material-symbols-outlined" style="color: #c5c7c1; font-size: 48px; display: block; margin-bottom: 16px;">storefront</span>
                    <span style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.6875rem; letter-spacing: 0.05em; text-transform: uppercase; color: #757873;">Est. {{ date('Y') }}</span>
                </div>
            @endif
        </div>
    </div>
</section>
