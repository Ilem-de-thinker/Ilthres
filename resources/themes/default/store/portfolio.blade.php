{{--
  Default theme · Store portfolio — featured products and media gallery.
--}}
<section id="portfolio" style="background: {{ $colors['text'] }}08;">
    <div class="container">
        <h2 class="section-title">Portfolio</h2>
        @php $media = $business->media()->where('collection', 'portfolio')->orWhereNull('collection')->latest()->take(8)->get(); @endphp
        @if ($media->isNotEmpty())
            <div class="grid grid-4">
                @foreach ($media as $item)
                    <div style="border-radius: var(--radius); overflow: hidden; background: {{ $colors['text'] }}08;">
                        @if (str_starts_with($item->mime_type, 'image/'))
                            <img src="{{ Storage::url($item->path) }}" alt="{{ $item->original_name }}"
                                 style="width: 100%; aspect-ratio: 1; object-fit: cover;">
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <p style="color: {{ $colors['text'] }}60;">No portfolio items yet.</p>
        @endif
    </div>
</section>
