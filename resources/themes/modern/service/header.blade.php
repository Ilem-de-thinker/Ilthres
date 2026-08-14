{!! html_entity_decode('<style>[x-cloak] { display: none !important; }</style>') !!}
{{-- Modern theme · Service header — Lumina SaaS fixed glass nav --}}
<header x-data="{ mobileOpen: false }" x-effect="document.body.style.overflow = mobileOpen ? 'hidden' : '';" class="fixed top-0 left-0 right-0 z-50" id="site-header" style="background: rgba(255,255,255,0.7); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border-bottom: 1px solid rgba(255,255,255,0.2);">
    <nav style="max-width: 1280px; margin: 0 auto; padding: 0 40px; display: flex; align-items: center; justify-content: space-between; height: 72px;">
        <a href="{{ route('business.site', $business) }}" style="display: flex; align-items: center; gap: 12px; text-decoration: none; flex-shrink: 0;">
            @if($business->logo)
                <img src="{{ Storage::url($business->logo) }}" alt="{{ $business->name }}" style="height: 36px; width: 36px; object-fit: cover; border-radius: 8px;">
            @else
                <div style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 800; background: linear-gradient(135deg, #4648d4, #006591); color: #ffffff; border-radius: 8px; font-family: 'Plus Jakarta Sans', sans-serif;">
                    {{ substr($business->name, 0, 1) }}
                </div>
            @endif
            <span style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.125rem; font-weight: 700; color: #1a1a2e;">
                {{ $business->name }}
            </span>
        </a>

        <ul class="hidden md:flex" style="display: flex; align-items: center; gap: 2px; list-style: none;">
            <li><a href="{{ route('business.about', $business) }}" style="padding: 8px 16px; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.875rem; font-weight: 500; color: #6b7280; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#4648d4'" onmouseout="this.style.color='#6b7280'">About</a></li>
            @if($business->portfolio && count($business->portfolio) > 0)
            <li><a href="{{ route('business.portfolio', $business) }}" style="padding: 8px 16px; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.875rem; font-weight: 500; color: #6b7280; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#4648d4'" onmouseout="this.style.color='#6b7280'">Portfolio</a></li>
            @endif
            <li><a href="{{ route('business.services', $business) }}" style="padding: 8px 16px; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.875rem; font-weight: 600; color: #4648d4; text-decoration: none; border-bottom: 2px solid #4648d4;">Services</a></li>
            <li><a href="{{ route('business.contact', $business) }}" style="padding: 8px 16px; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.875rem; font-weight: 500; color: #6b7280; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#4648d4'" onmouseout="this.style.color='#6b7280'">Contact</a></li>
        </ul>

        <div style="display: flex; align-items: center; gap: 12px;">
            <a href="{{ route('business.contact', $business) }}" class="hidden md:inline-flex btn-primary" style="padding: 12px 28px; font-size: 0.875rem;">
                <span class="material-symbols-outlined" style="font-size: 16px;">arrow_forward</span> Get in Touch
            </a>
            <button @click="mobileOpen = !mobileOpen" class="md:hidden" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background: rgba(255,255,255,0.7); border: 1px solid rgba(0,0,0,0.08); border-radius: 8px; cursor: pointer; position: relative; z-index: 60;">
                <div style="width: 20px; height: 14px; position: relative;">
                    <span :style="mobileOpen ? 'transform: rotate(45deg); top: 6px;' : 'transform: rotate(0); top: 0;'" style="position: absolute; left: 0; width: 20px; height: 2px; background:#1a1a2e; transition: all 0.3s ease; transform-origin: center;"></span>
                    <span :style="mobileOpen ? 'opacity: 0; transform: scaleX(0);' : 'opacity: 1; transform: scaleX(1);'" style="position: absolute; left: 0; top: 6px; width: 20px; height: 2px; background:#1a1a2e; transition: all 0.3s ease; transform-origin: center;"></span>
                    <span :style="mobileOpen ? 'transform: rotate(-45deg); top: 6px;' : 'transform: rotate(0); top: 12px;'" style="position: absolute; left: 0; width: 20px; height: 2px; background:#1a1a2e; transition: all 0.3s ease; transform-origin: center;"></span>
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
         style="position: fixed; top: 0; right: 0; bottom: 0; width: 300px; max-width: 85vw; z-index: 56; overflow-y: auto; padding: 80px 32px 32px; background: rgba(255,255,255,0.98); backdrop-filter: blur(20px);"
         x-cloak>
        <button @click="mobileOpen = false" style="position: absolute; top: 24px; right: 24px; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; background: rgba(0,0,0,0.04); border: none; border-radius: 8px; cursor: pointer; color: #1a1a2e;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
        <nav style="display: flex; flex-direction: column; gap: 0;">
            <a href="{{ route('business.about', $business) }}" @click="mobileOpen = false" style="padding: 14px 0; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.875rem; font-weight: 500; color: #1a1a2e; text-decoration: none; border-bottom: 1px solid rgba(0,0,0,0.06); transition: color 0.2s;" onmouseover="this.style.color='#4648d4'" onmouseout="this.style.color='#1a1a2e'">About</a>
            @if($business->portfolio && count($business->portfolio) > 0)
            <a href="{{ route('business.portfolio', $business) }}" @click="mobileOpen = false" style="padding: 14px 0; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.875rem; font-weight: 500; color: #1a1a2e; text-decoration: none; border-bottom: 1px solid rgba(0,0,0,0.06); transition: color 0.2s;" onmouseover="this.style.color='#4648d4'" onmouseout="this.style.color='#1a1a2e'">Portfolio</a>
            @endif
            <a href="{{ route('business.services', $business) }}" @click="mobileOpen = false" style="padding: 14px 0; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.875rem; font-weight: 600; color: #4648d4; text-decoration: none; border-bottom: 1px solid rgba(0,0,0,0.06);">Services</a>
            <a href="{{ route('business.contact', $business) }}" @click="mobileOpen = false" style="padding: 14px 0; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.875rem; font-weight: 500; color: #1a1a2e; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#4648d4'" onmouseout="this.style.color='#1a1a2e'">Contact</a>
        </nav>
    </div>
</header>

<script>
(function() {
    const header = document.getElementById('site-header');
    function updateHeader() {
        if (window.scrollY > 50) {
            header.style.boxShadow = '0 4px 24px rgba(70,72,212,0.08)';
        } else {
            header.style.boxShadow = 'none';
        }
    }
    updateHeader();
    window.addEventListener('scroll', updateHeader, { passive: true });
})();
</script>
