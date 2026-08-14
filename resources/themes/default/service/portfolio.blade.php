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
                <h1 style="font-family: 'Playfair Display', serif; font-style: italic; font-size: clamp(2rem, 4vw, 3rem); color: #1c1c18;">Portfolio</h1>
            </div>

            @if($business->portfolio && count($business->portfolio) > 0)
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 32px;">
                @foreach($business->portfolio as $i => $item)
                <div class="subtle-shadow" style="background: #fcf9f2; border-radius: 0.5rem; overflow: hidden; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='none'">
                    <div style="padding: 16px; border-bottom: 0.5px solid #E5E0D5;">
                        @if($item['image_url'] ?? $item['image'] ?? null)
                        <div style="overflow: hidden; border-radius: 0.25rem;">
                            <img src="{{ $item['image_url'] ?? $item['image'] }}" alt="{{ $item['title'] ?? '' }}"
                                 style="width: 100%; aspect-ratio: 4/3; object-fit: cover; display: block; transition: transform 0.5s;"
                                 onmouseover="this.style.transform='scale(1.03)'" onmouseout="this.style.transform='scale(1)'">
                        </div>
                        @else
                        <div style="width: 100%; aspect-ratio: 4/3; background: #f1eee7; display: flex; align-items: center; justify-content: center; border-radius: 0.25rem;">
                            <span class="material-symbols-outlined" style="color: #c5c7c1; font-size: 48px;">collections</span>
                        </div>
                        @endif
                    </div>
                    <div style="padding: 24px;">
                        @if($item['category'] ?? null)
                        <span class="section-label" style="color: #6c5e06; margin-bottom: 8px; display: block;">{{ strtoupper($item['category']) }}</span>
                        @endif
                        <h3 style="font-family: 'Playfair Display', serif; font-style: italic; font-size: 1.125rem; color: #1c1c18; margin-bottom: 8px;">{{ $item['title'] ?? '' }}</h3>
                        @if($item['description'] ?? $item['excerpt'] ?? null)
                        <p style="font-size: 0.8125rem; color: #454843; line-height: 1.7;">{{ $item['description'] ?? $item['excerpt'] }}</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="thin-frame" style="text-align: center; padding: 80px 32px; border-radius: 0.5rem;">
                <span class="material-symbols-outlined" style="color: #c5c7c1; font-size: 64px; display: block; margin-bottom: 16px;">collections</span>
                <h3 style="font-size: 1.25rem; color: #1c1c18; margin-bottom: 8px;">No Portfolio Yet</h3>
                <p style="color: #757873; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.8125rem; margin-bottom: 32px;">This business hasn't added any portfolio items yet.</p>
                <a href="{{ route('business.site', $business) }}"
                   style="display: inline-flex; align-items: center; gap: 8px; padding: 16px 40px; background: #1c1c18; color: #fcf9f2; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.6875rem; font-weight: 600; letter-spacing: 0.05em; text-transform: uppercase; border: 0.5px solid #1c1c18; border-radius: 0.25rem; transition: all 0.2s; text-decoration: none;"
                   onmouseover="this.style.background='#454843'; this.style.borderColor='#454843';"
                   onmouseout="this.style.background='#1c1c18'; this.style.borderColor='#1c1c18';">
                    <span class="material-symbols-outlined" style="font-size: 16px;">arrow_back</span> Back to Profile
                </a>
            </div>
            @endif
        </div>
    </main>

    @include('themes::default.service.footer')
</div>
</body>
</html>
