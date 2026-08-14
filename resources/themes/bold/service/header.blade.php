{!! html_entity_decode('<style>[x-cloak] { display: none !important; }</style>') !!}
{{-- Bold theme · Service header — High-Impact Editorial --}}
<header x-data="{ mobileOpen: false }" x-effect="document.body.style.overflow = mobileOpen ? 'hidden' : '';" class="fixed top-0 left-0 right-0 z-50" id="site-header" style="background: #131313; border-bottom: 4px solid #ffffff;">
    <nav style="max-width: 1280px; margin: 0 auto; padding: 0 64px; display: flex; align-items: center; justify-content: space-between; height: 96px;">
        <a href="{{ route('business.site', $business) }}" style="display: flex; align-items: center; gap: 16px; text-decoration: none; flex-shrink: 0;">
            @if($business->logo)
                <img src="{{ Storage::url($business->logo) }}" alt="{{ $business->name }}" style="height: 40px; width: 40px; object-fit: cover; border: 2px solid #ffffff;">
            @else
                <div style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: bold; background: #ffffff; color: #131313; font-family: 'Anton', sans-serif;">
                    {{ substr($business->name, 0, 1) }}
                </div>
            @endif
            <span style="font-family: 'Anton', sans-serif; font-size: 1.25rem; color: #ffffff; text-transform: uppercase; letter-spacing: 0.02em;">
                {{ $business->name }}
            </span>
        </a>

        <ul class="hidden md:flex" style="display: flex; align-items: center; gap: 4px; list-style: none;">
            <li><a href="{{ route('business.about', $business) }}" style="padding: 8px 16px; font-family: 'Space Mono', monospace; font-size: 14px; font-weight: 700; letter-spacing: 0.2em; text-transform: uppercase; color: #c4c7c8; text-decoration: none; transition: color 0.15s;" onmouseover="this.style.color='#ffffff'" onmouseout="this.style.color='#c4c7c8'">ABOUT</a></li>
            <li><a href="{{ route('business.services', $business) }}" style="padding: 8px 16px; font-family: 'Space Mono', monospace; font-size: 14px; font-weight: 700; letter-spacing: 0.2em; text-transform: uppercase; color: #72ff70; text-decoration: none; border-bottom: 2px solid #72ff70;">SERVICES</a></li>
            @if($business->portfolio && count($business->portfolio) > 0)
            <li><a href="{{ route('business.portfolio', $business) }}" style="padding: 8px 16px; font-family: 'Space Mono', monospace; font-size: 14px; font-weight: 700; letter-spacing: 0.2em; text-transform: uppercase; color: #c4c7c8; text-decoration: none; transition: color 0.15s;" onmouseover="this.style.color='#ffffff'" onmouseout="this.style.color='#c4c7c8'">PORTFOLIO</a></li>
            @endif
            <li><a href="{{ route('business.contact', $business) }}" style="padding: 8px 16px; font-family: 'Space Mono', monospace; font-size: 14px; font-weight: 700; letter-spacing: 0.2em; text-transform: uppercase; color: #c4c7c8; text-decoration: none; transition: color 0.15s;" onmouseover="this.style.color='#ffffff'" onmouseout="this.style.color='#c4c7c8'">CONTACT</a></li>
        </ul>

        <div style="display: flex; align-items: center; gap: 16px;">
            <a href="{{ route('business.contact', $business) }}" class="hidden md:inline-flex" style="align-items: center; padding: 12px 24px; background: #ffffff; color: #131313; font-family: 'Space Mono', monospace; font-size: 14px; font-weight: 700; letter-spacing: 0.2em; text-transform: uppercase; border: 2px solid #ffffff; transition: all 0.15s; text-decoration: none;"
               onmouseover="this.style.background='#72ff70'; this.style.borderColor='#72ff70';"
               onmouseout="this.style.background='#ffffff'; this.style.borderColor='#ffffff';">
                GET_IN_TOUCH
            </a>
            <button @click="mobileOpen = !mobileOpen" class="md:hidden" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background: none; border: none; cursor: pointer; position: relative; z-index: 60;">
                <div style="width: 20px; height: 14px; position: relative;">
                    <span :style="mobileOpen ? 'transform: rotate(45deg); top: 6px;' : 'transform: rotate(0); top: 0;'" style="position: absolute; left: 0; width: 20px; height: 2px; background:#ffffff; transition: all 0.3s ease; transform-origin: center;"></span>
                    <span :style="mobileOpen ? 'opacity: 0; transform: scaleX(0);' : 'opacity: 1; transform: scaleX(1);'" style="position: absolute; left: 0; top: 6px; width: 20px; height: 2px; background:#ffffff; transition: all 0.3s ease; transform-origin: center;"></span>
                    <span :style="mobileOpen ? 'transform: rotate(-45deg); top: 6px;' : 'transform: rotate(0); top: 12px;'" style="position: absolute; left: 0; width: 20px; height: 2px; background:#ffffff; transition: all 0.3s ease; transform-origin: center;"></span>
                </div>
            </button>
        </div>
    </nav>

    {{-- Mobile Backdrop --}}
    <div x-show="mobileOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         @click="mobileOpen = false"
         style="position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 55; backdrop-filter: blur(4px);"
         x-cloak></div>

    {{-- Mobile Slide-in Panel --}}
    <div x-show="mobileOpen" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
         @click.away="mobileOpen = false"
         style="position: fixed; top: 0; right: 0; bottom: 0; width: 300px; max-width: 85vw; z-index: 56; overflow-y: auto; padding: 80px 32px 32px; background: #131313; border-left: 4px solid #ffffff;"
         x-cloak>
        <button @click="mobileOpen = false" style="position: absolute; top: 24px; right: 24px; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; background: rgba(255,255,255,0.1); border: none; cursor: pointer; color: #ffffff;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
        <nav style="display: flex; flex-direction: column; gap: 0;">
            <a href="{{ route('business.about', $business) }}" @click="mobileOpen = false" style="padding: 14px 0; font-family: 'Space Mono', monospace; font-size: 14px; font-weight: 700; letter-spacing: 0.2em; text-transform: uppercase; color: #c4c7c8; text-decoration: none; border-bottom: 1px solid rgba(255,255,255,0.1); transition: color 0.15s;" onmouseover="this.style.color='#72ff70'" onmouseout="this.style.color='#c4c7c8'">ABOUT</a>
            <a href="{{ route('business.services', $business) }}" @click="mobileOpen = false" style="padding: 14px 0; font-family: 'Space Mono', monospace; font-size: 14px; font-weight: 700; letter-spacing: 0.2em; text-transform: uppercase; color: #72ff70; text-decoration: none; border-bottom: 1px solid rgba(255,255,255,0.1);">SERVICES</a>
            @if($business->portfolio && count($business->portfolio) > 0)
            <a href="{{ route('business.portfolio', $business) }}" @click="mobileOpen = false" style="padding: 14px 0; font-family: 'Space Mono', monospace; font-size: 14px; font-weight: 700; letter-spacing: 0.2em; text-transform: uppercase; color: #c4c7c8; text-decoration: none; border-bottom: 1px solid rgba(255,255,255,0.1); transition: color 0.15s;" onmouseover="this.style.color='#72ff70'" onmouseout="this.style.color='#c4c7c8'">PORTFOLIO</a>
            @endif
            <a href="{{ route('business.contact', $business) }}" @click="mobileOpen = false" style="padding: 14px 0; font-family: 'Space Mono', monospace; font-size: 14px; font-weight: 700; letter-spacing: 0.2em; text-transform: uppercase; color: #c4c7c8; text-decoration: none; transition: color 0.15s;" onmouseover="this.style.color='#72ff70'" onmouseout="this.style.color='#c4c7c8'">CONTACT</a>
        </nav>
    </div>
</header>

<script>
(function() {
    const header = document.getElementById('site-header');
    function updateHeader() {
        if (window.scrollY > 50) {
            header.style.background = '#131313ee';
            header.style.backdropFilter = 'blur(20px)';
            header.style.webkitBackdropFilter = 'blur(20px)';
        } else {
            header.style.background = '#131313';
            header.style.backdropFilter = 'none';
            header.style.webkitBackdropFilter = 'none';
        }
    }
    updateHeader();
    window.addEventListener('scroll', updateHeader, { passive: true });
})();
</script>
