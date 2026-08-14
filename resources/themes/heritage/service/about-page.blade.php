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
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&family=Hanken+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Hanken Grotesk', sans-serif; background: {{ $colors['background'] ?? '#fbf9f9' }}; color: {{ $colors['text'] ?? '#000000' }}; line-height: 1.6; -webkit-font-smoothing: antialiased; min-height: 100vh; display: flex; flex-direction: column; }
        main { flex: 1; }
        h1, h2, h3, h4 { font-family: 'Playfair Display', serif; font-weight: 600; }
        a { text-decoration: none; transition: all 0.2s ease; }
        ::selection { background: {{ $colors['secondary'] ?? '#735c00' }}; color: {{ $colors['background'] ?? '#fbf9f9' }}; }
        :root { --c-primary: {{ $colors['primary'] ?? '#000000' }}; --c-secondary: {{ $colors['secondary'] ?? '#735c00' }}; --c-bg: {{ $colors['background'] ?? '#fbf9f9' }}; --c-text: {{ $colors['text'] ?? '#000000' }}; --c-accent: {{ $colors['accent'] ?? '#fed65b' }}; --c-radius: {{ $radius ?? '0.125rem' }}; }
        .heritage-label { font-family: 'Hanken Grotesk', sans-serif; font-size: 0.6875rem; text-transform: uppercase; letter-spacing: 0.1em; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1eee7; }
        ::-webkit-scrollbar-thumb { background: #735c00; border-radius: 3px; }
        .about-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; align-items: start; }
        @media (max-width: 768px) { .about-grid { grid-template-columns: 1fr !important; padding: 0 24px !important; gap: 2rem !important; } }
        @media (max-width: 768px) { main > div { padding-left: 24px !important; padding-right: 24px !important; } }
    </style>
</head>
<body>
<div class="min-h-screen flex flex-col">
    @include('themes::heritage.service.header')

    <main class="flex-1" style="padding-top: 8rem; padding-bottom: 6rem;">
        <div style="max-width: 1280px; margin: 0 auto; padding: 0 64px;">
            <div style="text-align: center; margin-bottom: 4rem;">
                <a href="{{ route('business.site', $business) }}" style="display: inline-flex; align-items: center; gap: 8px; font-family: 'Hanken Grotesk', sans-serif; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; color: #735c00; text-decoration: none; margin-bottom: 2rem; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.6'" onmouseout="this.style.opacity='1'">
                    <span class="material-symbols-outlined" style="font-size: 16px;">arrow_back</span> Back to Profile
                </a>
                <div style="width: 32px; height: 0.5px; background: #735c00; margin: 0 auto 1.5rem;"></div>
                <h1 style="font-family: 'Playfair Display', serif; font-style: italic; font-weight: 500; font-size: 2.5rem; margin-bottom: 0.75rem;">About Us</h1>
                <span class="heritage-label" style="font-size: 0.6875rem; color: #735c00;">Our Expertise</span>
            </div>

            <div class="about-grid">
                <div>
                    <div style="width: 32px; height: 0.5px; background: #735c00; margin-bottom: 1.5rem;"></div>
                    <h2 style="font-family: 'Playfair Display', serif; font-weight: 600; font-size: 2.25rem; margin-bottom: 0.5rem; letter-spacing: -0.01em;">About Us</h2>
                    <span class="heritage-label" style="font-size: 0.6875rem; color: #735c00; display: block; margin-bottom: 2rem;">Our Expertise</span>
                    <p style="font-family: 'Hanken Grotesk', sans-serif; font-size: 1rem; line-height: 1.8; color: #444; margin-bottom: 1.5rem;">
                        {!! $business->about ?: ($business->description ?: 'Welcome to ' . $business->name . '.') !!}
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
                            <div id="about-map" style="width: 100%; height: 350px;"></div>
                        </div>
                        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
                        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
                        <script>
                            @if (!($animations ?? true))
                            document.addEventListener('DOMContentLoaded', function () {
                                var map = L.map('about-map').setView([{{ $business->latitude }}, {{ $business->longitude }}], 15);
                                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap contributors' }).addTo(map);
                                L.marker([{{ $business->latitude }}, {{ $business->longitude }}]).addTo(map);
                            });
                            @endif
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
        </div>
    </main>

    @include('themes::heritage.service.footer')
</div>
</body>
</html>
