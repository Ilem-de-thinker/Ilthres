{{--
  Bold theme · Service detail — minimal detail page with description, pricing, duration, and booking CTA.
--}}
@section('title', $service->name . ' — ' . $business->name)

@push('styles')
<style>
    :root {
        --color-primary: {{ $colors['primary'] ?? '#0F172A' }};
        --color-secondary: {{ $colors['secondary'] ?? '#F59E0B' }};
        --color-background: {{ $colors['background'] ?? '#FFFFFF' }};
        --color-text: {{ $colors['text'] ?? '#0F172A' }};
        --color-accent: {{ $colors['accent'] ?? '#EF4444' }};
        --font-heading: '{{ $typography['heading'] ?? 'Inter' }}', sans-serif;
        --font-body: '{{ $typography['body'] ?? 'Inter' }}', sans-serif;
        --radius: {{ $radius ?? '0.25rem' }};
    }
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: var(--font-body); background: var(--color-background); color: var(--color-text); }
    .container { max-width: 1040px; margin: 0 auto; padding: 0 1.5rem; }
    section { padding: 3rem 0; }
    h1, h2, h3, h4 { font-family: var(--font-heading); font-weight: 800; letter-spacing: -0.02em; }
    .btn {
        display: inline-block; padding: 0.875rem 2rem;
        font-weight: 700; font-size: 0.8125rem; text-transform: uppercase;
        letter-spacing: 0.08em; border: none; cursor: pointer;
        transition: all 0.15s;
    }
    .btn-primary { background: var(--color-primary); color: #fff; }
    .btn-primary:hover { opacity: 0.9; }
    .btn-outline { background: transparent; border: 2px solid var(--color-primary); color: var(--color-primary); }
    .stat { padding: 1.5rem; border: 1px solid {{ $colors['text'] }}10; }
    .stat-value { font-size: 1.5rem; font-weight: 900; }
    .stat-label { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.08em; color: {{ $colors['text'] }}50; }
</style>
@endpush

<div>
    @include('themes::bold.service.header')

    <section>
        <div class="container">
            <a href="{{ route('business.profile', $business) }}" style="display: inline-flex; align-items: center; gap: 0.375rem; font-size: 0.8125rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: var(--color-primary); margin-bottom: 2rem;">&larr; Back to profile</a>
            <div style="max-width: 720px;">
                <h1 style="font-size: 2.5rem; font-weight: 900; margin-bottom: 1.5rem; letter-spacing: -0.03em;">{{ $service->name }}</h1>

                <div style="display: flex; gap: 1.5rem; margin-bottom: 2.5rem;">
                    <div class="stat">
                        <div class="stat-value">{{ number_format($service->price, 2) }}</div>
                        <div class="stat-label">Price</div>
                    </div>
                    @if ($service->duration_minutes)
                        <div class="stat">
                            <div class="stat-value">{{ $service->duration_minutes }}</div>
                            <div class="stat-label">Minutes</div>
                        </div>
                    @endif
                </div>

                @if ($service->description)
                    <p style="font-size: 1.0625rem; line-height: 1.8; color: {{ $colors['text'] }}88; margin-bottom: 2.5rem;">{{ $service->description }}</p>
                @endif

                <div style="display: flex; gap: 1rem;">
                    @if ($business->whatsapp)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $business->whatsapp) }}?text=Hi! I'd like to book {{ $service->name }}"
                           class="btn btn-primary" target="_blank" onclick="navigator.sendBeacon('{{ route('track.click', [$business->id, 'whatsapp_click']) }}?source=service&url='+encodeURIComponent(location.href));">Book now</a>
                    @endif
                    <a href="{{ route('business.profile', $business) }}#services" class="btn btn-outline">All services</a>
                </div>
            </div>
        </div>
    </section>

    @include('themes::bold.service.contact')
    @include('themes::bold.service.footer')
</div>
