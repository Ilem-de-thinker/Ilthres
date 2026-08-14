{{-- Bold theme · Service layout — High-Impact Editorial --}}
@section('title', $business->name . ' — Services')

@push('styles')
<style>
    :root {
        --c-primary: {{ $colors['primary'] ?? '#ffffff' }};
        --c-secondary: {{ $colors['secondary'] ?? '#72ff70' }};
        --c-bg: {{ $colors['background'] ?? '#131313' }};
        --c-text: {{ $colors['text'] ?? '#e2e2e2' }};
        --c-accent: {{ $colors['accent'] ?? '#72ff70' }};
        --c-font-heading: '{{ $typography['heading'] ?? 'Anton' }}', sans-serif;
        --c-font-body: '{{ $typography['body'] ?? 'Archivo Narrow' }}', sans-serif;
        --c-radius: {{ $radius ?? '0rem' }};
    }
    body { background: var(--c-bg); color: var(--c-text); font-family: var(--c-font-body); }
    h1, h2, h3, h4 { font-family: var(--c-font-heading); text-transform: uppercase; letter-spacing: 0.01em; line-height: 1.05; }
    ::selection { background: var(--c-accent); color: #002203; }

    [style*="color: #72ff70"] { color: var(--c-accent) !important; }
    [style*="background: #72ff70"] { background: var(--c-accent) !important; }
    [style*="color: #e2e2e2"] { color: var(--c-text) !important; }
    [style*="color: #ffffff"] { color: var(--c-primary) !important; }
    [style*="background: #ffffff"] { background: var(--c-primary) !important; }
    [style*="background: #131313"] { background: var(--c-bg) !important; }
    [style*="color: #131313"] { color: var(--c-bg) !important; }
    [style*="border-color: #444748"] { border-color: color-mix(in srgb, var(--c-text) 30%, transparent) !important; }
    [style*="font-family: 'Anton'"] { font-family: var(--c-font-heading) !important; }
    [style*="font-family: 'Archivo Narrow'"] { font-family: var(--c-font-body) !important; }
    [style*="font-family: 'Space Mono'"] { font-family: var(--c-font-body) !important; }

    @if (!($animations ?? true))
        *, *::before, *::after { animation-duration: 0s !important; transition-duration: 0s !important; }
    @endif

    .dot-pattern { background-image: radial-gradient(circle, color-mix(in srgb, var(--c-text) 25%, transparent) 1px, transparent 1px); background-size: 24px 24px; }
    ::-webkit-scrollbar { width: 8px; }
    ::-webkit-scrollbar-track { background: var(--c-bg); }
    ::-webkit-scrollbar-thumb { background: var(--c-primary); border: 2px solid var(--c-bg); }
</style>
@endpush

<div class="min-h-screen flex flex-col">
    @include('themes::bold.service.header')
    <main class="flex-1 pt-0">
        @include('themes::bold.service.hero')
        @include('themes::bold.service.about')
        @include('themes::bold.service.services')
    </main>
    @include('themes::bold.service.footer')
</div>
