{{--
  Bold theme · Service hero — high-contrast editorial section.
--}}
<section style="background: var(--color-primary); color: var(--color-background); position: relative; overflow: hidden; border-bottom: 3px solid var(--color-text);">
    <div style="position: absolute; inset: 0; opacity: 0.8; background-image: radial-gradient(color-mix(in srgb, var(--color-background) 12%, transparent) 1.5px, transparent 1.5px); background-size: 20px 20px;"></div>
    
    <div class="container" style="display: flex; align-items: center; gap: 4rem; min-height: 55vh; padding-top: 4rem; padding-bottom: 4rem; position: relative; flex-wrap: wrap;">
        
        <div style="flex: 1.2; min-width: 320px;">
            <h1 style="font-size: 3.5rem; font-weight: 900; line-height: 1.05; letter-spacing: -0.03em; margin-bottom: 1.5rem; text-transform: uppercase; color: var(--color-background);">
                {{ $business->name }}
            </h1>
            @if ($business->description)
                <p style="font-size: 1.125rem; color: var(--color-background); opacity: 0.85; line-height: 1.7; margin-bottom: 2.5rem; max-width: 540px; font-weight: 400;">
                    {{ $business->description }}
                </p>
            @endif
            <div style="display: flex; gap: 1.25rem; flex-wrap: wrap;">
                <a href="#services" class="btn btn-primary" style="background: var(--color-background); color: var(--color-primary); border-color: var(--color-background);">
                    Book Now
                </a>
                <a href="#contact" class="btn btn-outline" style="background: transparent; color: var(--color-background); border-color: var(--color-background);">
                    Get In Touch
                </a>
            </div>
        </div>
        
        @if ($business->cover)
            <div style="flex: 1; min-width: 320px; display: flex; justify-content: center;">
                <div style="position: relative;">
                    <img src="{{ Storage::url($business->cover) }}" alt="{{ $business->name }}"
                         style="width: 100%; max-width: 460px; height: 320px; object-fit: cover; border: 2px solid var(--color-background); border-radius: var(--radius);">
                </div>
            </div>
        @endif
        
    </div>
</section>
