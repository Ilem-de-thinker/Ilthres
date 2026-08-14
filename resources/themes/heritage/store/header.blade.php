{!! html_entity_decode('<style>[x-cloak] { display: none !important; }</style>') !!}
{{-- Heritage theme · Store header --}}
<header x-data="{ mobileOpen: false }" x-effect="document.body.style.overflow = mobileOpen ? 'hidden' : '';" style="position: fixed; top: 0; left: 0; right: 0; z-index: 50; border-bottom: 0.5px solid #d4d0c8; transition: all 0.4s ease;" id="site-header">
    <nav style="max-width: 1280px; margin: 0 auto; padding: 0 64px; display: flex; align-items: center; justify-content: space-between; height: 80px;">
        <a href="{{ route('business.site', $business) }}" style="display: flex; align-items: center; gap: 12px; text-decoration: none; flex-shrink: 0;">
            @if($business->logo)
                <img src="{{ Storage::url($business->logo) }}" alt="{{ $business->name }}" style="height: 36px; width: 36px; object-fit: cover; border: 0.5px solid #d4d0c8;">
            @endif
            <span style="font-family: 'Playfair Display', serif; font-weight: 600; font-size: 1.125rem; color: var(--c-text); letter-spacing: -0.01em;">{{ $business->name }}</span>
        </a>

        <ul style="display: flex; align-items: center; gap: 2rem; list-style: none; margin: 0; padding: 0;">
            <li><a href="{{ route('business.about', $business) }}" class="heritage-nav-link" style="font-family: 'Hanken Grotesk', sans-serif; font-size: 0.6875rem; text-transform: uppercase; letter-spacing: 0.15em; font-weight: 500; color: var(--c-text); text-decoration: none; transition: color 0.2s; padding-bottom: 2px;">About</a></li>
            @if($business->portfolio && count($business->portfolio) > 0)
            <li><a href="{{ route('business.portfolio', $business) }}" class="heritage-nav-link" style="font-family: 'Hanken Grotesk', sans-serif; font-size: 0.6875rem; text-transform: uppercase; letter-spacing: 0.15em; font-weight: 500; color: var(--c-text); text-decoration: none; transition: color 0.2s; padding-bottom: 2px;">Portfolio</a></li>
            @endif
            @if($business->isProductStore())
            <li><a href="{{ route('business.products', $business) }}" class="heritage-nav-link" style="font-family: 'Hanken Grotesk', sans-serif; font-size: 0.6875rem; text-transform: uppercase; letter-spacing: 0.15em; font-weight: 500; color: var(--c-text); text-decoration: none; transition: color 0.2s; padding-bottom: 2px;">Products</a></li>
            @else
            <li><a href="{{ route('business.services', $business) }}" class="heritage-nav-link" style="font-family: 'Hanken Grotesk', sans-serif; font-size: 0.6875rem; text-transform: uppercase; letter-spacing: 0.15em; font-weight: 500; color: var(--c-text); text-decoration: none; transition: color 0.2s; padding-bottom: 2px;">Services</a></li>
            @endif
            <li><a href="{{ route('business.contact', $business) }}" class="heritage-nav-link" style="font-family: 'Hanken Grotesk', sans-serif; font-size: 0.6875rem; text-transform: uppercase; letter-spacing: 0.15em; font-weight: 500; color: var(--c-text); text-decoration: none; transition: color 0.2s; padding-bottom: 2px;">Contact</a></li>
        </ul>

        <div style="display: flex; align-items: center; gap: 1rem;">
            <a href="{{ route('business.contact', $business) }}" style="display: none; align-items: center; padding: 0.5rem 1.25rem; font-family: 'Hanken Grotesk', sans-serif; font-size: 0.6875rem; text-transform: uppercase; letter-spacing: 0.1em; font-weight: 600; background: #000; color: #fbf9f9; text-decoration: none; transition: background 0.3s;" class="heritage-desktop-cta">
                Inquire
            </a>
            <button @click="mobileOpen = !mobileOpen" class="md:hidden" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background: none; border: none; cursor: pointer; position: relative; z-index: 60;">
                <div style="width: 20px; height: 14px; position: relative;">
                    <span :style="mobileOpen ? 'transform: rotate(45deg); top: 6px;' : 'transform: rotate(0); top: 0;'" style="position: absolute; left: 0; width: 20px; height: 2px; background:var(--c-text); transition: all 0.3s ease; transform-origin: center;"></span>
                    <span :style="mobileOpen ? 'opacity: 0; transform: scaleX(0);' : 'opacity: 1; transform: scaleX(1);'" style="position: absolute; left: 0; top: 6px; width: 20px; height: 2px; background:var(--c-text); transition: all 0.3s ease; transform-origin: center;"></span>
                    <span :style="mobileOpen ? 'transform: rotate(-45deg); top: 6px;' : 'transform: rotate(0); top: 12px;'" style="position: absolute; left: 0; width: 20px; height: 2px; background:var(--c-text); transition: all 0.3s ease; transform-origin: center;"></span>
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
         style="position: fixed; top: 0; right: 0; bottom: 0; width: 300px; max-width: 85vw; z-index: 56; overflow-y: auto; padding: 80px 32px 32px; background: #fbf9f9; border-left: 0.5px solid #d4d0c8;"
         x-cloak>
        <button @click="mobileOpen = false" style="position: absolute; top: 24px; right: 24px; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; background: none; border: none; cursor: pointer; color: var(--c-text);">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
        <nav style="display: flex; flex-direction: column; gap: 0;">
            <a href="{{ route('business.about', $business) }}" @click="mobileOpen = false" style="padding: 14px 0; font-family: 'Hanken Grotesk', sans-serif; font-size: 0.6875rem; text-transform: uppercase; letter-spacing: 0.15em; font-weight: 500; color: var(--c-text); text-decoration: none; border-bottom: 0.5px solid #d4d0c8; transition: color 0.2s;" onmouseover="this.style.color='#735c00'" onmouseout="this.style.color='var(--c-text)'">About</a>
            @if($business->portfolio && count($business->portfolio) > 0)
            <a href="{{ route('business.portfolio', $business) }}" @click="mobileOpen = false" style="padding: 14px 0; font-family: 'Hanken Grotesk', sans-serif; font-size: 0.6875rem; text-transform: uppercase; letter-spacing: 0.15em; font-weight: 500; color: var(--c-text); text-decoration: none; border-bottom: 0.5px solid #d4d0c8; transition: color 0.2s;" onmouseover="this.style.color='#735c00'" onmouseout="this.style.color='var(--c-text)'">Portfolio</a>
            @endif
            @if($business->isProductStore())
            <a href="{{ route('business.products', $business) }}" @click="mobileOpen = false" style="padding: 14px 0; font-family: 'Hanken Grotesk', sans-serif; font-size: 0.6875rem; text-transform: uppercase; letter-spacing: 0.15em; font-weight: 500; color: var(--c-text); text-decoration: none; border-bottom: 0.5px solid #d4d0c8; transition: color 0.2s;" onmouseover="this.style.color='#735c00'" onmouseout="this.style.color='var(--c-text)'">Products</a>
            @else
            <a href="{{ route('business.services', $business) }}" @click="mobileOpen = false" style="padding: 14px 0; font-family: 'Hanken Grotesk', sans-serif; font-size: 0.6875rem; text-transform: uppercase; letter-spacing: 0.15em; font-weight: 500; color: var(--c-text); text-decoration: none; border-bottom: 0.5px solid #d4d0c8; transition: color 0.2s;" onmouseover="this.style.color='#735c00'" onmouseout="this.style.color='var(--c-text)'">Services</a>
            @endif
            <a href="{{ route('business.contact', $business) }}" @click="mobileOpen = false" style="padding: 14px 0; font-family: 'Hanken Grotesk', sans-serif; font-size: 0.6875rem; text-transform: uppercase; letter-spacing: 0.15em; font-weight: 500; color: var(--c-text); text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#735c00'" onmouseout="this.style.color='var(--c-text)'">Contact</a>
        </nav>
    </div>
</header>

<style>
    [x-cloak] { display: none !important; }
    @media (min-width: 768px) {
        .heritage-desktop-cta { display: inline-flex !important; }
    }
    @media (max-width: 767px) {
        #site-header nav { padding: 0 24px; }
        #site-header > nav > ul { display: none !important; }
    }
</style>

<script>
(function() {
    const header = document.getElementById('site-header');
    function updateHeader() {
        if (window.scrollY > 20) {
            header.style.background = 'rgba(251,249,249,0.95)';
            header.style.backdropFilter = 'blur(12px)';
            header.style.webkitBackdropFilter = 'blur(12px)';
        } else {
            header.style.background = 'var(--c-bg)';
            header.style.backdropFilter = 'none';
            header.style.webkitBackdropFilter = 'none';
        }
    }
    updateHeader();
    window.addEventListener('scroll', updateHeader, { passive: true });
    document.querySelectorAll('.heritage-nav-link').forEach(link => {
        link.addEventListener('mouseenter', function() { this.style.color = '#735c00'; });
        link.addEventListener('mouseleave', function() { this.style.color = 'var(--c-text)'; });
    });
})();
</script>
