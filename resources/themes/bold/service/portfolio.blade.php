{{--
  Bold theme · Service portfolio — clean grid layout.
--}}
<section id="portfolio" style="background: color-mix(in srgb, var(--color-text) 2%, transparent);">
    <div class="container">
        <h2 class="section-title">Portfolio</h2>
        <p class="section-subtitle">Visual highlights of our work</p>
        
        @php 
            $media = $business->media()->where('collection', 'portfolio')->orWhereNull('collection')->latest()->take(6)->get(); 
        @endphp
        
        @if ($media->isNotEmpty())
            <div class="grid grid-3">
                @foreach ($media as $item)
                    @if (str_starts_with($item->mime_type, 'image/'))
                        <div style="overflow: hidden; border: 2px solid var(--color-text); border-radius: var(--radius); aspect-ratio: 4/3; background: var(--color-background);">
                            <img src="{{ Storage::url($item->path) }}" alt="{{ $item->original_name }}"
                                 style="width: 100%; height: 100%; object-fit: cover; transition: opacity 0.2s ease; border: none; border-radius: 0;"
                                 onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                        </div>
                    @endif
                @endforeach
            </div>
        @else
            <p style="color: color-mix(in srgb, var(--color-text) 50%, transparent); font-weight: 600; text-transform: uppercase; font-size: 0.875rem;">
                No portfolio items listed yet.
            </p>
        @endif
    </div>
</section>
