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
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 0.75rem 1rem; text-align: left; border-bottom: 1px solid {{ $colors['text'] }}10; }
    th { font-weight: 600; font-size: 0.75rem; color: {{ $colors['text'] }}60; text-transform: uppercase; letter-spacing: 0.05em; }
    .split { display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; }
    @media (max-width: 768px) { .split { grid-template-columns: 1fr; } }
</style>
@endpush

<div>
    @include('themes::bold.store.header')

    <section>
        <div class="container">
            <a href="{{ route('business.profile', $business) }}" style="display: inline-flex; align-items: center; gap: 0.375rem; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: var(--color-primary); margin-bottom: 2rem;">&larr; Back to store</a>
            <div class="split">
                <div>
                    @if ($product->images && count($product->images) > 0)
                        <img src="{{ Storage::url($product->images[0]) }}" alt="{{ $product->name }}"
                             style="width: 100%;">
                        @if (count($product->images) > 1)
                            <div style="display: flex; gap: 0.5rem; margin-top: 0.75rem;">
                                @foreach ($product->images as $img)
                                    <img src="{{ Storage::url($img) }}" alt="" style="width: 4rem; height: 4rem; object-fit: cover; cursor: pointer; border: 2px solid transparent;">
                                @endforeach
                            </div>
                        @endif
                    @else
                        <div style="width: 100%; aspect-ratio: 1; background: {{ $colors['text'] }}05; display: flex; align-items: center; justify-content: center; color: {{ $colors['text'] }}40;">No image</div>
                    @endif
                </div>
                <div>
                    <h1 style="font-size: 2rem; font-weight: 900; margin-bottom: 1rem; letter-spacing: -0.03em;">{{ $product->name }}</h1>
                    @if ($product->description)
                        <p style="font-size: 1rem; line-height: 1.7; color: {{ $colors['text'] }}88; margin-bottom: 2rem;">{{ $product->description }}</p>
                    @endif

                    @if ($product->variants && $product->variants->isNotEmpty())
                        <h3 style="font-size: 0.875rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 1rem;">Variants</h3>
                        <div style="display: flex; flex-direction: column; gap: 1rem;">
                            @foreach ($product->variants as $variant)
                                <div>
                                    <p style="font-size: 0.6875rem; font-weight: 700; color: {{ $colors['text'] }}60; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 0.5rem;">{{ $variant['name'] }}</p>
                                    <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                                        @foreach ($variant['options'] ?? [] as $option)
                                            <div style="display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 0.75rem; border: 1px solid {{ $colors['text'] }}10; font-size: 0.8125rem;">
                                                @if (!empty($option['image']))
                                                    <img src="{{ Storage::url($option['image']) }}" alt="" style="width: 1.5rem; height: 1.5rem; border-radius: 9999px; object-fit: cover;">
                                                @endif
                                                <span>{{ $option['value'] }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @elseif ($product->price)
                        <p style="font-size: 1.5rem; font-weight: 900; margin-bottom: 2rem;">{{ number_format($product->price, 2) }}</p>
                    @endif

                    <div style="margin-top: 2rem;">
                        @if ($business->whatsapp)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $business->whatsapp) }}?text=Hi! I'm interested in {{ $product->name }}"
                               class="btn btn-primary" target="_blank" onclick="navigator.sendBeacon('{{ route('track.click', [$business->id, 'whatsapp_click']) }}?source=product&url='+encodeURIComponent(location.href));">Inquire on WhatsApp</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('themes::bold.store.contact')
    @include('themes::bold.store.footer')
</div>
