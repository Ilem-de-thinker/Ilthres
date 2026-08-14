{{-- Heritage theme · Service about --}}
<section id="about" style="padding: 96px 0;">
    <div style="max-width: 1280px; margin: 0 auto; padding: 0 64px; display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; align-items: start;">
        <div>
            <div style="width: 32px; height: 0.5px; background: #735c00; margin-bottom: 1.5rem;"></div>
            <h2 style="font-family: 'Playfair Display', serif; font-weight: 600; font-size: 2.25rem; margin-bottom: 0.5rem; letter-spacing: -0.01em;">About Us</h2>
            <span class="heritage-label" style="font-size: 0.6875rem; color: #735c00; display: block; margin-bottom: 2rem;">Our Expertise</span>
            <p style="font-family: 'Hanken Grotesk', sans-serif; font-size: 1rem; line-height: 1.8; color: #444; margin-bottom: 1.5rem;">
                {{ $business->about ?: ($business->description ?: 'Welcome to ' . $business->name . '.') }}
            </p>
            @if ($business->category)
                <span class="heritage-label" style="font-size: 0.6875rem; color: #735c00; padding: 0.375rem 1rem; border: 0.5px solid #735c00; display: inline-block;">
                    {{ $business->category->name }}
                </span>
            @endif
        </div>
        <div style="position: relative;">
            <div style="width: 0.5px; height: 100%; background: #d4d0c8; position: absolute; left: -2rem; top: 0;"></div>
            @if ($business->latitude && $business->longitude)
                <div style="border: 0.5px solid #d4d0c8; padding: 16px;">
                    <div id="about-map-{{ $business->id }}" style="width: 100%; height: 350px;"></div>
                </div>
                <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
                <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        var map = L.map('about-map-{{ $business->id }}').setView([{{ $business->latitude }}, {{ $business->longitude }}], 15);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap contributors' }).addTo(map);
                        L.marker([{{ $business->latitude }}, {{ $business->longitude }}]).addTo(map);
                    });
                </script>
            @else
                <div style="border: 0.5px solid #d4d0c8; padding: 16px; display: flex; align-items: center; justify-content: center; min-height: 350px; background: #f5f3ef;">
                    <div style="text-align: center;">
                        <span class="material-symbols-outlined" style="font-size: 48px; color: #d4d0c8;">location_on</span>
                        <p style="font-family: 'Hanken Grotesk', sans-serif; font-size: 0.8125rem; color: #999; margin-top: 1rem; text-transform: uppercase; letter-spacing: 0.1em;">Location</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>

<style>
    @media (max-width: 768px) {
        #about > div { grid-template-columns: 1fr !important; padding: 0 24px !important; gap: 2rem !important; }
    }
</style>
