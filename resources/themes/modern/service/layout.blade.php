{{-- Modern theme · Service layout — Lumina SaaS glassmorphism --}}
@section('title', $business->name . ' — Services')

@push('styles')
<style>
    :root {
        --c-primary: {{ $colors['primary'] ?? '#4648d4' }};
        --c-secondary: {{ $colors['secondary'] ?? '#006591' }};
        --c-bg: {{ $colors['background'] ?? '#f0f2ff' }};
        --c-text: {{ $colors['text'] ?? '#1a1a2e' }};
        --c-accent: {{ $colors['accent'] ?? '#b90538' }};
        --c-font-heading: '{{ $typography['heading'] ?? 'Plus Jakarta Sans' }}', sans-serif;
        --c-font-body: '{{ $typography['body'] ?? 'Plus Jakarta Sans' }}', sans-serif;
        --c-radius: {{ $radius ?? '1rem' }};
    }
    body { background: var(--c-bg); color: var(--c-text); font-family: var(--c-font-body); }
    h1, h2, h3, h4 { font-family: var(--c-font-heading); font-weight: 800; letter-spacing: -0.025em; }
    ::selection { background: var(--c-primary); color: #ffffff; }

    [style*="background: #4648d4"] { background: var(--c-primary) !important; }
    [style*="color: #4648d4"] { color: var(--c-primary) !important; }
    [style*="color: #1a1a2e"] { color: var(--c-text) !important; }
    [style*="color: #006591"] { color: var(--c-secondary) !important; }
    [style*="color: #b90538"] { color: var(--c-accent) !important; }
    [style*="background: #b90538"] { background: var(--c-accent) !important; }
    [style*="border-color: rgba(70,72,212"] { border-color: var(--c-primary) !important; }
    [style*="font-family: 'Plus Jakarta Sans'"] { font-family: var(--c-font-body) !important; }
    [style*="font-weight: 800"] { font-family: var(--c-font-heading) !important; }

    @if (!($animations ?? true))
        *, *::before, *::after { animation-duration: 0s !important; transition-duration: 0s !important; }
    @endif

    .glass { background: rgba(255,255,255,0.7); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.2); border-radius: var(--c-radius); }
    .glass-card { background: rgba(255,255,255,0.7); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.2); border-radius: var(--c-radius); }
    .btn-primary { display: inline-flex; align-items: center; gap: 8px; padding: 16px 40px; background: linear-gradient(135deg, var(--c-primary), var(--c-secondary)); color: #ffffff; border: none; border-radius: var(--c-radius); font-family: var(--c-font-body); font-weight: 600; cursor: pointer; box-shadow: 0 4px 24px rgba(70,72,212,0.3); text-decoration: none; }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 32px rgba(70,72,212,0.4); }
    ::-webkit-scrollbar { width: 6px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: var(--c-primary); border-radius: 3px; }
</style>
@endpush

<div class="min-h-screen flex flex-col">
    @include('themes::modern.service.header')
    <main class="flex-1 pt-0">
        @include('themes::modern.service.hero')
        @include('themes::modern.service.about')
        @include('themes::modern.service.services')
    </main>
    @include('themes::modern.service.footer')
</div>
