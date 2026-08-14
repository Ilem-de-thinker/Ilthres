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
        ::selection { background: #6c5e06; color: #fff; }
        .thin-frame { padding: 16px; border: 0.5px solid #E5E0D5; }
        .subtle-shadow { box-shadow: 0px 10px 40px rgba(0,0,0,0.03); }
        .section-label { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.6875rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1eee7; }
        ::-webkit-scrollbar-thumb { background: #c5c7c1; border-radius: 3px; }
        .about-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 32px; align-items: start; }
        @media (max-width: 768px) { .about-grid { grid-template-columns: 1fr !important; } }
        @media (max-width: 768px) { main > div { padding-left: 24px !important; padding-right: 24px !important; } }
    </style>
</head>
<body>
<div class="min-h-screen flex flex-col">
    @include('themes::default.service.header')

    <main class="flex-1" style="padding-top: 112px; padding-bottom: 96px;">
        <div style="max-width: 1280px; margin: 0 auto; padding: 0 64px;">
            <div style="margin-bottom: 64px;">
                <a href="{{ route('business.site', $business) }}" style="display: inline-flex; align-items: center; gap: 6px; margin-bottom: 24px; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #454843; text-decoration: none;" onmouseover="this.style.color='#6c5e06'" onmouseout="this.style.color='#454843'">
                    <span class="material-symbols-outlined" style="font-size: 16px;">arrow_back</span> Back to Profile
                </a>
                <h1 style="font-family: 'Playfair Display', serif; font-style: italic; font-size: clamp(2rem, 4vw, 3rem); color: #1c1c18;">About Us</h1>
            </div>

            <div class="about-grid">
                <div>
                    <span class="section-label" style="color: #6c5e06; display: block; margin-bottom: 16px;">ABOUT US</span>
                    <h2 style="font-family: 'Playfair Display', serif; font-style: italic; font-size: clamp(1.75rem, 3.5vw, 2.75rem); color: #1c1c18; margin-bottom: 24px; line-height: 1.15;">
                        Our Story
                    </h2>
                    <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.9375rem; line-height: 1.8; color: #454843; margin-bottom: 24px;">
                        {!! $business->about ?: ($business->description ?: 'Welcome to ' . $business->name . '.') !!}
                    </div>
                    @if ($business->category)
                        <span style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; border: 0.5px solid #6c5e06; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.6875rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #6c5e06;">
                            {{ $business->category->name }}
                        </span>
                    @endif
                    @if ($business->location)
                        <div style="display: flex; align-items: center; gap: 10px; margin-top: 24px; padding-top: 20px; border-top: 0.5px solid #E5E0D5;">
                            <span class="material-symbols-outlined" style="color: #6c5e06; font-size: 18px;">location_on</span>
                            <span style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.875rem; color: #454843;">{{ $business->location }}</span>
                        </div>
                    @endif
                </div>
                <div>
                    @if ($business->latitude && $business->longitude)
                        <div class="thin-frame" style="overflow: hidden; border-radius: 0.5rem;">
                            <div id="about-map" style="width: 100%; height: 360px;"></div>
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
                        <div class="thin-frame" style="padding: 48px; text-align: center; border-radius: 0.5rem;">
                            <span class="material-symbols-outlined" style="color: #c5c7c1; font-size: 48px; display: block; margin-bottom: 16px;">storefront</span>
                            <span style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.6875rem; letter-spacing: 0.05em; text-transform: uppercase; color: #757873;">Est. {{ date('Y') }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>

    @include('themes::default.service.footer')
</div>
</body>
</html>
