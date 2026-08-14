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
        @media (max-width: 768px) {
            .about-grid { grid-template-columns: 1fr !important; }
            .about-grid > div { grid-column: 1 / -1 !important; border-right: none !important; padding-right: 0 !important; padding-left: 0 !important; border-left: none !important; }
            .stats-grid { grid-template-columns: repeat(2, 1fr) !important; }
            .values-grid { grid-template-columns: 1fr !important; }
        }
    </style>
</head>
<body>
<div class="min-h-screen flex flex-col">
    @include('themes::bold.store.header')

    <main class="flex-1" style="padding-top: 112px; padding-bottom: 64px;">
        <div style="max-width: 1280px; margin: 0 auto; padding: 0 64px;">
            {{-- Page Header --}}
            <div style="margin-bottom: 64px;">
                <a href="{{ route('business.site', $business) }}" style="display: inline-flex; align-items: center; gap: 6px; margin-bottom: 24px; font-family: 'Space Mono', monospace; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.15em; color: #e2e2e2; text-decoration: none;" onmouseover="this.style.color='#72ff70'" onmouseout="this.style.color='#e2e2e2'">
                    <span class="material-symbols-outlined" style="font-size: 16px;">arrow_back</span> BACK_TO_PROFILE
                </a>
                <div style="display: flex; justify-content: space-between; align-items: flex-end; padding-bottom: 16px; border-bottom: 4px solid #ffffff;">
                    <h1 style="font-family: 'Anton', sans-serif; font-size: 3rem; color: #ffffff;">ABOUT_US</h1>
                    <span style="font-family: 'Space Mono', monospace; font-size: 10px; text-transform: uppercase; letter-spacing: 0.15em; color: #e2e2e2; opacity: 0.6;">SECTION_01</span>
                </div>
            </div>

            {{-- Main About Section --}}
            <div class="about-grid" style="display: grid; grid-template-columns: repeat(12, 1fr); gap: 24px; align-items: start; margin-bottom: 80px;">
                {{-- Left: Heading + About text --}}
                <div style="grid-column: 1 / 8; border-right: 4px solid #ffffff; padding-right: 48px;">
                    <h2 style="font-family: 'Anton', sans-serif; font-size: 2.5rem; color: #ffffff; margin-bottom: 32px;">OUR STORY</h2>

                    <blockquote style="font-family: 'Archivo Narrow', sans-serif; font-size: 1.25rem; line-height: 1.7; color: #e2e2e2; border-left: 6px solid #72ff70; padding-left: 24px; margin-bottom: 32px;">
                        {!! $business->about ?: ($business->description ?: 'Welcome to ' . $business->name . '.') !!}
                    </blockquote>

                    @if ($business->category)
                        <span style="display: inline-block; padding: 8px 20px; border: 2px solid #ffffff; font-family: 'Space Mono', monospace; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.15em; color: #ffffff;">
                            {{ $business->category->name }}
                        </span>
                    @endif
                </div>

                {{-- Right: Map --}}
                <div style="grid-column: 8 / 13; padding-left: 24px;">
                    @if ($business->latitude && $business->longitude)
                        <div style="border: 4px solid #ffffff; overflow: hidden; margin-bottom: 24px;">
                            <div id="about-map-{{ $business->id }}" style="width: 100%; height: 320px;"></div>
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
                        <div class="dot-pattern" style="border: 4px solid #ffffff; padding: 48px; text-align: center; min-height: 320px; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                            <span class="material-symbols-outlined" style="color: #444748; font-size: 48px; display: block; margin-bottom: 16px;">storefront</span>
                            <span style="font-family: 'Space Mono', monospace; font-size: 10px; letter-spacing: 0.15em; text-transform: uppercase; color: #e2e2e2; opacity: 0.5;">ESTABLISHED {{ date('Y') }}</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Stats --}}
            @if(!($animations ?? true)) @else
            <div style="margin-bottom: 80px; padding: 40px 0; border-top: 4px solid #ffffff; border-bottom: 4px solid #ffffff;">
                <span style="font-family: 'Space Mono', monospace; font-size: 10px; font-weight: 700; letter-spacing: 0.15em; text-transform: uppercase; color: #e2e2e2; opacity: 0.6; display: block; margin-bottom: 24px;">BY THE NUMBERS</span>
                <div class="stats-grid" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px;">
                    @if($business->products_count ?? $business->products->count() ?? false)
                    <div style="border: 4px solid #ffffff; padding: 32px; text-align: center;">
                        <div style="font-family: 'Anton', sans-serif; font-size: 2.5rem; color: #72ff70;">{{ $business->products_count ?? $business->products->count() }}</div>
                        <div style="font-family: 'Space Mono', monospace; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.15em; color: #e2e2e2; opacity: 0.6;">Products</div>
                    </div>
                    @endif
                    <div style="border: 4px solid #ffffff; padding: 32px; text-align: center;">
                        <div style="font-family: 'Anton', sans-serif; font-size: 2.5rem; color: #72ff70;">{{ $business->created_at ? $business->created_at->diffInYears(now()) : 1 }}</div>
                        <div style="font-family: 'Space Mono', monospace; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.15em; color: #e2e2e2; opacity: 0.6;">Years Active</div>
                    </div>
                    @if($business->location)
                    <div style="border: 4px solid #ffffff; padding: 32px; text-align: center;">
                        <div style="font-family: 'Anton', sans-serif; font-size: 2.5rem; color: #ffffff;">1</div>
                        <div style="font-family: 'Space Mono', monospace; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.15em; color: #e2e2e2; opacity: 0.6;">Location</div>
                    </div>
                    @endif
                    @if($business->portfolio && count($business->portfolio) > 0)
                    <div style="border: 4px solid #ffffff; padding: 32px; text-align: center;">
                        <div style="font-family: 'Anton', sans-serif; font-size: 2.5rem; color: #ffffff;">{{ count($business->portfolio) }}</div>
                        <div style="font-family: 'Space Mono', monospace; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.15em; color: #e2e2e2; opacity: 0.6;">Projects</div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Values --}}
            <div class="values-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; margin-bottom: 80px;">
                <div style="grid-column: 1 / -1; margin-bottom: 8px; padding-bottom: 16px; border-bottom: 4px solid #ffffff; display: flex; justify-content: space-between; align-items: flex-end;">
                    <h2 style="font-family: 'Anton', sans-serif; font-size: 2rem; color: #ffffff;">OUR VALUES</h2>
                    <span style="font-family: 'Space Mono', monospace; font-size: 10px; text-transform: uppercase; letter-spacing: 0.15em; color: #e2e2e2; opacity: 0.6;">SECTION_02</span>
                </div>

                <div class="dot-pattern" style="border: 4px solid #ffffff; padding: 40px;">
                    <span class="material-symbols-outlined" style="color: #72ff70; font-size: 28px; display: block; margin-bottom: 16px;">bolt</span>
                    <h3 style="font-family: 'Anton', sans-serif; font-size: 1.25rem; color: #ffffff; margin-bottom: 12px;">BOLD MOVES</h3>
                    <p style="font-family: 'Archivo Narrow', sans-serif; font-size: 0.9375rem; line-height: 1.7; color: #e2e2e2; opacity: 0.8;">We take decisive action and embrace bold strategies that set us apart from the competition.</p>
                </div>

                <div class="dot-pattern" style="border: 4px solid #ffffff; padding: 40px;">
                    <span class="material-symbols-outlined" style="color: #72ff70; font-size: 28px; display: block; margin-bottom: 16px;">shield</span>
                    <h3 style="font-family: 'Anton', sans-serif; font-size: 1.25rem; color: #ffffff; margin-bottom: 12px;">UNCOMPROMISING</h3>
                    <p style="font-family: 'Archivo Narrow', sans-serif; font-size: 0.9375rem; line-height: 1.7; color: #e2e2e2; opacity: 0.8;">We never settle for less. Our commitment to quality and performance is absolute.</p>
                </div>

                <div class="dot-pattern" style="border: 4px solid #ffffff; padding: 40px;">
                    <span class="material-symbols-outlined" style="color: #72ff70; font-size: 28px; display: block; margin-bottom: 16px;">rocket</span>
                    <h3 style="font-family: 'Anton', sans-serif; font-size: 1.25rem; color: #ffffff; margin-bottom: 12px;">DRIVEN</h3>
                    <p style="font-family: 'Archivo Narrow', sans-serif; font-size: 0.9375rem; line-height: 1.7; color: #e2e2e2; opacity: 0.8;">We are fueled by ambition, pushing boundaries and exceeding every expectation.</p>
                </div>
            </div>

            {{-- Full-width Map --}}
            @if($business->latitude && $business->longitude)
            <div>
                <span style="font-family: 'Space Mono', monospace; font-size: 10px; font-weight: 700; letter-spacing: 0.15em; text-transform: uppercase; color: #e2e2e2; opacity: 0.6; display: block; margin-bottom: 16px;">LOCATION</span>
                <div style="border: 4px solid #ffffff; overflow: hidden;">
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

    @include('themes::bold.store.footer')
</div>
</body>
</html>
