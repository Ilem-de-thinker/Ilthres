<!DOCTYPE html>
<html lang="en" class="dark">
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
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Archivo+Narrow:wght@400;500;600;700&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Archivo Narrow', sans-serif; background: #131313; color: #e2e2e2; line-height: 1.6; -webkit-font-smoothing: antialiased; min-height: 100vh; display: flex; flex-direction: column; overflow-x: hidden; }
        main { flex: 1; }
        h1, h2, h3, h4 { font-family: 'Anton', sans-serif; text-transform: uppercase; letter-spacing: 0.01em; line-height: 1.05; }
        a { text-decoration: none; transition: all 0.15s ease; }
        ::selection { background: #72ff70; color: #002203; }
        .dot-pattern { background-image: radial-gradient(circle, #444748 1px, transparent 1px); background-size: 24px 24px; }
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #131313; }
        ::-webkit-scrollbar-thumb { background: #ffffff; border: 2px solid #131313; }
        .about-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 64px; }
        @media (max-width: 768px) { .about-grid { grid-template-columns: 1fr !important; gap: 32px !important; } }
        @media (max-width: 768px) { main > div { padding-left: 24px !important; padding-right: 24px !important; } }
    </style>
</head>
<body>
<div class="min-h-screen flex flex-col">
    @include('themes::bold.service.header')

    <main class="flex-1" style="padding-top: 96px; padding-bottom: 64px;">
        <div style="max-width: 1280px; margin: 0 auto; padding: 0 64px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-end; padding-bottom: 16px; border-bottom: 4px solid #ffffff; margin-bottom: 64px;">
                <h1 style="font-family: 'Anton', sans-serif; font-size: 3rem; color: #ffffff;">ABOUT_US</h1>
                <span style="font-family: 'Space Mono', monospace; font-size: 10px; text-transform: uppercase; letter-spacing: 0.15em; color: #e2e2e2; opacity: 0.6;">SECTION_02</span>
            </div>

            <div class="about-grid">
                <div>
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 32px;">
                        <span style="font-family: 'Space Mono', monospace; font-size: 10px; letter-spacing: 0.15em; text-transform: uppercase; color: #72ff70;">SECTION_02</span>
                        <div style="flex: 1; height: 1px; background: #ffffff;"></div>
                    </div>
                    <h2 style="font-family: 'Anton', sans-serif; font-size: clamp(2rem, 4vw, 3.5rem); text-transform: uppercase; color: #ffffff; margin-bottom: 32px;">ABOUT<br>THE_BUSINESS</h2>
                    <div style="font-family: 'Archivo Narrow', sans-serif; font-size: 1rem; line-height: 1.8; color: #e2e2e2; opacity: 0.85;">
                        {!! $business->about ?: ($business->description ?: 'Welcome to ' . $business->name . '.') !!}
                    </div>
                    @if ($business->category)
                        <span style="display: inline-block; margin-top: 32px; font-family: 'Space Mono', monospace; font-size: 10px; letter-spacing: 0.15em; text-transform: uppercase; color: #72ff70; border: 2px solid #72ff70; padding: 8px 16px;">
                            {{ $business->category->name }}
                        </span>
                    @endif
                    @if ($business->location)
                        <div style="display: flex; align-items: center; gap: 12px; margin-top: 32px; padding-top: 24px; border-top: 2px solid #444748;">
                            <span class="material-symbols-outlined" style="color: #72ff70; font-size: 20px;">location_on</span>
                            <span style="font-family: 'Space Mono', monospace; font-size: 14px; color: #e2e2e2;">{{ $business->location }}</span>
                        </div>
                    @endif
                </div>
                <div>
                    @if ($business->latitude && $business->longitude)
                        <div style="border: 4px solid #ffffff; height: 100%; min-height: 400px; position: relative;">
                            <div id="about-map" style="width: 100%; height: 100%; min-height: 400px;"></div>
                            <div style="position: absolute; top: 0; left: 0; background: #ffffff; color: #131313; padding: 12px; font-family: 'Space Mono', monospace; font-size: 10px; letter-spacing: 0.15em; text-transform: uppercase; z-index: 10;">LOCATION_DATA</div>
                        </div>
                        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
                        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
                        <script>
                            @if (!($animations ?? true))
                            document.addEventListener('DOMContentLoaded', function () {
                                var el = document.getElementById('about-map');
                                if (!el) return;
                                var lat = {{ $business->latitude }};
                                var lng = {{ $business->longitude }};
                                var map = L.map(el).setView([lat, lng], 15);
                                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                    attribution: '&copy; OpenStreetMap contributors'
                                }).addTo(map);
                                L.marker([lat, lng]).addTo(map);
                            });
                            @endif
                        </script>
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
        </div>
    </main>

    @include('themes::bold.service.footer')
</div>
</body>
</html>
