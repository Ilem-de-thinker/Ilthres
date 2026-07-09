{{--
  Modern theme · Store portfolio — masonry-style gallery grid.
--}}
<section id="portfolio" style="background: #fff;">
    <div class="container">
        <h2 class="section-title">Portfolio</h2>
        <p class="section-subtitle">Featured products and highlights</p>
        @php $media = $business->media()->where('collection', 'portfolio')->orWhereNull('collection')->latest()->take(8)->get(); @endphp
        @if ($media->isNotEmpty())
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem;">
                @foreach ($media as $item)
                    <div class="card" style="aspect-ratio: 1;">
                        @if (str_starts_with($item->mime_type, 'image/'))
                            <img src="{{ Storage::url($item->path) }}" alt="{{ $item->original_name }}"
                                 style="width: 100%; height: 100%; object-fit: cover;">
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <p style="color: {{ $colors['text'] }}60;">No portfolio items yet.</p>
        @endif
    </div>
</section>
