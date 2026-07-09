{{--
  Modern theme · Store header — glass-effect nav bar with logo and links.
--}}
<header style="position: sticky; top: 0; z-index: 100; backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); background: rgba(255, 255, 255, 0.75); border-bottom: 1px solid rgba(0, 0, 0, 0.04); box-shadow: 0 4px 30px rgba(0, 0, 0, 0.02);">
    <div class="container" style="display: flex; align-items: center; justify-content: space-between; padding-top: 0.875rem; padding-bottom: 0.875rem;">
        <div style="display: flex; align-items: center; gap: 0.75rem;">
            @if ($business->logo)
                <img src="{{ Storage::url($business->logo) }}" alt="{{ $business->name }}"
                     style="width: 2.5rem; height: 2.5rem; border-radius: 9999px; object-fit: cover; border: 2px solid #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
            @else
                <div style="width: 2.5rem; height: 2.5rem; border-radius: 9999px; background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.875rem; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
                    {{ substr($business->name, 0, 1) }}
                </div>
            @endif
            <a href="{{ route('business.profile', $business) }}" style="font-family: var(--font-heading); font-weight: 700; font-size: 1.25rem; color: var(--color-text); text-decoration: none; letter-spacing: -0.02em;">
                {{ $business->name }}
            </a>
        </div>
        <nav style="display: flex; gap: 2rem; font-size: 0.875rem; font-weight: 600;">
            <a href="#about" style="color: rgba(0,0,0,0.6); text-decoration: none;" onmouseover="this.style.color='var(--color-primary)'" onmouseout="this.style.color='rgba(0,0,0,0.6)'">About</a>
            <a href="#portfolio" style="color: rgba(0,0,0,0.6); text-decoration: none;" onmouseover="this.style.color='var(--color-primary)'" onmouseout="this.style.color='rgba(0,0,0,0.6)'">Portfolio</a>
            <a href="#products" style="color: rgba(0,0,0,0.6); text-decoration: none;" onmouseover="this.style.color='var(--color-primary)'" onmouseout="this.style.color='rgba(0,0,0,0.6)'">Products</a>
            <a href="#contact" style="color: rgba(0,0,0,0.6); text-decoration: none;" onmouseover="this.style.color='var(--color-primary)'" onmouseout="this.style.color='rgba(0,0,0,0.6)'">Contact</a>
        </nav>
    </div>
</header>
