{{--
  Default theme · Store product detail — single product with images, description, variants table.
--}}
@section('title', $product->name . ' — ' . $business->name)

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
</style>
@endpush

<div>
    @include('themes::default.store.header')

    <section style="padding: 3rem 0; border-bottom: 1px solid color-mix(in srgb, var(--color-text) 8%, transparent);">
        <div class="container">
            <div style="margin-bottom: 2rem;">
                <a href="{{ route('business.profile', $business) }}" style="display: inline-flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; font-weight: 500; color: color-mix(in srgb, var(--color-text) 60%, transparent);">
                    &larr; Back to Products
                </a>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; align-items: start;">
                {{-- Images --}}
                <div>
                    @if ($product->images && count($product->images) > 0)
                        <img src="{{ Storage::url($product->images[0]) }}" alt="{{ $product->name }}"
                             style="width: 100%; border-radius: var(--radius); border: 1px solid color-mix(in srgb, var(--color-text) 8%, transparent); aspect-ratio: 1; object-fit: cover;">
                        @if (count($product->images) > 1)
                            <div style="display: flex; gap: 0.75rem; margin-top: 1rem; overflow-x: auto;">
                                @foreach ($product->images as $img)
                                    <img src="{{ Storage::url($img) }}" alt="" style="width: 4.5rem; height: 4.5rem; object-fit: cover; border-radius: calc(var(--radius) * 0.5); cursor: pointer; border: 1px solid color-mix(in srgb, var(--color-text) 8%, transparent);">
                                @endforeach
                            </div>
                        @endif
                    @else
                        <div style="width: 100%; aspect-ratio: 1; background: color-mix(in srgb, var(--color-text) 3%, transparent); border-radius: var(--radius); display: flex; align-items: center; justify-content: center; color: color-mix(in srgb, var(--color-text) 30%, transparent); font-size: 0.875rem; border: 1px solid color-mix(in srgb, var(--color-text) 8%, transparent);">
                            No image available
                        </div>
                    @endif
                </div>

                {{-- Details --}}
                <div>
                    <h1 style="font-size: 2.25rem; font-weight: 600; margin-bottom: 1rem; line-height: 1.2;">{{ $product->name }}</h1>
                    
                    <div style="margin-bottom: 2rem;">
                        <span style="font-weight: 700; font-size: 1.5rem; color: var(--color-primary);">
                            @if ($product->variants && $product->variants->isNotEmpty())
                                From {{ number_format($product->variants->min('price'), 2) }}
                            @else
                                {{ number_format($product->price, 2) }}
                            @endif
                        </span>
                        @if ($product->compare_price && $product->compare_price > $product->price)
                            <span style="font-size: 1.125rem; color: color-mix(in srgb, var(--color-text) 40%, transparent); text-decoration: line-through; margin-left: 0.5rem;">
                                {{ number_format($product->compare_price, 2) }}
                            </span>
                        @endif
                    </div>

                    @if ($product->description)
                        <p style="font-size: 1rem; line-height: 1.7; color: color-mix(in srgb, var(--color-text) 70%, transparent); margin-bottom: 2rem;">{{ $product->description }}</p>
                    @endif

                    {{-- Variants --}}
                    @if ($product->variants && $product->variants->isNotEmpty())
                        <div style="margin-bottom: 2.5rem; border-top: 1px solid color-mix(in srgb, var(--color-text) 8%, transparent); padding-top: 1.5rem;">
                            <h3 style="font-size: 0.875rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: color-mix(in srgb, var(--color-text) 50%, transparent); margin-bottom: 1rem;">Available Options</h3>
                            <div style="display: flex; flex-direction: column; gap: 1rem;">
                                @foreach ($product->variants as $variant)
                                    <div>
                                        <p style="font-size: 0.8125rem; font-weight: 600; color: var(--color-text); margin-bottom: 0.5rem;">{{ $variant['name'] }}</p>
                                        <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                                            @foreach ($variant['options'] ?? [] as $option)
                                                <div style="display: flex; align-items: center; gap: 0.5rem; padding: 0.375rem 0.75rem; border: 1px solid color-mix(in srgb, var(--color-text) 12%, transparent); border-radius: var(--radius); font-size: 0.8125rem;">
                                                    @if (!empty($option['image']))
                                                        <img src="{{ Storage::url($option['image']) }}" alt="" style="width: 1.25rem; height: 1.25rem; border-radius: 9999px; object-fit: cover;">
                                                    @endif
                                                    <span>{{ $option['value'] }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Contact CTA --}}
                    <div style="border-top: 1px solid color-mix(in srgb, var(--color-text) 8%, transparent); padding-top: 1.5rem;">
                        @if ($business->whatsapp)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $business->whatsapp) }}?text=Hi! I'm interested in {{ $product->name }}"
                               class="btn btn-primary" target="_blank" onclick="navigator.sendBeacon('{{ route('track.click', [$business->id, 'whatsapp_click']) }}?source=product&url='+encodeURIComponent(location.href));">
                                Purchase via WhatsApp
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('themes::default.store.contact')
    @include('themes::default.store.footer')
</div>
