<!DOCTYPE html>
<html lang="en" class="dark">
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
    </style>
</head>
<body>
<div class="min-h-screen flex flex-col">
    @include('themes::bold.service.header')

    <main class="flex-1" style="padding-top: 96px; padding-bottom: 64px;">
        <div style="max-width: 1280px; margin: 0 auto; padding: 0 64px;">
            {{-- Page Header --}}
            <div style="margin-bottom: 64px;">
                <a href="{{ route('business.site', $business) }}" style="display: inline-flex; align-items: center; gap: 6px; margin-bottom: 24px; font-family: 'Space Mono', monospace; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.15em; color: #e2e2e2; text-decoration: none;" onmouseover="this.style.color='#72ff70'" onmouseout="this.style.color='#e2e2e2'">
                    <span class="material-symbols-outlined" style="font-size: 16px;">arrow_back</span> BACK_TO_PROFILE
                </a>
                <div style="display: flex; justify-content: space-between; align-items: flex-end; padding-bottom: 16px; border-bottom: 4px solid #ffffff;">
                    <h1 style="font-family: 'Anton', sans-serif; font-size: 3rem; color: #ffffff;">PORTFOLIO</h1>
                    <span style="font-family: 'Space Mono', monospace; font-size: 10px; text-transform: uppercase; letter-spacing: 0.15em; color: #e2e2e2; opacity: 0.6;">SECTION_03</span>
                </div>
            </div>

            @if($business->portfolio && count($business->portfolio) > 0)
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px;">
                @foreach($business->portfolio as $i => $item)
                <div style="border: 4px solid #ffffff; overflow: hidden; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='none'">
                    <div style="aspect-ratio: 4/3; overflow: hidden; border-bottom: 4px solid #ffffff;">
                        @if($item['image_url'] ?? $item['image'] ?? null)
                        <img src="{{ $item['image_url'] ?? $item['image'] }}" alt="{{ $item['title'] ?? '' }}"
                             style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s;"
                             onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                        @else
                        <div class="dot-pattern" style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                            <span class="material-symbols-outlined" style="color: #444748; font-size: 48px;">collections</span>
                        </div>
                        @endif
                    </div>
                    <div style="padding: 24px;">
                        <span style="font-family: 'Space Mono', monospace; font-size: 10px; color: #e2e2e2; opacity: 0.5; margin-bottom: 8px; display: block; text-transform: uppercase; letter-spacing: 0.15em;">
                            {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }} @if($item['category'] ?? null) // {{ strtoupper($item['category']) }} @endif
                        </span>
                        <h3 style="font-family: 'Anton', sans-serif; font-size: 1.125rem; text-transform: uppercase; color: #ffffff; margin-bottom: 8px;">{{ $item['title'] ?? '' }}</h3>
                        @if($item['description'] ?? $item['excerpt'] ?? null)
                        <p style="font-size: 0.875rem; color: #e2e2e2; opacity: 0.7;">{{ $item['description'] ?? $item['excerpt'] }}</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="dot-pattern" style="text-align: center; padding: 80px 0; border: 4px solid #444748;">
                <span class="material-symbols-outlined" style="color: #444748; font-size: 64px; display: block; margin-bottom: 16px;">collections</span>
                <h3 style="font-size: 1.5rem; color: #e2e2e2; margin-bottom: 8px;">NO_PORTFOLIO_YET</h3>
                <p style="color: #e2e2e2; opacity: 0.5; font-family: 'Space Mono', monospace; font-size: 10px; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 32px;">THIS_BUSINESS_HASN'T_ADDED_ANY_PORTFOLIO_ITEMS_YET.</p>
                <a href="{{ route('business.site', $business) }}"
                   style="display: inline-flex; align-items: center; gap: 8px; padding: 16px 40px; background: #ffffff; color: #131313; font-family: 'Space Mono', monospace; font-size: 14px; font-weight: 700; letter-spacing: 0.2em; text-transform: uppercase; border: 4px solid #ffffff; transition: all 0.15s; text-decoration: none;"
                   onmouseover="this.style.background='#72ff70'; this.style.borderColor='#72ff70';"
                   onmouseout="this.style.background='#ffffff'; this.style.borderColor='#ffffff';">
                    <span class="material-symbols-outlined" style="font-size: 16px;">arrow_back</span> BACK_TO_PROFILE
                </a>
            </div>
            @endif
        </div>
    </main>

    @include('themes::bold.service.footer')
</div>
</body>
</html>
