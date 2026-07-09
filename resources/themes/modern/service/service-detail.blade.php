@push('styles')
<style>
        :root {
            --color-primary: {{ $colors['primary'] ?? '#8B5CF6' }};
            --color-secondary: {{ $colors['secondary'] ?? '#06B6D4' }};
            --color-background: {{ $colors['background'] ?? '#F9FAFB' }};
            --color-text: {{ $colors['text'] ?? '#1F2937' }};
            --color-accent: {{ $colors['accent'] ?? '#F472B6' }};
            --font-heading: '{{ $typography['heading'] ?? 'Plus Jakarta Sans' }}', sans-serif;
            --font-body: '{{ $typography['body'] ?? 'Inter' }}', sans-serif;
            --radius: {{ $radius ?? '0.75rem' }};
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: var(--font-body); background: #fff; color: var(--color-text); }
        .container { max-width: 1120px; margin: 0 auto; padding: 0 1.5rem; }
        .btn {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.75rem 1.75rem; border-radius: 9999px;
            font-weight: 600; font-size: 0.875rem; border: none; cursor: pointer;
            transition: all 0.2s;
        }
        .btn-primary { background: var(--color-primary); color: #fff; }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 4px 16px {{ $colors['primary'] }}40; }
        .btn-outline { background: transparent; border: 2px solid var(--color-primary); color: var(--color-primary); }
        .stat { padding: 1.25rem; border: 1px solid {{ $colors['text'] }}10; border-radius: var(--radius); }
        .stat-value { font-size: 1.5rem; font-weight: 700; color: var(--color-primary); }
        .stat-label { font-size: 0.75rem; color: {{ $colors['text'] }}60; }
        @media (max-width: 768px) { .split { grid-template-columns: 1fr; } }
    </style>
@endpush

<div>
    @include('themes::modern.service.header')

    <section style="padding: 3rem 0;">
        <div class="container" style="max-width: 720px;">
            <a href="{{ route('business.profile', $business) }}" style="display: inline-flex; align-items: center; gap: 0.375rem; font-size: 0.875rem; color: var(--color-primary); margin-bottom: 2rem;">&larr; Back to profile</a>
            <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: 1.5rem;">{{ $service->name }}</h1>

            <div style="display: flex; gap: 1rem; margin-bottom: 2rem;">
                @if ($service->price)
                    <div class="stat">
                        <div class="stat-value">{{ number_format($service->price, 2) }}</div>
                        <div class="stat-label">Price</div>
                    </div>
                @endif
                @if ($service->duration_minutes)
                    <div class="stat">
                        <div class="stat-value">{{ $service->duration_minutes }}</div>
                        <div class="stat-label">Minutes</div>
                    </div>
                @endif
            </div>

            @if ($service->description)
                <p style="font-size: 1.0625rem; line-height: 1.7; color: {{ $colors['text'] }}88; margin-bottom: 2rem;">{{ $service->description }}</p>
            @endif

            <div style="display: flex; gap: 1rem;">
                @if ($business->whatsapp)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $business->whatsapp) }}?text=Hi! I'd like to book {{ $service->name }}"
                       class="btn btn-primary" target="_blank" onclick="navigator.sendBeacon('{{ route('track.click', [$business->id, 'whatsapp_click']) }}?source=service&url='+encodeURIComponent(location.href));">Book on WhatsApp</a>
                @endif
                <a href="{{ route('business.profile', $business) }}#services" class="btn btn-outline">All services</a>
            </div>
        </div>
    </section>

    @include('themes::modern.service.contact')
    @include('themes::modern.service.footer')
</div>
