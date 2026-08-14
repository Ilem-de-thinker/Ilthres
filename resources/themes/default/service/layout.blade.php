{{-- Default theme · Service layout — Serene Ivory --}}
@section('title', $business->name . ' — Services')

@push('styles')
<style>
    :root {
        --c-primary: {{ $colors['primary'] ?? '#1c1c18' }};
        --c-secondary: {{ $colors['secondary'] ?? '#5e5f5c' }};
        --c-bg: {{ $colors['background'] ?? '#fcf9f2' }};
        --c-text: {{ $colors['text'] ?? '#1c1c18' }};
        --c-accent: {{ $colors['accent'] ?? '#6c5e06' }};
        --c-font-heading: '{{ $typography['heading'] ?? 'Playfair Display' }}', serif;
        --c-font-body: '{{ $typography['body'] ?? 'Plus Jakarta Sans' }}', sans-serif;
        --c-radius: {{ $radius ?? '0.25rem' }};
    }
    body { background: var(--c-bg); color: var(--c-text); font-family: var(--c-font-body); }
    h1, h2, h3, h4 { font-family: var(--c-font-heading); line-height: 1.15; }
    ::selection { background: var(--c-accent); color: #fff; }

    [style*="background: #fcf9f2"] { background: var(--c-bg) !important; }
    [style*="background: #1c1c18"] { background: var(--c-primary) !important; }
    [style*="color: #1c1c18"] { color: var(--c-text) !important; }
    [style*="color: #fcf9f2"] { color: var(--c-bg) !important; }
    [style*="color: #6c5e06"] { color: var(--c-accent) !important; }
    [style*="color: #5e5f5c"] { color: var(--c-secondary) !important; }
    [style*="background: #6c5e06"] { background: var(--c-accent) !important; }
    [style*="border: 0.5px solid #E5E0D5"],
    [style*="border: 0.5px solid #e5e0d5"] { border-color: color-mix(in srgb, var(--c-text) 20%, transparent) !important; }
    [style*="font-family: 'Playfair Display'"] { font-family: var(--c-font-heading) !important; }
    [style*="font-family: 'Plus Jakarta Sans'"] { font-family: var(--c-font-body) !important; }

    @if (!($animations ?? true))
        *, *::before, *::after { animation-duration: 0s !important; transition-duration: 0s !important; }
    @endif

    .thin-frame { padding: 16px; border: 0.5px solid color-mix(in srgb, var(--c-text) 20%, transparent); }
    .subtle-shadow { box-shadow: 0px 10px 40px rgba(0,0,0,0.03); }
    .section-label { font-family: var(--c-font-body); font-size: 0.6875rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
    ::-webkit-scrollbar { width: 6px; }
    ::-webkit-scrollbar-track { background: color-mix(in srgb, var(--c-bg) 90%, var(--c-text)); }
    ::-webkit-scrollbar-thumb { background: var(--c-secondary); border-radius: 3px; }
    ::-webkit-scrollbar-thumb:hover { background: var(--c-text); }
</style>
@endpush

<div class="min-h-screen flex flex-col">
    @include('themes::default.service.header')
    <main class="flex-1 pt-0">
        @include('themes::default.service.hero')
        @include('themes::default.service.about')
        @include('themes::default.service.services')
    </main>
    @include('themes::default.service.footer')
</div>
