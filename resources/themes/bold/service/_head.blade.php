<!DOCTYPE html>
<html lang="en" class="dark">
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
        :root { --c-primary: {{ $colors['primary'] ?? '#ffffff' }}; --c-secondary: {{ $colors['secondary'] ?? '#72ff70' }}; --c-bg: {{ $colors['background'] ?? '#131313' }}; --c-text: {{ $colors['text'] ?? '#e2e2e2' }}; --c-accent: {{ $colors['accent'] ?? '#72ff70' }}; }
        .dot-pattern { background-image: radial-gradient(circle, #444748 1px, transparent 1px); background-size: 24px 24px; }
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #131313; }
        ::-webkit-scrollbar-thumb { background: #ffffff; border: 2px solid #131313; }
    </style>
</head>
<body>
