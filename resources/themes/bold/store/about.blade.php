{{-- Bold theme · Store about — High-Impact Editorial --}}
<section id="about" style="background: #131313; border-bottom: 4px solid #ffffff; padding: 64px 0;">
    <div style="max-width: 1280px; margin: 0 auto; padding: 0 64px; display: grid; grid-template-columns: repeat(12, 1fr); gap: 24px; align-items: start;">
        {{-- Left: Heading + About text --}}
        <div style="grid-column: 1 / 8; border-right: 4px solid #ffffff; padding-right: 48px;">
            <div style="display: flex; align-items: baseline; justify-content: space-between; margin-bottom: 32px; padding-bottom: 16px; border-bottom: 4px solid #ffffff;">
                <h2 style="font-family: 'Anton', sans-serif; font-size: 2.5rem; color: #ffffff; text-transform: uppercase;">ABOUT_US</h2>
                <span style="font-family: 'Space Mono', monospace; font-size: 10px; letter-spacing: 0.15em; text-transform: uppercase; color: #e2e2e2; opacity: 0.6;">SECTION_01</span>
            </div>

            <blockquote style="font-family: 'Archivo Narrow', sans-serif; font-size: 1.25rem; line-height: 1.7; color: #e2e2e2; border-left: 6px solid #72ff70; padding-left: 24px; margin-bottom: 32px;">
                {{ $business->about ?: ($business->description ?: 'Welcome to ' . $business->name . '.') }}
            </blockquote>

            @if ($business->category)
                <span style="display: inline-block; padding: 8px 20px; border: 2px solid #ffffff; font-family: 'Space Mono', monospace; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.15em; color: #ffffff;">
                    {{ $business->category->name }}
                </span>
            @endif
        </div>

        {{-- Right: Stats / Info --}}
        <div style="grid-column: 8 / 13; padding-left: 24px;">
            @if ($business->latitude && $business->longitude)
                <div style="border: 4px solid #ffffff; overflow: hidden; margin-bottom: 24px;">
                    <div id="about-map-{{ $business->id }}" style="width: 100%; height: 280px;"></div>
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
                <div class="dot-pattern" style="border: 4px solid #ffffff; padding: 48px; text-align: center;">
                    <span class="material-symbols-outlined" style="color: #444748; font-size: 48px; display: block; margin-bottom: 16px;">storefront</span>
                    <span style="font-family: 'Space Mono', monospace; font-size: 10px; letter-spacing: 0.15em; text-transform: uppercase; color: #e2e2e2; opacity: 0.5;">ESTABLISHED {{ date('Y') }}</span>
                </div>
            @endif
        </div>
    </div>
</section>
