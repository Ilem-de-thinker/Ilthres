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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: linear-gradient(135deg, #f0f2ff 0%, #e8f4ff 50%, #fff 100%); color: #1a1a2e; line-height: 1.6; -webkit-font-smoothing: antialiased; min-height: 100vh; display: flex; flex-direction: column; overflow-x: hidden; }
        main { flex: 1; }
        h1, h2, h3, h4 { font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; letter-spacing: -0.025em; line-height: 1.1; }
        a { text-decoration: none; transition: all 0.25s ease; }
        ::selection { background: #4648d4; color: #ffffff; }
        .glass { background: rgba(255,255,255,0.7); backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.2); border-radius: 16px; }
        .glass-card { background: rgba(255,255,255,0.7); backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.2); border-radius: 16px; transition: all 0.3s ease; }
        .label { font-size: 0.8125rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #4648d4; border-radius: 3px; }
        .about-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 64px; align-items: start; }
        @media (max-width: 768px) { .about-grid { grid-template-columns: 1fr !important; gap: 32px !important; } }
        @media (max-width: 768px) { main > div { padding-left: 24px !important; padding-right: 24px !important; } }
    </style>
</head>
<body>
<div class="min-h-screen flex flex-col">
    @include('themes::modern.service.header')

    <main class="flex-1" style="padding-top: 96px; padding-bottom: 64px;">
        <div style="max-width: 1280px; margin: 0 auto; padding: 0 40px;">
            <div style="margin-bottom: 48px;">
                <a href="{{ route('business.site', $business) }}" style="display: inline-flex; align-items: center; gap: 6px; margin-bottom: 24px; font-size: 0.875rem; font-weight: 500; color: #4648d4; text-decoration: none;" onmouseover="this.style.color='#006591'" onmouseout="this.style.color='#4648d4'">
                    <span class="material-symbols-outlined" style="font-size: 16px;">arrow_back</span> Back to Profile
                </a>
                <div>
                    <span class="label" style="color: #4648d4; margin-bottom: 8px; display: block;">About</span>
                    <h1 style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: clamp(2rem, 4vw, 3rem); letter-spacing: -0.03em; color: #1a1a2e;">About Us</h1>
                </div>
            </div>

            <div class="about-grid">
                <div>
                    <h2 style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: clamp(2rem, 3.5vw, 3rem); letter-spacing: -0.03em; line-height: 1.1; color: #1a1a2e; margin-bottom: 24px;">
                        {{ $business->name }}
                    </h2>
                    <div class="glass-card" style="padding: 32px;">
                        <p style="font-size: 1rem; line-height: 1.8; color: #4b5563;">
                            {!! $business->about ?: ($business->description ?: 'Welcome to ' . $business->name . '.') !!}
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

                <div>
                    @if ($business->latitude && $business->longitude)
                        <div class="glass" style="overflow: hidden;">
                            <div id="about-map" style="width: 100%; height: 360px;"></div>
                        </div>
                        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
                        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
                        <script>
                            @if (!($animations ?? true))
                            document.addEventListener('DOMContentLoaded', function () {
                                var map = L.map('about-map').setView([{{ $business->latitude }}, {{ $business->longitude }}], 15);
                                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                    attribution: '&copy; OpenStreetMap contributors'
                                }).addTo(map);
                                L.marker([{{ $business->latitude }}, {{ $business->longitude }}]).addTo(map);
                            });
                            @endif
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
    </main>

    @include('themes::modern.service.footer')
</div>
</body>
</html>
