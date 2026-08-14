{{-- Modern theme · Store hero — Lumina SaaS gradient hero with glass carousel --}}
<section style="position: relative; overflow: hidden; min-height: 70vh; display: flex; align-items: center;">
    {{-- Decorative background circles --}}
    <div style="position: absolute; inset: 0; overflow: hidden; pointer-events: none;">
        <div style="position: absolute; top: -120px; left: -120px; width: 400px; height: 400px; background: radial-gradient(circle, rgba(70,72,212,0.12) 0%, transparent 70%); border-radius: 50%;"></div>
        <div style="position: absolute; bottom: -80px; right: -80px; width: 300px; height: 300px; background: radial-gradient(circle, rgba(0,101,145,0.1) 0%, transparent 70%); border-radius: 50%;"></div>
        <div style="position: absolute; top: 40%; right: 20%; width: 200px; height: 200px; background: radial-gradient(circle, rgba(185,5,56,0.06) 0%, transparent 70%); border-radius: 50%;"></div>
    </div>

    @if ($heroBanners->count())
        <div x-data="{ active: 0, total: {{ $heroBanners->count() }}, next() { this.active = (this.active + 1) % this.total; }, prev() { this.active = (this.active - 1 + this.total) % this.total; } }"
            @mouseenter="clearInterval($refs.timer)" @mouseleave="$refs.timer = setInterval(() => next(), 5000)"
            style="width: 100%; position: relative;">
            <div x-ref="timer" x-init="$nextTick(() => $refs.timer = setInterval(() => next(), 5000))"></div>

            @foreach ($heroBanners as $i => $banner)
                <div x-show="active === {{ $i }}" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                    style="width: 100%; padding: 72px 0;">
                    <div style="max-width: 1280px; margin: 0 auto; padding: 0 40px; display: grid; grid-template-columns: repeat(12, 1fr); gap: 24px; align-items: center;">
                        <div style="grid-column: 1 / 8;">
                            <span class="label" style="color: #4648d4; margin-bottom: 16px; display: block;">Banner {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            <h1 style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: clamp(2.25rem, 5vw, 4rem); letter-spacing: -0.03em; line-height: 1.05; color: #1a1a2e; margin-bottom: 24px;">
                                {{ $banner->message }}
                            </h1>
                            @if ($banner->cta_text)
                                <a href="{{ $banner->cta_url ?: '#products' }}" class="btn-primary" style="display: inline-flex;">
                                    {{ $banner->cta_text }}
                                    <span class="material-symbols-outlined" style="font-size: 18px;">arrow_right_alt</span>
                                </a>
                            @endif
                        </div>
                        <div style="grid-column: 8 / 13;">
                            <div class="glass" style="overflow: hidden; aspect-ratio: 4/3;">
                                @if ($banner->image)
                                    <img src="{{ Storage::url($banner->image) }}" alt="{{ $banner->message }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 16px;">
                                @else
                                    <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #4648d4;">
                                        <span class="material-symbols-outlined" style="font-size: 48px;">image</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            @if ($heroBanners->count() > 1)
                <button @click="prev()" style="position: absolute; left: 20px; top: 50%; transform: translateY(-50%); z-index: 10; width: 44px; height: 44px; background: rgba(255,255,255,0.7); backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #1a1a2e; transition: all 0.2s;" onmouseover="this.style.background='#4648d4'; this.style.color='#ffffff'" onmouseout="this.style.background='rgba(255,255,255,0.7)'; this.style.color='#1a1a2e'">
                    <span class="material-symbols-outlined" style="font-size: 20px;">chevron_left</span>
                </button>
                <button @click="next()" style="position: absolute; right: 20px; top: 50%; transform: translateY(-50%); z-index: 10; width: 44px; height: 44px; background: rgba(255,255,255,0.7); backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #1a1a2e; transition: all 0.2s;" onmouseover="this.style.background='#4648d4'; this.style.color='#ffffff'" onmouseout="this.style.background='rgba(255,255,255,0.7)'; this.style.color='#1a1a2e'">
                    <span class="material-symbols-outlined" style="font-size: 20px;">chevron_right</span>
                </button>

                <div style="position: absolute; bottom: 1.5rem; left: 50%; transform: translateX(-50%); z-index: 10; display: flex; gap: 8px;">
                    @foreach ($heroBanners as $i => $banner)
                        <button @click="active = {{ $i }}" style="border: none; cursor: pointer; transition: all 0.3s; padding: 0; border-radius: 999px;"
                            :style="active === {{ $i }} ? 'width: 24px; height: 6px; background: #4648d4;' : 'width: 6px; height: 6px; background: rgba(70,72,212,0.3);'"></button>
                    @endforeach
                </div>
            @endif
        </div>
    @else
        <div style="max-width: 1280px; margin: 0 auto; padding: 0 40px; width: 100%; padding-top: 72px; padding-bottom: 64px;">
            <div style="max-width: 720px;">
                <span class="label" style="color: #4648d4; margin-bottom: 20px; display: block;">Welcome</span>
                <h1 style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: clamp(2.5rem, 6vw, 4.5rem); letter-spacing: -0.03em; line-height: 1.05; color: #1a1a2e; margin-bottom: 24px;">
                    {{ $business->name }}
                </h1>
                @if ($business->description)
                    <p style="font-size: 1.125rem; line-height: 1.7; color: #4b5563; margin-bottom: 40px; max-width: 540px;">
                        {{ $business->description }}
                    </p>
                @endif
                <div style="display: flex; gap: 16px; flex-wrap: wrap;">
                    <a href="#products" class="btn-primary">
                        Explore Products
                        <span class="material-symbols-outlined" style="font-size: 18px;">arrow_right_alt</span>
                    </a>
                    <a href="{{ route('business.contact', $business) }}" class="btn-secondary">
                        <span class="material-symbols-outlined" style="font-size: 18px;">chat</span> Get in Touch
                    </a>
                </div>
            </div>
        </div>
    @endif
</section>
