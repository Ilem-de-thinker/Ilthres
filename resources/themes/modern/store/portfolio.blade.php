<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Portfolio — {{ $business->name }}</title>
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
        .glass-card:hover { transform: translateY(-4px); box-shadow: 0 24px 48px -12px rgba(70,72,212,0.15), 0 8px 24px -4px rgba(0,0,0,0.08); }
        .btn-primary { display: inline-flex; align-items: center; gap: 8px; padding: 14px 32px; background: linear-gradient(135deg, #4648d4, #006591); color: #ffffff; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 24px rgba(70,72,212,0.3); text-decoration: none; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 32px rgba(70,72,212,0.4); }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #4648d4; border-radius: 3px; }
    </style>
</head>
<body>
<div class="min-h-screen flex flex-col">
    @include('themes::modern.store.header')

    <main class="flex-1" style="padding-top: 96px; padding-bottom: 64px;">
        <div style="max-width: 1280px; margin: 0 auto; padding: 0 40px;">
            <div style="margin-bottom: 48px;">
                <a href="{{ route('business.site', $business) }}" style="display: inline-flex; align-items: center; gap: 6px; margin-bottom: 24px; font-size: 0.875rem; font-weight: 500; color: #4648d4; text-decoration: none;" onmouseover="this.style.color='#006591'" onmouseout="this.style.color='#4648d4'">
                    <span class="material-symbols-outlined" style="font-size: 16px;">arrow_back</span> Back to Profile
                </a>
                <div>
                    <span class="label" style="color: #4648d4; margin-bottom: 8px; display: block;">Portfolio</span>
                    <h1 style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: clamp(2rem, 4vw, 3rem); letter-spacing: -0.03em; color: #1a1a2e;">Our Work</h1>
                </div>
            </div>

            @if($business->portfolio && count($business->portfolio) > 0)
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px;">
                @foreach($business->portfolio as $i => $item)
                <div class="glass-card" style="overflow: hidden;">
                    <div style="aspect-ratio: 4/3; overflow: hidden;">
                        @if($item['image_url'] ?? $item['image'] ?? null)
                        <img src="{{ $item['image_url'] ?? $item['image'] }}" alt="{{ $item['title'] ?? '' }}"
                             style="width: 100%; height: 100%; object-fit: cover; border-radius: 16px 16px 0 0; transition: transform 0.5s;"
                             onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                        @else
                        <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: rgba(70,72,212,0.04);">
                            <span class="material-symbols-outlined" style="color: #4648d4; font-size: 48px;">collections</span>
                        </div>
                        @endif
                    </div>
                    <div style="padding: 24px;">
                        @if($item['category'] ?? null)
                        <span style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: #006591; display: block; margin-bottom: 8px;">{{ $item['category'] }}</span>
                        @endif
                        <h3 style="font-weight: 700; font-size: 1.125rem; color: #1a1a2e; margin-bottom: 8px;">{{ $item['title'] ?? '' }}</h3>
                        @if($item['description'] ?? $item['excerpt'] ?? null)
                        <p style="font-size: 0.875rem; color: #6b7280; line-height: 1.6;">{{ $item['description'] ?? $item['excerpt'] }}</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="glass" style="text-align: center; padding: 80px 0;">
                <span class="material-symbols-outlined" style="color: #4648d4; font-size: 64px; display: block; margin-bottom: 16px;">collections</span>
                <h3 style="font-size: 1.25rem; color: #1a1a2e; margin-bottom: 8px;">No Portfolio Yet</h3>
                <p style="font-size: 0.875rem; color: #9ca3af; margin-bottom: 32px;">This business hasn't added any portfolio items yet.</p>
                <a href="{{ route('business.site', $business) }}" class="btn-primary">
                    <span class="material-symbols-outlined" style="font-size: 16px;">arrow_back</span> Back to Profile
                </a>
            </div>
            @endif
        </div>
    </main>

    @include('themes::modern.store.footer')
</div>
</body>
</html>
