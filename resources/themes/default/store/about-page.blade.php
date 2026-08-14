<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $business->name }} — About Us</title>
    @if (!empty($business))
        <meta name="description" content="{{ Str::limit(strip_tags($business->description ?? $business->about ?? $business->name), 160) }}">
    @endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400;1,700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #fcf9f2; color: #1c1c18; line-height: 1.6; -webkit-font-smoothing: antialiased; min-height: 100vh; display: flex; flex-direction: column; overflow-x: hidden; }
        main { flex: 1; }
        h1, h2, h3, h4 { font-family: 'Playfair Display', serif; line-height: 1.15; }
        a { text-decoration: none; transition: all 0.2s ease; }
        input, textarea, select { font-family: 'Plus Jakarta Sans', sans-serif; }
        ::selection { background: #6c5e06; color: #fff; }
        .thin-frame { padding: 16px; border: 0.5px solid #E5E0D5; }
        .subtle-shadow { box-shadow: 0px 10px 40px rgba(0,0,0,0.03); }
        .section-label { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.6875rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1eee7; }
        ::-webkit-scrollbar-thumb { background: #c5c7c1; border-radius: 3px; }
        @media (max-width: 768px) {
            .about-grid { grid-template-columns: 1fr !important; }
            .about-grid > div { grid-column: 1 / -1 !important; }
            .stats-grid { grid-template-columns: repeat(2, 1fr) !important; }
            .values-grid { grid-template-columns: 1fr !important; }
        }
    </style>
</head>
<body>
<div class="min-h-screen flex flex-col">
    @include('themes::default.store.header')

    <main class="flex-1" style="padding-top: 112px; padding-bottom: 96px;">
        <div style="max-width: 1280px; margin: 0 auto; padding: 0 64px;">
            {{-- Page Header --}}
            <div style="margin-bottom: 64px;">
                <a href="{{ route('business.site', $business) }}" style="display: inline-flex; align-items: center; gap: 6px; margin-bottom: 24px; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #454843; text-decoration: none;" onmouseover="this.style.color='#6c5e06'" onmouseout="this.style.color='#454843'">
                    <span class="material-symbols-outlined" style="font-size: 16px;">arrow_back</span> Back to Profile
                </a>
                <h1 style="font-family: 'Playfair Display', serif; font-style: italic; font-size: clamp(2rem, 4vw, 3rem); color: #1c1c18;">About Us</h1>
            </div>

            {{-- Main About Section --}}
            <div class="about-grid" style="display: grid; grid-template-columns: repeat(12, 1fr); gap: 32px; align-items: start; margin-bottom: 80px;">
                {{-- Left: Heading + About text --}}
                <div style="grid-column: 1 / 7;">
                    <span class="section-label" style="color: #6c5e06; display: block; margin-bottom: 16px;">OUR STORY</span>
                    <h2 style="font-family: 'Playfair Display', serif; font-style: italic; font-size: clamp(1.75rem, 3.5vw, 2.75rem); color: #1c1c18; margin-bottom: 24px; line-height: 1.15;">
                        {{ $business->name }}
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
                                L.marker([{{ $business->latitude }}, {{ $business->longitude }}]).addTo(map)
                                    .bindPopup('{{ addslashes($business->name) }}').openPopup();
                            });
                        </script>
                    @else
                        <div class="thin-frame" style="padding: 48px; text-align: center; border-radius: 0.5rem; min-height: 360px; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                            <span class="material-symbols-outlined" style="color: #c5c7c1; font-size: 48px; display: block; margin-bottom: 16px;">storefront</span>
                            <span style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.6875rem; letter-spacing: 0.05em; text-transform: uppercase; color: #757873;">Est. {{ date('Y') }}</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Business Stats --}}
            @if(!($animations ?? true)) @else
            <div style="margin-bottom: 80px;">
                <span class="section-label" style="color: #6c5e06; display: block; margin-bottom: 24px;">BY THE NUMBERS</span>
                <div class="stats-grid" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px;">
                    @if($business->products_count ?? $business->products->count() ?? false)
                    <div class="thin-frame subtle-shadow" style="padding: 32px; border-radius: 0.5rem; text-align: center;">
                        <div style="font-family: 'Playfair Display', serif; font-size: 2rem; color: #1c1c18; margin-bottom: 4px;">{{ $business->products_count ?? $business->products->count() }}</div>
                        <div class="section-label" style="color: #757873;">Products</div>
                    </div>
                    @endif
                    <div class="thin-frame subtle-shadow" style="padding: 32px; border-radius: 0.5rem; text-align: center;">
                        <div style="font-family: 'Playfair Display', serif; font-size: 2rem; color: #1c1c18; margin-bottom: 4px;">{{ $business->created_at ? $business->created_at->diffInYears(now()) : date('Y') - date('Y') + 1 }}</div>
                        <div class="section-label" style="color: #757873;">Years Active</div>
                    </div>
                    @if($business->location)
                    <div class="thin-frame subtle-shadow" style="padding: 32px; border-radius: 0.5rem; text-align: center;">
                        <div style="font-family: 'Playfair Display', serif; font-size: 2rem; color: #1c1c18; margin-bottom: 4px;">1</div>
                        <div class="section-label" style="color: #757873;">Location</div>
                    </div>
                    @endif
                    @if($business->portfolio && count($business->portfolio) > 0)
                    <div class="thin-frame subtle-shadow" style="padding: 32px; border-radius: 0.5rem; text-align: center;">
                        <div style="font-family: 'Playfair Display', serif; font-size: 2rem; color: #1c1c18; margin-bottom: 4px;">{{ count($business->portfolio) }}</div>
                        <div class="section-label" style="color: #757873;">Projects</div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Our Values --}}
            <div class="values-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; margin-bottom: 80px;">
                <div style="grid-column: 1 / -1; margin-bottom: 8px;">
                    <span class="section-label" style="color: #6c5e06; display: block; margin-bottom: 8px;">OUR VALUES</span>
                    <h2 style="font-family: 'Playfair Display', serif; font-style: italic; font-size: 1.75rem; color: #1c1c18;">What Drives Us</h2>
                </div>

                <div class="thin-frame subtle-shadow" style="padding: 40px; border-radius: 0.5rem;">
                    <span class="material-symbols-outlined" style="color: #6c5e06; font-size: 28px; display: block; margin-bottom: 16px;">quality</span>
                    <h3 style="font-family: 'Playfair Display', serif; font-size: 1.125rem; color: #1c1c18; margin-bottom: 12px;">Quality First</h3>
                    <p style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.875rem; line-height: 1.7; color: #5e5f5c;">We never compromise on the quality of our products and services. Every detail matters.</p>
                </div>

                <div class="thin-frame subtle-shadow" style="padding: 40px; border-radius: 0.5rem;">
                    <span class="material-symbols-outlined" style="color: #6c5e06; font-size: 28px; display: block; margin-bottom: 16px;">handshake</span>
                    <h3 style="font-family: 'Playfair Display', serif; font-size: 1.125rem; color: #1c1c18; margin-bottom: 12px;">Trust & Integrity</h3>
                    <p style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.875rem; line-height: 1.7; color: #5e5f5c;">We build lasting relationships through transparency, honesty, and genuine care for our customers.</p>
                </div>

                <div class="thin-frame subtle-shadow" style="padding: 40px; border-radius: 0.5rem;">
                    <span class="material-symbols-outlined" style="color: #6c5e06; font-size: 28px; display: block; margin-bottom: 16px;">lightbulb</span>
                    <h3 style="font-family: 'Playfair Display', serif; font-size: 1.125rem; color: #1c1c18; margin-bottom: 12px;">Innovation</h3>
                    <p style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.875rem; line-height: 1.7; color: #5e5f5c;">We constantly evolve and adapt, bringing fresh ideas and modern solutions to everything we do.</p>
                </div>
            </div>

            {{-- Full-width Map --}}
            @if($business->latitude && $business->longitude)
            <div>
                <span class="section-label" style="color: #6c5e06; display: block; margin-bottom: 16px;">FIND US</span>
                <div class="thin-frame" style="overflow: hidden; border-radius: 0.5rem;">
                    <div id="about-page-map" style="width: 100%; height: 400px;"></div>
                </div>
                <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
                <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        var map = L.map('about-page-map').setView([{{ $business->latitude }}, {{ $business->longitude }}], 15);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '&copy; OpenStreetMap contributors'
                        }).addTo(map);
                        L.marker([{{ $business->latitude }}, {{ $business->longitude }}]).addTo(map)
                            .bindPopup('{{ addslashes($business->name) }}').openPopup();
                    });
                </script>
            </div>
            @endif
        </div>
    </main>

    @include('themes::default.store.footer')
</div>
</body>
</html>
