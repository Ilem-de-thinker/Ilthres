{{-- Heritage theme · Store hero --}}
@if ($heroBanners->count())
    <section x-data="{ active: 0, total: {{ $heroBanners->count() }}, next() { this.active = (this.active + 1) % this.total; }, prev() { this.active = (this.active - 1 + this.total) % this.total; } }"
        @mouseenter="clearInterval($refs.timer)" @mouseleave="$refs.timer = setInterval(() => next(), 6000)"
        style="position: relative; overflow: hidden; background: #000; min-height: 70vh;">
        <div x-ref="timer" x-init="$nextTick(() => $refs.timer = setInterval(() => next(), 6000))"></div>

        @foreach ($heroBanners as $i => $banner)
            <div x-show="active === {{ $i }}" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-500" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                style="position: absolute; inset: 0;">
                @if ($banner->image)
                    <img src="{{ Storage::url($banner->image) }}" alt="{{ $banner->message }}" style="width: 100%; height: 100%; object-fit: cover; position: absolute; inset: 0; opacity: 0.6;">
                    <div style="position: absolute; inset: 0; background: linear-gradient(180deg, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0.5) 100%);"></div>
                @else
                    <div style="position: absolute; inset: 0; background: #000;"></div>
                @endif

                <div style="max-width: 1280px; margin: 0 auto; padding: 0 64px; position: relative; z-index: 2; display: flex; align-items: center; min-height: 70vh;">
                    <div style="max-width: 700px;">
                        <div style="width: 48px; height: 0.5px; background: #735c00; margin-bottom: 2rem;"></div>
                        <h1 style="font-family: 'Playfair Display', serif; font-style: italic; font-weight: 500; font-size: 3.5rem; line-height: 1.1; margin-bottom: 1.5rem; color: #fbf9f9; letter-spacing: -0.02em;">
                            {{ $banner->message }}
                        </h1>
                        @if ($banner->cta_text)
                            <a href="{{ $banner->cta_url ?: '#products' }}"
                               style="display: inline-flex; align-items: center; padding: 0.875rem 2rem; font-family: 'Hanken Grotesk', sans-serif; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.12em; color: #fbf9f9; border: 0.5px solid #735c00; text-decoration: none; transition: all 0.3s ease; background: transparent;"
                               onmouseover="this.style.background='#735c00'; this.style.borderColor='#735c00';"
                               onmouseout="this.style.background='transparent'; this.style.borderColor='#735c00';">
                                {{ $banner->cta_text }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach

        @if ($heroBanners->count() > 1)
            <button @click="prev()" style="position: absolute; left: 2rem; top: 50%; transform: translateY(-50%); z-index: 10; background: transparent; border: 0.5px solid rgba(251,249,249,0.3); width: 44px; height: 44px; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #fbf9f9; transition: all 0.3s;" onmouseover="this.style.borderColor='#735c00'; this.style.color='#fed65b';" onmouseout="this.style.borderColor='rgba(251,249,249,0.3)'; this.style.color='#fbf9f9';">
                <span class="material-symbols-outlined" style="font-size: 20px;">chevron_left</span>
            </button>
            <button @click="next()" style="position: absolute; right: 2rem; top: 50%; transform: translateY(-50%); z-index: 10; background: transparent; border: 0.5px solid rgba(251,249,249,0.3); width: 44px; height: 44px; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #fbf9f9; transition: all 0.3s;" onmouseover="this.style.borderColor='#735c00'; this.style.color='#fed65b';" onmouseout="this.style.borderColor='rgba(251,249,249,0.3)'; this.style.color='#fbf9f9';">
                <span class="material-symbols-outlined" style="font-size: 20px;">chevron_right</span>
            </button>
            <div style="position: absolute; bottom: 2.5rem; left: 50%; transform: translateX(-50%); z-index: 10; display: flex; gap: 1rem;">
                @foreach ($heroBanners as $i => $banner)
                    <button @click="active = {{ $i }}" style="height: 1px; border: none; cursor: pointer; transition: all 0.4s; padding: 0;"
                        :style="active === {{ $i }} ? 'width: 40px; background: #fed65b;' : 'width: 20px; background: rgba(251,249,249,0.3);'"></button>
                @endforeach
            </div>
        @endif
    </section>
@else
    <section style="position: relative; overflow: hidden; background: #fbf9f9; min-height: 70vh; display: flex; align-items: center;">
        <div style="position: absolute; top: 0; right: 0; width: 300px; height: 300px; border: 0.5px solid #d4d0c8; transform: rotate(45deg) translate(50%, -50%); opacity: 0.3;"></div>
        <div style="position: absolute; bottom: 0; left: 0; width: 200px; height: 200px; border: 0.5px solid #d4d0c8; transform: rotate(45deg) translate(-50%, 50%); opacity: 0.3;"></div>

        <div style="max-width: 1280px; margin: 0 auto; padding: 0 64px; width: 100%; display: flex; align-items: center; min-height: 70vh; padding-top: 4rem; padding-bottom: 4rem; position: relative;">
            <div style="max-width: 720px;">
                <div style="width: 48px; height: 0.5px; background: #735c00; margin-bottom: 2rem;"></div>
                <h1 style="font-family: 'Playfair Display', serif; font-style: italic; font-weight: 500; font-size: 3.5rem; line-height: 1.1; margin-bottom: 1.5rem; letter-spacing: -0.02em;">
                    {{ $business->name }}
                </h1>
                @if ($business->description)
                    <p style="font-family: 'Hanken Grotesk', sans-serif; font-size: 1.0625rem; color: #666; line-height: 1.7; margin-bottom: 2.5rem; max-width: 520px;">
                        {{ $business->description }}
                    </p>
                @endif
                <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                    <a href="#products" class="heritage-btn-primary">Shop Collection</a>
                    <a href="#contact" class="heritage-btn-secondary">Get in Touch</a>
                </div>
            </div>
            @if ($business->cover)
                <div style="flex: 1; display: flex; justify-content: center; margin-left: 4rem;">
                    <div style="position: relative; padding: 16px; border: 0.5px solid #d4d0c8;">
                        <img src="{{ Storage::url($business->cover) }}" alt="{{ $business->name }}" style="width: 100%; max-width: 480px; height: 320px; object-fit: cover; display: block;">
                    </div>
                </div>
            @endif
        </div>
    </section>
@endif
