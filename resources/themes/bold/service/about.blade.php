{{-- Bold theme · Service about — High-Impact Editorial: 12-col grid --}}
<section id="about" style="padding: 80px 0; border-bottom: 4px solid #ffffff;">
    <div style="max-width: 1280px; margin: 0 auto; padding: 0 64px; display: grid; grid-template-columns: repeat(12, 1fr); gap: 64px;">
        <div style="grid-column: 1 / 6;">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 32px;">
                <span style="font-family: 'Space Mono', monospace; font-size: 10px; letter-spacing: 0.15em; text-transform: uppercase; color: #72ff70;">SECTION_02</span>
                <div style="flex: 1; height: 1px; background: #ffffff;"></div>
            </div>
            <h2 style="font-family: 'Anton', sans-serif; font-size: clamp(2rem, 4vw, 3.5rem); text-transform: uppercase; color: #ffffff; margin-bottom: 32px;">ABOUT<br>THE_BUSINESS</h2>
            @if($business->about)
                <div style="font-family: 'Archivo Narrow', sans-serif; font-size: 1rem; line-height: 1.8; color: #e2e2e2; opacity: 0.85;">{!! $business->about !!}</div>
            @endif
            @if($business->location)
                <div style="display: flex; align-items: center; gap: 12px; margin-top: 32px; padding-top: 24px; border-top: 2px solid #444748;">
                    <span class="material-symbols-outlined" style="color: #72ff70; font-size: 20px;">location_on</span>
                    <span style="font-family: 'Space Mono', monospace; font-size: 14px; color: #e2e2e2;">{{ $business->location }}</span>
                </div>
            @endif
        </div>
        <div style="grid-column: 7 / 13;">
            @if ($business->location && $business->lat && $business->lng)
                <div style="border: 4px solid #ffffff; height: 100%; min-height: 400px; position: relative;">
                    <div id="leaflet-map" data-lat="{{ $business->lat }}" data-lng="{{ $business->lng }}" style="width: 100%; height: 100%; min-height: 400px;"></div>
                    <div style="position: absolute; top: 0; left: 0; background: #ffffff; color: #131313; padding: 12px; font-family: 'Space Mono', monospace; font-size: 10px; letter-spacing: 0.15em; text-transform: uppercase; z-index: 10;">LOCATION_DATA</div>
                </div>
            @else
                <div class="dot-pattern" style="border: 4px solid #ffffff; height: 100%; min-height: 400px; display: flex; align-items: center; justify-content: center; position: relative;">
                    <div style="position: absolute; top: 0; left: 0; background: #ffffff; color: #131313; padding: 12px; font-family: 'Space Mono', monospace; font-size: 10px; letter-spacing: 0.15em; text-transform: uppercase;">LOCATION_DATA</div>
                    <div style="text-align: center;">
                        <span class="material-symbols-outlined" style="color: #444748; font-size: 48px; display: block; margin-bottom: 12px;">location_off</span>
                        <p style="font-family: 'Space Mono', monospace; font-size: 10px; letter-spacing: 0.15em; text-transform: uppercase; color: #e2e2e2; opacity: 0.5;">NO_LOCATION_DATA_AVAILABLE</p>
                    </div>
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
