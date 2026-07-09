{{--
  Modern theme · Service hero — gradient background with large heading and cover.
--}}
<section style="background: linear-gradient(135deg, {{ $colors['primary'] }}09 0%, {{ $colors['secondary'] }}09 50%, {{ $colors['primary'] }}05 100%); position: relative; overflow: hidden; border-bottom: 1px solid rgba(0, 0, 0, 0.02);">
    {{-- Decorative background glow circles --}}
    <div style="position: absolute; width: 400px; height: 400px; border-radius: 9999px; background: var(--color-primary); filter: blur(120px); opacity: 0.06; top: -100px; right: -50px; pointer-events: none;"></div>
    <div style="position: absolute; width: 300px; height: 300px; border-radius: 9999px; background: var(--color-secondary); filter: blur(100px); opacity: 0.05; bottom: -50px; left: -50px; pointer-events: none;"></div>

    <div class="container" style="display: flex; align-items: center; gap: 4rem; min-height: 60vh; padding-top: 4rem; padding-bottom: 4rem; position: relative; flex-wrap: wrap;">
        <div style="flex: 1.2; min-width: 320px;">
            <h1 style="font-size: 3.5rem; font-weight: 800; line-height: 1.1; margin-bottom: 1.5rem; letter-spacing: -0.03em;">
                Premium Care from <br>
                <span style="background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                    {{ $business->name }}
                </span>
            </h1>
            @if ($business->description)
                <p style="font-size: 1.125rem; color: rgba(0,0,0,0.6); line-height: 1.7; margin-bottom: 2.5rem; max-width: 550px; font-weight: 400;">
                    {{ $business->description }}
                </p>
            @endif
            <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                <a href="#services" class="btn btn-primary">Book a Service</a>
                <a href="#contact" class="btn btn-outline">Get in Touch</a>
            </div>
        </div>
        @if ($business->cover)
            <div style="flex: 1; min-width: 320px; display: flex; justify-content: center; position: relative;">
                <div style="position: absolute; inset: -8px; background: linear-gradient(135deg, var(--color-primary), var(--color-secondary)); border-radius: calc(var(--radius) + 8px); opacity: 0.15; filter: blur(8px); z-index: 1;"></div>
                <img src="{{ Storage::url($business->cover) }}" alt="{{ $business->name }}"
                     style="border-radius: var(--radius); width: 100%; max-width: 480px; height: 350px; object-fit: cover; border: 1px solid rgba(255, 255, 255, 0.4); box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08); position: relative; z-index: 2;">
            </div>
        @endif
    </div>
</section>
