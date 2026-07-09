{{--
  Default theme · Service detail — single service details with pricing, duration, and CTAs.
--}}
@section('title', $service->name . ' — ' . $business->name)

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
    
    .btn {
        display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;
        padding: 0.75rem 1.5rem; border-radius: var(--radius);
        font-weight: 500; font-size: 0.875rem; border: 1px solid transparent; cursor: pointer;
        transition: all 0.2s ease; text-decoration: none;
    }
    .btn-primary { background: var(--color-primary); color: var(--color-background); }
    .btn-primary:hover { opacity: 0.9; }
    .btn-outline { background: transparent; border: 1px solid var(--color-primary); color: var(--color-primary); }
    .btn-outline:hover { background: color-mix(in srgb, var(--color-primary) 4%, transparent); }
</style>
@endpush

<div>
    @include('themes::default.service.header')

    <section style="padding: 3rem 0; border-bottom: 1px solid color-mix(in srgb, var(--color-text) 8%, transparent);">
        <div class="container" style="max-width: 800px;">
            <div style="margin-bottom: 2rem;">
                <a href="{{ route('business.profile', $business) }}" style="display: inline-flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; font-weight: 500; color: color-mix(in srgb, var(--color-text) 60%, transparent);">
                    &larr; Back to Services
                </a>
            </div>
            
            <span style="font-size: 0.8125rem; font-weight: 600; text-transform: uppercase; color: var(--color-primary); letter-spacing: 0.05em; display: block; margin-bottom: 0.5rem;">
                {{ $service->category?->name ?? 'General' }}
            </span>
            
            <h1 style="font-size: 2.25rem; font-weight: 600; margin-bottom: 1.5rem; line-height: 1.2;">{{ $service->name }}</h1>

            @if ($service->description)
                <p style="font-size: 1.0625rem; line-height: 1.7; color: color-mix(in srgb, var(--color-text) 70%, transparent); margin-bottom: 2.5rem;">
                    {{ $service->description }}
                </p>
            @endif

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem; margin-bottom: 3rem;">
                @if ($service->price)
                    <div style="padding: 1.25rem; border: 1px solid color-mix(in srgb, var(--color-text) 8%, transparent); border-radius: var(--radius);">
                        <p style="font-size: 0.75rem; color: color-mix(in srgb, var(--color-text) 50%, transparent); margin-bottom: 0.25rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.05em;">Price</p>
                        <p style="font-size: 1.375rem; font-weight: 600; color: var(--color-text);">{{ number_format($service->price, 2) }}</p>
                    </div>
                @endif
                @if ($service->duration_minutes)
                    <div style="padding: 1.25rem; border: 1px solid color-mix(in srgb, var(--color-text) 8%, transparent); border-radius: var(--radius);">
                        <p style="font-size: 0.75rem; color: color-mix(in srgb, var(--color-text) 50%, transparent); margin-bottom: 0.25rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.05em;">Duration</p>
                        <p style="font-size: 1.375rem; font-weight: 600; color: var(--color-text);">{{ $service->duration_minutes }} minutes</p>
                    </div>
                @endif
            </div>

            <div style="display: flex; gap: 1rem; border-top: 1px solid color-mix(in srgb, var(--color-text) 8%, transparent); padding-top: 2rem;">
                @if ($business->whatsapp)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $business->whatsapp) }}?text=Hi! I'd like to book {{ $service->name }}"
                       class="btn btn-primary" target="_blank" onclick="navigator.sendBeacon('{{ route('track.click', [$business->id, 'whatsapp_click']) }}?source=service&url='+encodeURIComponent(location.href));">
                        Book via WhatsApp
                    </a>
                @endif
                @if ($business->phone)
                    <a href="tel:{{ $business->phone }}" class="btn btn-outline" onclick="navigator.sendBeacon('{{ route('track.click', [$business->id, 'phone_click']) }}?source=service&url='+encodeURIComponent(location.href));">
                        Call to Book
                    </a>
                @endif
            </div>
        </div>
    </section>

    @include('themes::default.service.contact')
    @include('themes::default.service.footer')
</div>
