{{-- Bold theme · Service hero — High-Impact Editorial: 12-col grid --}}
@if ($heroBanners->count())
    <section x-data="{ active: 0, total: {{ $heroBanners->count() }}, next() { this.active = (this.active + 1) % this.total; }, prev() { this.active = (this.active - 1 + this.total) % this.total; } }"
        @mouseenter="clearInterval($refs.timer)" @mouseleave="$refs.timer = setInterval(() => next(), 5000)"
        style="border-bottom: 4px solid #ffffff; min-height: 80vh; position: relative;">
        <div x-ref="timer" x-init="$nextTick(() => $refs.timer = setInterval(() => next(), 5000))"></div>

        @foreach ($heroBanners as $i => $banner)
            <div x-show="active === {{ $i }}" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                style="display: grid; grid-template-columns: repeat(12, 1fr); min-height: 80vh;">
                <div class="dot-pattern" style="grid-column: 1 / 8; padding: 64px; display: flex; flex-direction: column; justify-content: flex-end; border-right: 4px solid #ffffff; position: relative;">
                    <div style="position: absolute; top: 32px; left: 64px; border: 2px solid #ffffff; padding: 8px;">
                        <span style="font-family: 'Space Mono', monospace; font-size: 10px; letter-spacing: 0.15em; text-transform: uppercase; color: #e2e2e2;">ISSUE NO. {{ str_pad($i + 1, 3, '0', STR_PAD_LEFT) }} // BANNER</span>
                    </div>
                    <h1 style="font-family: 'Anton', sans-serif; font-size: clamp(2.5rem, 6vw, 5.25rem); line-height: 0.95; letter-spacing: -0.02em; margin-bottom: 2rem; color: #ffffff; text-transform: uppercase;">{{ $banner->message }}</h1>
                    @if ($banner->cta_text)
                        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                            <a href="{{ $banner->cta_url ?: '#services' }}" style="display: inline-flex; align-items: center; padding: 16px 40px; background: #ffffff; color: #131313; font-family: 'Space Mono', monospace; font-size: 14px; font-weight: 700; letter-spacing: 0.2em; text-transform: uppercase; border: 4px solid #ffffff; transition: all 0.15s; cursor: pointer;" onmouseover="this.style.background='#72ff70'; this.style.borderColor='#72ff70';" onmouseout="this.style.background='#ffffff'; this.style.borderColor='#ffffff';">{{ $banner->cta_text }}</a>
                        </div>
                    @endif
                </div>
                <div style="grid-column: 8 / 13; position: relative; overflow: hidden; cursor: crosshair;">
                    @if ($banner->image)
                        <img src="{{ Storage::url($banner->image) }}" alt="{{ $banner->message }}" style="width: 100%; height: 100%; object-fit: cover; filter: grayscale(0.3); transition: filter 0.5s;" onmouseover="this.style.filter='grayscale(0)'" onmouseout="this.style.filter='grayscale(0.3)'">
                    @else
                        <div class="dot-pattern" style="width: 100%; height: 100%; min-height: 80vh;"></div>
                    @endif
                    <div style="position: absolute; bottom: 0; right: 0; background: #ffffff; color: #131313; padding: 16px; font-family: 'Space Mono', monospace; font-size: 10px; letter-spacing: 0.15em; text-transform: uppercase;">VIEW_DETAILS_{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</div>
                </div>
            </div>
        @endforeach

        @if ($heroBanners->count() > 1)
            <button @click="prev()" style="position: absolute; left: 24px; top: 50%; transform: translateY(-50%); z-index: 10; background: rgba(0,0,0,0.4); border: 2px solid #ffffff; width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #ffffff; transition: all 0.15s;" onmouseover="this.style.background='#ffffff'; this.style.color='#131313';" onmouseout="this.style.background='rgba(0,0,0,0.4)'; this.style.color='#ffffff';"><span class="material-symbols-outlined" style="font-size: 20px;">chevron_left</span></button>
            <button @click="next()" style="position: absolute; right: 24px; top: 50%; transform: translateY(-50%); z-index: 10; background: rgba(0,0,0,0.4); border: 2px solid #ffffff; width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #ffffff; transition: all 0.15s;" onmouseover="this.style.background='#ffffff'; this.style.color='#131313';" onmouseout="this.style.background='rgba(0,0,0,0.4)'; this.style.color='#ffffff';"><span class="material-symbols-outlined" style="font-size: 20px;">chevron_right</span></button>
            <div style="position: absolute; bottom: 2rem; left: 50%; transform: translateX(-50%); z-index: 10; display: flex; gap: 0.5rem;">
                @foreach ($heroBanners as $i => $banner)
                    <button @click="active = {{ $i }}" style="height: 4px; border: none; cursor: pointer; transition: all 0.3s; padding: 0;" :style="active === {{ $i }} ? 'width: 36px; background: #ffffff;' : 'width: 16px; background: rgba(255,255,255,0.3);'"></button>
                @endforeach
            </div>
        @endif
    </section>
@else
    <section style="display: grid; grid-template-columns: repeat(12, 1fr); border-bottom: 4px solid #ffffff; min-height: 80vh;">
        <div class="dot-pattern" style="grid-column: 1 / 8; padding: 64px; display: flex; flex-direction: column; justify-content: flex-end; border-right: 4px solid #ffffff; position: relative;">
            <div style="position: absolute; top: 32px; left: 64px; border: 2px solid #ffffff; padding: 8px;">
                <span style="font-family: 'Space Mono', monospace; font-size: 10px; letter-spacing: 0.15em; text-transform: uppercase; color: #e2e2e2;">ISSUE NO. 001 // PROFILE</span>
            </div>
            <h1 style="font-family: 'Anton', sans-serif; font-size: clamp(2.5rem, 6vw, 5.25rem); line-height: 0.95; letter-spacing: -0.02em; margin-bottom: 2rem; color: #ffffff; text-transform: uppercase;">{{ $business->name }}</h1>
            @if ($business->description)
                <p style="font-family: 'Archivo Narrow', sans-serif; font-size: 1.125rem; line-height: 1.7; color: #e2e2e2; opacity: 0.85; margin-bottom: 2.5rem; max-width: 540px;">{{ $business->description }}</p>
            @endif
            <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                <a href="#services" style="display: inline-flex; align-items: center; padding: 16px 40px; background: #ffffff; color: #131313; font-family: 'Space Mono', monospace; font-size: 14px; font-weight: 700; letter-spacing: 0.2em; text-transform: uppercase; border: 4px solid #ffffff; transition: all 0.15s; cursor: pointer;" onmouseover="this.style.background='#72ff70'; this.style.borderColor='#72ff70';" onmouseout="this.style.background='#ffffff'; this.style.borderColor='#ffffff';">OUR_SERVICES</a>
                <a href="#contact" style="display: inline-flex; align-items: center; padding: 16px 40px; background: transparent; color: #ffffff; font-family: 'Space Mono', monospace; font-size: 14px; font-weight: 700; letter-spacing: 0.2em; text-transform: uppercase; border: 4px solid #ffffff; transition: all 0.15s; cursor: pointer;" onmouseover="this.style.background='rgba(255,255,255,0.1)';" onmouseout="this.style.background='transparent';">GET_IN_TOUCH</a>
            </div>
        </div>
        <div style="grid-column: 8 / 13; position: relative; overflow: hidden; cursor: crosshair;">
            @if ($business->cover)
                <img src="{{ Storage::url($business->cover) }}" alt="{{ $business->name }}" style="width: 100%; height: 100%; object-fit: cover; filter: grayscale(0.3); transition: filter 0.5s;" onmouseover="this.style.filter='grayscale(0)'" onmouseout="this.style.filter='grayscale(0.3)'">
            @else
                <div class="dot-pattern" style="width: 100%; height: 100%; min-height: 80vh;"></div>
            @endif
            <div style="position: absolute; bottom: 0; right: 0; background: #ffffff; color: #131313; padding: 16px; font-family: 'Space Mono', monospace; font-size: 10px; letter-spacing: 0.15em; text-transform: uppercase;">VIEW_SPECIFICATIONS_01</div>
        </div>
    </section>
@endif
