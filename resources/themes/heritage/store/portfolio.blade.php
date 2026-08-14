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
    </style>
</head>
<body>
<div class="min-h-screen flex flex-col">
    @include('themes::heritage.store.header')

    <main class="flex-1" style="padding-top: 8rem; padding-bottom: 6rem;">
        <div style="max-width: 1280px; margin: 0 auto; padding: 0 64px;">
            <div style="text-align: center; margin-bottom: 4rem;">
                <a href="{{ route('business.site', $business) }}" style="display: inline-flex; align-items: center; gap: 8px; font-family: 'Hanken Grotesk', sans-serif; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; color: #735c00; text-decoration: none; margin-bottom: 2rem; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.6'" onmouseout="this.style.opacity='1'">
                    <span class="material-symbols-outlined" style="font-size: 16px;">arrow_back</span> Back to Profile
                </a>
                <div style="width: 32px; height: 0.5px; background: #735c00; margin: 0 auto 1.5rem;"></div>
                <h1 style="font-family: 'Playfair Display', serif; font-style: italic; font-weight: 500; font-size: 2.5rem; margin-bottom: 0.75rem;">Portfolio</h1>
                <span class="heritage-label" style="font-size: 0.6875rem; color: #735c00;">A curated showcase of work</span>
            </div>

            @if($business->portfolio && count($business->portfolio) > 0)
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem;">
                @foreach($business->portfolio as $index => $item)
                <div style="border: 0.5px solid #d4d0c8; transition: border-color 0.3s; position: relative;"
                     onmouseover="this.style.borderColor='#735c00';"
                     onmouseout="this.style.borderColor='#d4d0c8';">
                    <div style="height: 18rem; overflow: hidden; border-bottom: 0.5px solid #d4d0c8;">
                        @if($item['image_url'] ?? $item['image'] ?? null)
                        <img src="{{ $item['image_url'] ?? $item['image'] }}" alt="{{ $item['title'] ?? '' }}" style="width: 100%; height: 100%; object-fit: cover; transition: opacity 0.5s;" onmouseover="this.style.opacity='0.85'" onmouseout="this.style.opacity='1'">
                        @else
                        <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #f5f3ef;">
                            <span class="material-symbols-outlined" style="color: #d4d0c8; font-size: 48px;">collections</span>
                        </div>
                        @endif
                    </div>
                    <div style="padding: 1.5rem;">
                        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem;">
                            <span style="font-family: 'Playfair Display', serif; font-size: 1.5rem; font-weight: 600; color: #d4d0c8;">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            @if($item['category'] ?? null)
                                <span class="heritage-label" style="font-size: 0.5625rem; color: #735c00; padding: 0.25rem 0.625rem; border: 0.5px solid #735c00;">{{ $item['category'] }}</span>
                            @endif
                        </div>
                        <h3 style="font-family: 'Playfair Display', serif; font-size: 1.0625rem; font-weight: 600; margin-bottom: 0.5rem;">{{ $item['title'] ?? '' }}</h3>
                        @if($item['description'] ?? $item['excerpt'] ?? null)
                        <p style="font-family: 'Hanken Grotesk', sans-serif; font-size: 0.8125rem; color: #888; line-height: 1.6;">{{ $item['description'] ?? $item['excerpt'] }}</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div style="text-align: center; padding: 5rem 0; border: 0.5px solid #d4d0c8;">
                <span class="material-symbols-outlined" style="font-size: 48px; color: #d4d0c8; display: block; margin-bottom: 1rem;">collections</span>
                <h3 style="font-family: 'Playfair Display', serif; font-size: 1.25rem; margin-bottom: 0.5rem;">No portfolio yet</h3>
                <p style="font-family: 'Hanken Grotesk', sans-serif; color: #999; margin-bottom: 2rem;">No portfolio items have been added yet.</p>
                <a href="{{ route('business.site', $business) }}" class="heritage-btn-primary" style="display: inline-flex; align-items: center; gap: 8px; text-decoration: none;">
                    <span class="material-symbols-outlined" style="font-size: 16px;">arrow_back</span> Back to Profile
                </a>
            </div>
            @endif
        </div>
    </main>

    @include('themes::heritage.store.footer')
</div>
</body>
</html>
