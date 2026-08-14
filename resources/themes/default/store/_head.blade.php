<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $business->name }} — Store</title>
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
        :root { --c-primary: {{ $colors['primary'] ?? '#1c1c18' }}; --c-secondary: {{ $colors['secondary'] ?? '#5e5f5c' }}; --c-bg: {{ $colors['background'] ?? '#fcf9f2' }}; --c-text: {{ $colors['text'] ?? '#1c1c18' }}; --c-accent: {{ $colors['accent'] ?? '#6c5e06' }}; }
        .thin-frame { padding: 16px; border: 0.5px solid #E5E0D5; }
        .subtle-shadow { box-shadow: 0px 10px 40px rgba(0,0,0,0.03); }
        .section-label { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.6875rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1eee7; }
        ::-webkit-scrollbar-thumb { background: #c5c7c1; border-radius: 3px; }
    </style>
</head>
<body>
