<section id="about" style="background: var(--color-background);">
    <div class="container" style="max-width: 800px; text-align: left;">
        <h2 class="section-title">About Us</h2>
        <blockquote style="font-size: 1.375rem; font-weight: 500; line-height: 1.6; color: var(--color-text); border-left: 6px solid var(--color-primary); padding-left: 2rem; margin-bottom: 2rem;">
            {{ $business->about ?: ($business->description ?: 'Welcome to ' . $business->name . '.') }}
        </blockquote>
        @if ($business->category)
            <span style="display: inline-block; padding: 0.375rem 1rem; border: 2px solid var(--color-text); font-family: var(--font-heading); font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; color: var(--color-text);">
                {{ $business->category->name }}
            </span>
        @endif
        @if ($business->latitude && $business->longitude)
            <div style="margin-top: 2rem; height: 300px; border-radius: 8px; overflow: hidden;">
                <div id="about-map-{{ $business->id }}" style="width: 100%; height: 100%;"></div>
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
        @endif
    </div>
</section>
