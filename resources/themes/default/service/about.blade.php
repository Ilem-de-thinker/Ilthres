{{-- Default theme · Service about — Serene Ivory --}}
<section id="about" style="padding: 96px 0; background: #fcf9f2;">
    <div style="max-width: 1280px; margin: 0 auto; padding: 0 64px; display: grid; grid-template-columns: repeat(12, 1fr); gap: 32px; align-items: start;">
        <div style="grid-column: 1 / 7;">
            <span class="section-label" style="color: #6c5e06; display: block; margin-bottom: 16px;">ABOUT US</span>
            <h2 style="font-family: 'Playfair Display', serif; font-style: italic; font-size: clamp(1.75rem, 3.5vw, 2.75rem); color: #1c1c18; margin-bottom: 24px; line-height: 1.15;">
                Our Story
            </h2>
            <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.9375rem; line-height: 1.8; color: #454843; margin-bottom: 24px;">
                {!! $business->about ?: ($business->description ?: 'Welcome to ' . $business->name . '.') !!}
            </div>
            @if ($business->location)
                <div style="display: flex; align-items: center; gap: 10px; margin-top: 24px; padding-top: 20px; border-top: 0.5px solid #E5E0D5;">
                    <span class="material-symbols-outlined" style="color: #6c5e06; font-size: 18px;">location_on</span>
                    <span style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.875rem; color: #454843;">{{ $business->location }}</span>
                </div>
            @endif
        </div>
        <div style="grid-column: 7 / 13;">
            @if ($business->location && $business->lat && $business->lng)
                <div class="thin-frame" style="overflow: hidden; border-radius: 0.5rem;">
                    <div id="leaflet-map" data-lat="{{ $business->lat }}" data-lng="{{ $business->lng }}" style="width: 100%; height: 360px;"></div>
                </div>
            @else
                <div class="thin-frame" style="padding: 48px; text-align: center; border-radius: 0.5rem;">
                    <span class="material-symbols-outlined" style="color: #c5c7c1; font-size: 48px; display: block; margin-bottom: 16px;">storefront</span>
                    <span style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.6875rem; letter-spacing: 0.05em; text-transform: uppercase; color: #757873;">Est. {{ date('Y') }}</span>
                </div>
            @endif
        </div>
    </div>
</section>

@push('scripts')
    @if ($business->location && $business->lat && $business->lng)
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var el = document.getElementById('leaflet-map');
                if (!el) return;
                var lat = parseFloat(el.dataset.lat);
                var lng = parseFloat(el.dataset.lng);
                if (isNaN(lat) || isNaN(lng)) return;
                var map = L.map(el).setView([lat, lng], 15);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);
                L.marker([lat, lng]).addTo(map);
            });
        </script>
    @endif
@endpush
