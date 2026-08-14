<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $business->name }} — Services</title>
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
        body {
            font-family: 'Hanken Grotesk', sans-serif;
            background: {{ $colors['background'] ?? '#fbf9f9' }};
            color: {{ $colors['text'] ?? '#000000' }};
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        main { flex: 1; }
        h1, h2, h3, h4 { font-family: 'Playfair Display', serif; font-weight: 600; line-height: 1.25; }
        a { text-decoration: none; transition: all 0.2s ease; }
        ::selection { background: {{ $colors['secondary'] ?? '#735c00' }}; color: {{ $colors['background'] ?? '#fbf9f9' }}; }
        :root {
            --c-primary: {{ $colors['primary'] ?? '#000000' }};
            --c-secondary: {{ $colors['secondary'] ?? '#735c00' }};
            --c-bg: {{ $colors['background'] ?? '#fbf9f9' }};
            --c-text: {{ $colors['text'] ?? '#000000' }};
            --c-accent: {{ $colors['accent'] ?? '#fed65b' }};
            --c-radius: {{ $radius ?? '0.125rem' }};
        }
        .heritage-label {
            font-family: 'Hanken Grotesk', sans-serif;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            font-weight: 500;
        }
        .heritage-hairline { border: 0.5px solid #d4d0c8; }
        .heritage-btn-primary {
            background: #000;
            color: #fbf9f9;
            border: none;
            padding: 0.875rem 2rem;
            font-family: 'Hanken Grotesk', sans-serif;
            font-size: 0.8125rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .heritage-btn-primary:hover { background: #735c00; }
        .heritage-btn-secondary {
            background: transparent;
            color: #000;
            border: 0.5px solid #000;
            padding: 0.875rem 2rem;
            font-family: 'Hanken Grotesk', sans-serif;
            font-size: 0.8125rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .heritage-btn-secondary:hover { background: #000; color: #fbf9f9; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: {{ $colors['background'] ?? '#fbf9f9' }}; }
        ::-webkit-scrollbar-thumb { background: {{ $colors['primary'] ?? '#000000' }}40; border-radius: 3px; }
    </style>
</head>
<body>
