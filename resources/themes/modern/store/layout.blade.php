{{--
  Modern theme · Store layout — sleek, professional, contemporary design (Stripe/SaaS aesthetic).
--}}
@section('title', $business->name . ' — Store')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<style>
    :root {
        --color-primary: {{ $colors['primary'] ?? '#4F46E5' }};
        --color-secondary: {{ $colors['secondary'] ?? '#06B6D4' }};
        --color-background: {{ $colors['background'] ?? '#F9FAFB' }};
        --color-text: {{ $colors['text'] ?? '#1F2937' }};
        --color-accent: {{ $colors['accent'] ?? '#EC4899' }};
        --font-heading: 'Plus Jakarta Sans', sans-serif;
        --font-body: 'Inter', sans-serif;
        --radius: {{ $radius ?? '0.5rem' }};
    }
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: var(--font-body); background: var(--color-background); color: var(--color-text); line-height: 1.6; -webkit-font-smoothing: antialiased; }
    a { color: var(--color-primary); text-decoration: none; transition: all 0.2s ease; }
    a:hover { opacity: 0.85; }
    
    .container { max-width: 1200px; margin: 0 auto; padding: 0 2rem; }
    section { padding: 6rem 0; position: relative; }
    h1, h2, h3, h4 { font-family: var(--font-heading); color: var(--color-text); line-height: 1.25; font-weight: 700; }
    img { max-width: 100%; height: auto; display: block; }
    
    /* Elegant Modern Buttons */
    .btn {
        display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;
        padding: 0.75rem 1.5rem; border-radius: var(--radius);
        font-family: var(--font-heading); font-weight: 600; font-size: 0.875rem; 
        border: 1px solid transparent; cursor: pointer;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1); text-decoration: none;
    }
    .btn-primary { 
        background: var(--color-primary); 
        color: #ffffff;
        box-shadow: 0 4px 12px color-mix(in srgb, var(--color-primary) 15%, transparent);
    }
    .btn-primary:hover { 
        transform: translateY(-1px);
        box-shadow: 0 6px 16px color-mix(in srgb, var(--color-primary) 25%, transparent);
    }
    .btn-outline { 
        background: #ffffff; 
        border: 1px solid color-mix(in srgb, var(--color-text) 12%, transparent); 
        color: var(--color-text);
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }
    .btn-outline:hover { 
        background: color-mix(in srgb, var(--color-text) 2%, transparent);
        border-color: color-mix(in srgb, var(--color-text) 20%, transparent);
        transform: translateY(-1px);
    }
    
    /* Premium Cards */
    .card {
        background: #ffffff;
        border-radius: var(--radius);
        border: 1px solid color-mix(in srgb, var(--color-text) 6%, transparent);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.01);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        overflow: hidden;
    }
    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
        border-color: color-mix(in srgb, var(--color-primary) 15%, transparent);
    }
    
    /* Grid system */
    .grid { display: grid; gap: 2rem; }
    .grid-2 { grid-template-columns: repeat(2, 1fr); }
    .grid-3 { grid-template-columns: repeat(3, 1fr); }
    .grid-4 { grid-template-columns: repeat(4, 1fr); }
    @media (max-width: 991px) { .grid-4 { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 768px) { .grid-2, .grid-3, .grid-4 { grid-template-columns: 1fr; } .container { padding: 0 1.5rem; } section { padding: 4.5rem 0; } }
    
    .section-title { font-size: 2rem; font-weight: 800; text-align: center; margin-bottom: 0.5rem; letter-spacing: -0.02em; }
    .section-subtitle { font-size: 0.9375rem; color: color-mix(in srgb, var(--color-text) 50%, transparent); text-align: center; margin-bottom: 3.5rem; font-weight: 500; }
</style>
@endpush

<div>
    @include('themes::modern.store.header')
    @include('themes::modern.store.hero')
    @include('themes::modern.store.about')
    @include('themes::modern.store.portfolio')
    @include('themes::modern.store.products')
    @include('themes::modern.store.contact')
    @include('themes::modern.store.footer')
</div>
