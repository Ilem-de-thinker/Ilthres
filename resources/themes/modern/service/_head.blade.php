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
        input, textarea, select { font-family: 'Plus Jakarta Sans', sans-serif; }
        ::selection { background: #4648d4; color: #ffffff; }
        :root { --c-primary: {{ $colors['primary'] ?? '#4648d4' }}; --c-secondary: {{ $colors['secondary'] ?? '#006591' }}; --c-bg: {{ $colors['background'] ?? '#f0f2ff' }}; --c-text: {{ $colors['text'] ?? '#1a1a2e' }}; --c-accent: {{ $colors['accent'] ?? '#b90538' }}; }
        .glass { background: rgba(255,255,255,0.7); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.2); border-radius: 16px; }
        .glass-card { background: rgba(255,255,255,0.7); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.2); border-radius: 16px; transition: all 0.3s ease; }
        .glass-card:hover { transform: translateY(-4px); box-shadow: 0 24px 48px -12px rgba(70,72,212,0.15), 0 8px 24px -4px rgba(0,0,0,0.08); }
        .btn-primary { display: inline-flex; align-items: center; gap: 8px; padding: 16px 40px; background: linear-gradient(135deg, #4648d4, #006591); color: #ffffff; border: none; border-radius: 8px; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1rem; font-weight: 600; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 24px rgba(70,72,212,0.3); text-decoration: none; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 32px rgba(70,72,212,0.4); }
        .btn-secondary { display: inline-flex; align-items: center; gap: 8px; padding: 16px 40px; background: rgba(255,255,255,0.7); border: 1px solid rgba(70,72,212,0.3); color: #4648d4; border-radius: 8px; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1rem; font-weight: 600; cursor: pointer; transition: all 0.3s ease; text-decoration: none; backdrop-filter: blur(8px); }
        .btn-secondary:hover { background: #4648d4; color: #ffffff; border-color: #4648d4; }
        .label { font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; font-size: 0.75rem; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #4648d4; border-radius: 3px; }
        input.glass-input, textarea.glass-input, select.glass-input { width: 100%; padding: 16px 20px; background: rgba(255,255,255,0.7); border: 1px solid rgba(0,0,0,0.08); border-radius: 8px; color: #1a1a2e; font-size: 1rem; outline: none; transition: all 0.25s ease; }
        input.glass-input:focus, textarea.glass-input:focus, select.glass-input:focus { border-color: #4648d4; box-shadow: 0 0 0 3px rgba(70,72,212,0.15); }
    </style>
</head>
<body>
