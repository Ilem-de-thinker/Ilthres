{{-- Default theme · Service hero — Serene Ivory --}}
@if ($heroBanners->count())
    <section x-data="{ active: 0, total: {{ $heroBanners->count() }}, next() { this.active = (this.active + 1) % this.total; }, prev() { this.active = (this.active - 1 + this.total) % this.total; } }"
        @mouseenter="clearInterval($refs.timer)" @mouseleave="$refs.timer = setInterval(() => next(), 6000)"
        style="min-height: 70vh; position: relative; overflow: hidden;">
        <div x-ref="timer" x-init="$nextTick(() => $refs.timer = setInterval(() => next(), 6000))"></div>

        @foreach ($heroBanners as $i => $banner)
            <div x-show="active === {{ $i }}" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-500" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                style="position: absolute; inset: 0; display: grid; grid-template-columns: repeat(12, 1fr); min-height: 70vh;">
                <div style="grid-column: 1 / 7; padding: 64px; display: flex; flex-direction: column; justify-content: center; position: relative; z-index: 2;">
                    <h1 style="font-family: 'Playfair Display', serif; font-style: italic; font-size: clamp(2.25rem, 5vw, 4rem); line-height: 1.1; margin-bottom: 1.5rem; color: #1c1c18;">{{ $banner->message }}</h1>
                    @if ($banner->cta_text)
                        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                            <a href="{{ $banner->cta_url ?: '#services' }}"
                                style="display: inline-flex; align-items: center; padding: 16px 40px; background: #1c1c18; color: #fcf9f2; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.6875rem; font-weight: 600; letter-spacing: 0.05em; text-transform: uppercase; border: 0.5px solid #1c1c18; border-radius: 0.25rem; transition: all 0.2s; cursor: pointer; text-decoration: none;"
                                onmouseover="this.style.background='#454843'; this.style.borderColor='#454843';"
                                onmouseout="this.style.background='#1c1c18'; this.style.borderColor='#1c1c18';">{{ $banner->cta_text }}</a>
                        </div>
                    @endif
                </div>
                <div style="grid-column: 7 / 13; position: relative; overflow: hidden; display: flex; align-items: center; padding: 32px;">
                    @if ($banner->image)
                        <div class="thin-frame" style="width: 100%; overflow: hidden; border-radius: 0.5rem;">
                            <img src="{{ Storage::url($banner->image) }}" alt="{{ $banner->message }}" style="width: 100%; aspect-ratio: 4/5; object-fit: cover; display: block;">
                        </div>
                    @else
                        <div style="width: 100%; aspect-ratio: 4/5; background: #f1eee7; display: flex; align-items: center; justify-content: center; border-radius: 0.5rem;">
                            <span class="material-symbols-outlined" style="color: #c5c7c1; font-size: 64px;">image</span>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach

        @if ($heroBanners->count() > 1)
            <button @click="prev()" style="position: absolute; left: 24px; top: 50%; transform: translateY(-50%); z-index: 10; background: rgba(252,249,242,0.9); border: 0.5px solid #E5E0D5; width: 44px; height: 44px; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #1c1c18; transition: all 0.2s; border-radius: 0.25rem;" onmouseover="this.style.background='#1c1c18'; this.style.color='#fcf9f2';" onmouseout="this.style.background='rgba(252,249,242,0.9)'; this.style.color='#1c1c18';">
                <span class="material-symbols-outlined" style="font-size: 20px;">chevron_left</span>
            </button>
            <button @click="next()" style="position: absolute; right: 24px; top: 50%; transform: translateY(-50%); z-index: 10; background: rgba(252,249,242,0.9); border: 0.5px solid #E5E0D5; width: 44px; height: 44px; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #1c1c18; transition: all 0.2s; border-radius: 0.25rem;" onmouseover="this.style.background='#1c1c18'; this.style.color='#fcf9f2';" onmouseout="this.style.background='rgba(252,249,242,0.9)'; this.style.color='#1c1c18';">
                <span class="material-symbols-outlined" style="font-size: 20px;">chevron_right</span>
            </button>
            <div style="position: absolute; bottom: 2rem; left: 50%; transform: translateX(-50%); z-index: 10; display: flex; gap: 0.5rem;">
                @foreach ($heroBanners as $i => $banner)
                    <button @click="active = {{ $i }}" style="height: 2px; border: none; cursor: pointer; transition: all 0.3s; padding: 0; border-radius: 1px;"
                        :style="active === {{ $i }} ? 'width: 32px; background: #1c1c18;' : 'width: 12px; background: #c5c7c1;'"></button>
                @endforeach
            </div>
        @endif
    </section>
@else
    <section style="display: grid; grid-template-columns: repeat(12, 1fr); min-height: 70vh;">
        <div style="grid-column: 1 / 7; padding: 64px; display: flex; flex-direction: column; justify-content: center;">
            <h1 style="font-family: 'Playfair Display', serif; font-style: italic; font-size: clamp(2.25rem, 5vw, 4rem); line-height: 1.1; margin-bottom: 1.5rem; color: #1c1c18;">{{ $business->name }}</h1>
            @if ($business->description)
                <p style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1rem; line-height: 1.7; color: #454843; margin-bottom: 2.5rem; max-width: 480px;">{{ $business->description }}</p>
            @endif
            <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                <a href="#services"
                    style="display: inline-flex; align-items: center; padding: 16px 40px; background: #1c1c18; color: #fcf9f2; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.6875rem; font-weight: 600; letter-spacing: 0.05em; text-transform: uppercase; border: 0.5px solid #1c1c18; border-radius: 0.25rem; transition: all 0.2s; cursor: pointer; text-decoration: none;"
                    onmouseover="this.style.background='#454843'; this.style.borderColor='#454843';"
                    onmouseout="this.style.background='#1c1c18'; this.style.borderColor='#1c1c18';">OUR SERVICES</a>
                <a href="{{ route('business.contact', $business) }}"
                    style="display: inline-flex; align-items: center; padding: 16px 40px; background: transparent; color: #1c1c18; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.6875rem; font-weight: 600; letter-spacing: 0.05em; text-transform: uppercase; border: 0.5px solid #5e5f5c; border-radius: 0.25rem; transition: all 0.2s; cursor: pointer; text-decoration: none;"
                    onmouseover="this.style.background='#1c1c18'; this.style.color='#fcf9f2'; this.style.borderColor='#1c1c18';"
                    onmouseout="this.style.background='transparent'; this.style.color='#1c1c18'; this.style.borderColor='#5e5f5c';">GET IN TOUCH</a>
            </div>
        </div>
        <div style="grid-column: 7 / 13; position: relative; overflow: hidden; display: flex; align-items: center; padding: 32px;">
            @if ($business->cover)
                <div class="thin-frame" style="width: 100%; overflow: hidden; border-radius: 0.5rem;">
                    <img src="{{ Storage::url($business->cover) }}" alt="{{ $business->name }}" style="width: 100%; aspect-ratio: 4/5; object-fit: cover; display: block;">
                </div>
            @else
                <div style="width: 100%; aspect-ratio: 4/5; background: #f1eee7; display: flex; align-items: center; justify-content: center; border-radius: 0.5rem;">
                    <span class="material-symbols-outlined" style="color: #c5c7c1; font-size: 64px;">image</span>
                </div>
            @endif
        </div>
    </section>
@endif
