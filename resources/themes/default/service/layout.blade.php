{{--
  Default theme · Service layout — minimalist, high-end presentation (Apple style).
--}}
@section('title', $business->name . ' — Services')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
    :root {
        --color-primary: {{ $colors['primary'] ?? '#111827' }};
        --color-secondary: {{ $colors['secondary'] ?? '#4B5563' }};
        --color-background: {{ $colors['background'] ?? '#FFFFFF' }};
        --color-text: {{ $colors['text'] ?? '#111827' }};
        --color-accent: {{ $colors['accent'] ?? '#9CA3AF' }};
        --font-heading: 'Inter', sans-serif;
        --font-body: 'Inter', sans-serif;
        --radius: {{ $radius ?? '0.375rem' }};
    }
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: var(--font-body); background: var(--color-background); color: var(--color-text); line-height: 1.6; -webkit-font-smoothing: antialiased; }
    a { color: var(--color-text); text-decoration: none; transition: opacity 0.15s ease; }
    a:hover { opacity: 0.8; }
    
    .container { max-width: 1200px; margin: 0 auto; padding: 0 2rem; }
    section { padding: 5rem 0; border-bottom: 1px solid color-mix(in srgb, var(--color-text) 8%, transparent); }
    h1, h2, h3, h4 { font-family: var(--font-heading); color: var(--color-text); line-height: 1.25; }
    img { max-width: 100%; height: auto; display: block; }
    
    /* Elegant Buttons */
    .btn {
        display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;
        padding: 0.75rem 1.5rem; border-radius: var(--radius);
        font-weight: 500; font-size: 0.875rem; border: 1px solid transparent; cursor: pointer;
        transition: all 0.2s ease; text-decoration: none;
    }
    .btn-primary { 
        background: var(--color-primary); 
        color: var(--color-background); 
    }
    .btn-primary:hover { 
        opacity: 0.9;
    }
    .btn-outline { 
        background: transparent; 
        border: 1px solid color-mix(in srgb, var(--color-text) 20%, transparent); 
        color: var(--color-text); 
    }
    .btn-outline:hover { 
        background: color-mix(in srgb, var(--color-text) 4%, transparent);
    }
    
    /* Clean Grids */
    .grid { display: grid; gap: 2rem; }
    .grid-2 { grid-template-columns: repeat(2, 1fr); }
    .grid-3 { grid-template-columns: repeat(3, 1fr); }
    .grid-4 { grid-template-columns: repeat(4, 1fr); }
    @media (max-width: 991px) { .grid-4 { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 768px) { .grid-2, .grid-3, .grid-4 { grid-template-columns: 1fr; } .container { padding: 0 1.25rem; } section { padding: 3.5rem 0; } }
    
    .section-title { font-size: 1.75rem; font-weight: 500; margin-bottom: 3rem; text-align: center; letter-spacing: -0.01em; }
</style>
@endpush

<div>
    @include('themes::default.service.header')
    @include('themes::default.service.hero')
    @include('themes::default.service.about')
    @include('themes::default.service.portfolio')
    @include('themes::default.service.services')
    @include('themes::default.service.contact')
    @include('themes::default.service.footer')
</div>
