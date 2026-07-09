<section id="about">
    <div class="container">
        <h2 class="section-title">About</h2>
        <div style="max-width: 720px;">
            <p style="font-size: 1.0625rem; line-height: 1.7; color: {{ $colors['text'] }}cc;">
                {{ $business->about ?: ($business->description ?: 'Welcome to ' . $business->name . '.') }}
            </p>
            @if ($business->category)
                <p style="margin-top: 1rem; font-size: 0.875rem; color: {{ $colors['text'] }}80;">
                    Category: {{ $business->category->name }}
                </p>
            @endif
        </div>
        @if ($business->latitude && $business->longitude)
            <div style="margin-top: 2rem; max-width: 720px; height: 300px; border-radius: 8px; overflow: hidden;">
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
