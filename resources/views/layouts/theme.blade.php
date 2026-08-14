<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    @if (!empty($business))
        <meta name="description" content="{{ Str::limit(strip_tags($business->description ?? $business->about ?? $business->name), 160) }}">
        <meta property="og:title" content="{{ $business->name }}">
        <meta property="og:description" content="{{ Str::limit(strip_tags($business->description ?? $business->about ?? ''), 160) }}">
        @if ($business->cover)
            <meta property="og:image" content="{{ Storage::url($business->cover) }}">
        @endif
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">
        <link rel="canonical" href="{{ url()->current() }}">
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Archivo+Narrow:wght@400;500;600;700&family=Be+Vietnam+Pro:wght@300;400;500;600;700&family=Hanken+Grotesk:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600&family=JetBrains+Mono:wght@400;500;700&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Source+Serif+4:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Space+Mono:wght@400;700&family=Work+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; line-height: 1.6; -webkit-font-smoothing: antialiased; min-height: 100vh; display: flex; flex-direction: column; }
        main { flex: 1; }
        a { text-decoration: none; transition: all 0.2s ease; }
        ::selection { background: var(--c-primary, #111827); color: var(--c-bg, #fff); }
        :root {
            --c-primary: {{ $colors['primary'] ?? '#111827' }};
            --c-secondary: {{ $colors['secondary'] ?? '#4B5563' }};
            --c-bg: {{ $colors['background'] ?? '#FFFFFF' }};
            --c-text: {{ $colors['text'] ?? '#111827' }};
            --c-accent: {{ $colors['accent'] ?? '#9CA3AF' }};
            --c-font-heading: '{{ $typography['heading'] ?? 'Inter' }}', sans-serif;
            --c-font-body: '{{ $typography['body'] ?? 'Inter' }}', sans-serif;
            --c-radius: {{ $radius ?? '0.5rem' }};
            --c-animations: {{ ($animations ?? true) ? '1' : '0' }};
        }
        body { background: var(--c-bg) !important; color: var(--c-text) !important; }
        h1, h2, h3, h4 { font-family: var(--c-font-heading) !important; }
        @if (!($animations ?? true))
            *, *::before, *::after { animation-duration: 0s !important; transition-duration: 0s !important; }
        @endif
    </style>

    @livewireStyles
    @stack('styles')
</head>
<body>
    {{ $slot }}
    @livewireScripts
</body>
</html>
