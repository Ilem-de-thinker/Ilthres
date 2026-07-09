<section id="about">
    <div class="container" style="max-width: 720px; text-align: center;">
        <h2 class="section-title">About</h2>
        <p style="font-size: 1.125rem; line-height: 1.8; color: {{ $colors['text'] }}99;">
            {{ $business->about ?: ($business->description ?: 'Welcome to ' . $business->name . '.') }}
        </p>
        @if ($business->category)
            <span style="display: inline-block; margin-top: 1.5rem; padding: 0.375rem 1rem; border-radius: 9999px; background: {{ $colors['primary'] }}15; color: var(--color-primary); font-size: 0.8125rem; font-weight: 600;">
                {{ $business->category->name }}
            </span>
        @endif
        @if ($business->latitude && $business->longitude)
            <div style="margin-top: 2rem; height: 300px; border-radius: 12px; overflow: hidden;">
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
