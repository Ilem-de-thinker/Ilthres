{{-- Heritage theme · Service layout --}}
@section('title', $business->name . ' — Services')

@push('styles')
<style>
    :root {
        --c-primary: {{ $colors['primary'] ?? '#000000' }};
        --c-secondary: {{ $colors['secondary'] ?? '#735c00' }};
        --c-bg: {{ $colors['background'] ?? '#fbf9f9' }};
        --c-text: {{ $colors['text'] ?? '#000000' }};
        --c-accent: {{ $colors['accent'] ?? '#fed65b' }};
        --c-font-heading: '{{ $typography['heading'] ?? 'Playfair Display' }}', serif;
        --c-font-body: '{{ $typography['body'] ?? 'Hanken Grotesk' }}', sans-serif;
        --c-radius: {{ $radius ?? '0.125rem' }};
    }
    body { background: var(--c-bg); color: var(--c-text); font-family: var(--c-font-body); }
    h1, h2, h3, h4 { font-family: var(--c-font-heading); }
    ::selection { background: var(--c-accent); color: var(--c-text); }

    [style*="background: #000000"] { background: var(--c-primary) !important; }
    [style*="color: #000000"] { color: var(--c-text) !important; }
    [style*="color: #735c00"] { color: var(--c-secondary) !important; }
    [style*="background: #735c00"] { background: var(--c-secondary) !important; }
    [style*="color: #fed65b"] { color: var(--c-accent) !important; }
    [style*="background: #fed65b"] { background: var(--c-accent) !important; }
    [style*="background: #fbf9f9"] { background: var(--c-bg) !important; }
    [style*="color: #fbf9f9"] { color: var(--c-bg) !important; }
    [style*="border-color: #d4d0c8"] { border-color: color-mix(in srgb, var(--c-secondary) 30%, transparent) !important; }
    [style*="background: #d4d0c8"] { background: color-mix(in srgb, var(--c-secondary) 30%, transparent) !important; }
    [style*="font-family: 'Playfair Display'"] { font-family: var(--c-font-heading) !important; }
    [style*="font-family: 'Hanken Grotesk'"] { font-family: var(--c-font-body) !important; }
    [style*="font-family: 'Plus Jakarta Sans'"] { font-family: var(--c-font-body) !important; }

    @if (!($animations ?? true))
        *, *::before, *::after { animation-duration: 0s !important; transition-duration: 0s !important; }
    @endif

    ::-webkit-scrollbar { width: 6px; }
    ::-webkit-scrollbar-track { background: var(--c-bg); }
    ::-webkit-scrollbar-thumb { background: var(--c-secondary); border-radius: 3px; }
    ::-webkit-scrollbar-thumb:hover { background: var(--c-text); }
</style>
@endpush

<div class="min-h-screen flex flex-col">
    @include('themes::heritage.service.header')
    <main class="flex-1 pt-0">
        @include('themes::heritage.service.hero')

        <div class="relative py-16" style="background: var(--c-bg);">
            <div class="flex items-center justify-center gap-6">
                <div style="width: 80px; height: 0.5px; background: var(--c-secondary);"></div>
                <div style="width: 4px; height: 4px; background: var(--c-secondary); transform: rotate(45deg);"></div>
                <div style="width: 80px; height: 0.5px; background: var(--c-secondary);"></div>
            </div>
        </div>

        @include('themes::heritage.service.about')

        <div class="relative py-16" style="background: var(--c-bg);">
            <div class="flex items-center justify-center gap-6">
                <div style="width: 120px; height: 0.5px; background: var(--c-secondary);"></div>
                <span class="material-symbols-outlined" style="font-size: 16px; color: var(--c-secondary);">diamond</span>
                <div style="width: 120px; height: 0.5px; background: var(--c-secondary);"></div>
            </div>
        </div>

        @include('themes::heritage.service.services')
    </main>
    @include('themes::heritage.service.footer')
</div>
