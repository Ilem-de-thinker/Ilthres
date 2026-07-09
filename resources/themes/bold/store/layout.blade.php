{{--
  Bold theme · Store layout — editorial, high-contrast streetwear / premium magazine style.
--}}
@section('title', $business->name . ' — Store')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,800;0,900;1,800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    :root {
        --color-primary: {{ $colors['primary'] ?? '#000000' }};
        --color-secondary: {{ $colors['secondary'] ?? '#E5E7EB' }};
        --color-background: {{ $colors['background'] ?? '#FFFFFF' }};
        --color-text: {{ $colors['text'] ?? '#000000' }};
        --color-accent: {{ $colors['accent'] ?? '#DC2626' }};
        --font-heading: 'Plus Jakarta Sans', sans-serif;
        --font-body: 'Inter', sans-serif;
        --radius: {{ $radius ?? '0px' }};
    }
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: var(--font-body); background: var(--color-background); color: var(--color-text); line-height: 1.5; -webkit-font-smoothing: antialiased; }
    a { color: var(--color-text); text-decoration: none; transition: opacity 0.15s ease; }
    a:hover { opacity: 0.75; }
    
    .container { max-width: 1200px; margin: 0 auto; padding: 0 2rem; }
    section { padding: 6.5rem 0; border-bottom: 2px solid var(--color-text); }
    h1, h2, h3, h4 { font-family: var(--font-heading); color: var(--color-text); line-height: 1.05; font-weight: 900; text-transform: uppercase; letter-spacing: -0.03em; }
    img { max-width: 100%; height: auto; display: block; border: 2px solid var(--color-text); border-radius: var(--radius); }
    
    /* Editorial Bold Buttons */
    .btn {
        display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;
        padding: 0.875rem 2rem; border-radius: var(--radius);
        font-family: var(--font-heading); font-weight: 800; font-size: 0.8125rem;
        text-transform: uppercase; letter-spacing: 0.05em;
        border: 2px solid var(--color-text); cursor: pointer;
        transition: all 0.15s ease; text-decoration: none;
    }
    .btn-primary { 
        background: var(--color-primary); 
        color: var(--color-background);
        border-color: var(--color-primary);
    }
    .btn-primary:hover { 
        background: transparent;
        color: var(--color-text);
        border-color: var(--color-text);
    }
    .btn-outline { 
        background: transparent; 
        border: 2px solid var(--color-text); 
        color: var(--color-text);
    }
    .btn-outline:hover { 
        background: var(--color-text); 
        color: var(--color-background);
    }
    
    /* Clean Boxy Cards (No childish shadows) */
    .card {
        background: var(--color-background);
        border-radius: var(--radius);
        border: 2px solid var(--color-text);
        transition: transform 0.2s ease;
    }
    .card:hover {
        transform: translateY(-2px);
    }
    
    /* Grid system */
    .grid { display: grid; gap: 2.5rem; }
    .grid-2 { grid-template-columns: repeat(2, 1fr); }
    .grid-3 { grid-template-columns: repeat(3, 1fr); }
    .grid-4 { grid-template-columns: repeat(4, 1fr); }
    @media (max-width: 991px) { .grid-4 { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 768px) { .grid-2, .grid-3, .grid-4 { grid-template-columns: 1fr; } .container { padding: 0 1.5rem; } section { padding: 4.5rem 0; } }
    
    /* Section Headers */
    .section-title { font-size: 2.75rem; font-weight: 900; text-align: left; margin-bottom: 3.5rem; letter-spacing: -0.03em; border-bottom: 4px solid var(--color-text); padding-bottom: 0.5rem; display: inline-block; }
</style>
@endpush

<div>
    @include('themes::bold.store.header')
    @include('themes::bold.store.hero')
    @include('themes::bold.store.about')
    @include('themes::bold.store.portfolio')
    @include('themes::bold.store.products')
    @include('themes::bold.store.contact')
    @include('themes::bold.store.footer')
</div>
