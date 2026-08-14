{{-- Modern theme · Store about — Lumina SaaS glass about section --}}
<section id="about" style="padding: 96px 0 128px;">
    <div style="max-width: 1280px; margin: 0 auto; padding: 0 40px;">
        <div style="display: grid; grid-template-columns: repeat(12, 1fr); gap: 24px; align-items: start;">
            <div style="grid-column: 1 / 7;">
                <span class="label" style="color: #4648d4; margin-bottom: 12px; display: block;">About</span>
                <h2 style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: clamp(2rem, 3.5vw, 3rem); letter-spacing: -0.03em; line-height: 1.1; color: #1a1a2e; margin-bottom: 24px;">
                    {{ $business->name }}
                </h2>
                <div class="glass-card" style="padding: 32px;">
                    <p style="font-size: 1rem; line-height: 1.8; color: #4b5563;">
                        {{ $business->about ?: ($business->description ?: 'Welcome to ' . $business->name . '.') }}
                    </p>
                </div>
                @if ($business->category || $business->location)
                    <div style="display: flex; flex-wrap: wrap; gap: 12px; margin-top: 24px;">
                        @if ($business->category)
                            <span style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; background: rgba(70,72,212,0.08); color: #4648d4; border-radius: 999px; font-size: 0.8125rem; font-weight: 600;">
                                <span class="material-symbols-outlined" style="font-size: 14px;">category</span>
                                {{ $business->category->name }}
                            </span>
                        @endif
                        @if ($business->location)
                            <span style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; background: rgba(0,101,145,0.08); color: #006591; border-radius: 999px; font-size: 0.8125rem; font-weight: 600;">
                                <span class="material-symbols-outlined" style="font-size: 14px;">location_on</span>
                                {{ $business->location }}
                            </span>
                        @endif
                    </div>
                @endif
            </div>

            <div style="grid-column: 7 / 13;">
                @if ($business->latitude && $business->longitude)
                    <div class="glass" style="overflow: hidden;">
                        <div id="about-map-{{ $business->id }}" style="width: 100%; height: 360px;"></div>
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
                    <div class="glass" style="height: 360px; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                        <span class="material-symbols-outlined" style="color: #4648d4; font-size: 48px; margin-bottom: 12px;">map</span>
                        <span style="font-size: 0.875rem; color: #9ca3af;">Location not available</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
