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
        .glass { background: rgba(255,255,255,0.7); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.2); border-radius: 16px; }
        .glass-card { background: rgba(255,255,255,0.7); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.2); border-radius: 16px; transition: all 0.3s ease; }
        .label { font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; font-size: 0.75rem; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #4648d4; border-radius: 3px; }
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
    @include('themes::modern.store.header')

    <main class="flex-1" style="padding-top: 112px; padding-bottom: 64px;">
        <div style="max-width: 1280px; margin: 0 auto; padding: 0 40px;">
            {{-- Page Header --}}
            <div style="margin-bottom: 48px;">
                <a href="{{ route('business.site', $business) }}" style="display: inline-flex; align-items: center; gap: 6px; margin-bottom: 24px; font-size: 0.875rem; font-weight: 500; color: #4648d4; text-decoration: none;" onmouseover="this.style.color='#006591'" onmouseout="this.style.color='#4648d4'">
                    <span class="material-symbols-outlined" style="font-size: 16px;">arrow_back</span> Back to Profile
                </a>
                <div>
                    <span class="label" style="color: #4648d4; margin-bottom: 8px; display: block;">About</span>
                    <h1 style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: clamp(2rem, 4vw, 3rem); letter-spacing: -0.03em; color: #1a1a2e;">About Us</h1>
                </div>
            </div>

            {{-- Main About Section --}}
            <div class="about-grid" style="display: grid; grid-template-columns: repeat(12, 1fr); gap: 24px; align-items: start; margin-bottom: 64px;">
                <div style="grid-column: 1 / 7;">
                    <span class="label" style="color: #4648d4; margin-bottom: 12px; display: block;">Our Story</span>
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

            {{-- Stats --}}
            @if(!($animations ?? true)) @else
            <div style="margin-bottom: 64px;">
                <span class="label" style="color: #4648d4; margin-bottom: 16px; display: block;">By the Numbers</span>
                <div class="stats-grid" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px;">
                    @if($business->products_count ?? $business->products->count() ?? false)
                    <div class="glass-card" style="padding: 32px; text-align: center;">
                        <div style="font-weight: 800; font-size: 2rem; color: #4648d4; margin-bottom: 4px;">{{ $business->products_count ?? $business->products->count() }}</div>
                        <div style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: #9ca3af;">Products</div>
                    </div>
                    @endif
                    <div class="glass-card" style="padding: 32px; text-align: center;">
                        <div style="font-weight: 800; font-size: 2rem; color: #4648d4; margin-bottom: 4px;">{{ $business->created_at ? $business->created_at->diffInYears(now()) : 1 }}</div>
                        <div style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: #9ca3af;">Years Active</div>
                    </div>
                    @if($business->location)
                    <div class="glass-card" style="padding: 32px; text-align: center;">
                        <div style="font-weight: 800; font-size: 2rem; color: #006591; margin-bottom: 4px;">1</div>
                        <div style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: #9ca3af;">Location</div>
                    </div>
                    @endif
                    @if($business->portfolio && count($business->portfolio) > 0)
                    <div class="glass-card" style="padding: 32px; text-align: center;">
                        <div style="font-weight: 800; font-size: 2rem; color: #006591; margin-bottom: 4px;">{{ count($business->portfolio) }}</div>
                        <div style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: #9ca3af;">Projects</div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Our Values --}}
            <div class="values-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; margin-bottom: 64px;">
                <div style="grid-column: 1 / -1; margin-bottom: 8px;">
                    <span class="label" style="color: #4648d4; margin-bottom: 8px; display: block;">Our Values</span>
                    <h2 style="font-weight: 800; font-size: clamp(1.75rem, 3vw, 2.5rem); letter-spacing: -0.03em; color: #1a1a2e;">What We Stand For</h2>
                </div>

                <div class="glass-card" style="padding: 40px;">
                    <div style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; background: rgba(70,72,212,0.08); border-radius: 12px; margin-bottom: 20px;">
                        <span class="material-symbols-outlined" style="font-size: 24px; color: #4648d4;">auto_awesome</span>
                    </div>
                    <h3 style="font-weight: 700; font-size: 1.125rem; color: #1a1a2e; margin-bottom: 12px;">Excellence</h3>
                    <p style="font-size: 0.875rem; line-height: 1.7; color: #6b7280;">We pursue excellence in every aspect of our work, setting high standards and delivering beyond expectations.</p>
                </div>

                <div class="glass-card" style="padding: 40px;">
                    <div style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; background: rgba(0,101,145,0.08); border-radius: 12px; margin-bottom: 20px;">
                        <span class="material-symbols-outlined" style="font-size: 24px; color: #006591;">diversity_3</span>
                    </div>
                    <h3 style="font-weight: 700; font-size: 1.125rem; color: #1a1a2e; margin-bottom: 12px;">Community</h3>
                    <p style="font-size: 0.875rem; line-height: 1.7; color: #6b7280;">We believe in building strong connections with our customers and the communities we serve.</p>
                </div>

                <div class="glass-card" style="padding: 40px;">
                    <div style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; background: rgba(70,72,212,0.08); border-radius: 12px; margin-bottom: 20px;">
                        <span class="material-symbols-outlined" style="font-size: 24px; color: #4648d4;">speed</span>
                    </div>
                    <h3 style="font-weight: 700; font-size: 1.125rem; color: #1a1a2e; margin-bottom: 12px;">Efficiency</h3>
                    <p style="font-size: 0.875rem; line-height: 1.7; color: #6b7280;">We leverage technology and smart processes to deliver fast, reliable, and seamless experiences.</p>
                </div>
            </div>

            {{-- Full-width Map --}}
            @if($business->latitude && $business->longitude)
            <div>
                <span class="label" style="color: #4648d4; margin-bottom: 16px; display: block;">Find Us</span>
                <div class="glass" style="overflow: hidden;">
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

    @include('themes::modern.store.footer')
</div>
</body>
</html>
