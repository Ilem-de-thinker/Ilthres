{{--
  Bold theme · Service header — high-contrast dark menu bar with solid outlines.
--}}
<header style="position: sticky; top: 0; z-index: 100; background: var(--color-primary); color: #ffffff; border-bottom: 4px solid var(--color-text);">
    <div class="container" style="display: flex; align-items: center; justify-content: space-between; padding-top: 1rem; padding-bottom: 1rem;">
        <div style="display: flex; align-items: center; gap: 0.75rem;">
            @if ($business->logo)
                <img src="{{ Storage::url($business->logo) }}" alt="{{ $business->name }}"
                     style="width: 2.25rem; height: 2.25rem; border-radius: var(--radius); object-fit: cover; border: 2px solid #ffffff; box-shadow: none;">
            @else
                <div style="width: 2.25rem; height: 2.25rem; border-radius: var(--radius); background: var(--color-secondary); color: var(--color-text); display: flex; align-items: center; justify-content: center; font-family: var(--font-heading); font-weight: 900; font-size: 1rem; border: 2px solid #ffffff;">
                    {{ substr($business->name, 0, 1) }}
                </div>
            @endif
            <a href="{{ route('business.profile', $business) }}" style="font-family: var(--font-heading); font-weight: 900; font-size: 1.25rem; color: #ffffff; text-decoration: none; letter-spacing: -0.01em;">
                {{ $business->name }}
            </a>
        </div>
        <nav style="display: flex; gap: 1.5rem; font-family: var(--font-heading); font-size: 0.8125rem; font-weight: 900; text-transform: uppercase; letter-spacing: 0.05em;">
            <a href="#about" style="color: #ffffff; text-decoration: none;" onmouseover="this.style.color='var(--color-secondary)'" onmouseout="this.style.color='#ffffff'">About</a>
            <a href="#portfolio" style="color: #ffffff; text-decoration: none;" onmouseover="this.style.color='var(--color-secondary)'" onmouseout="this.style.color='#ffffff'">Portfolio</a>
            <a href="#services" style="color: #ffffff; text-decoration: none;" onmouseover="this.style.color='var(--color-secondary)'" onmouseout="this.style.color='#ffffff'">Services</a>
            <a href="#contact" style="color: #ffffff; text-decoration: none;" onmouseover="this.style.color='var(--color-secondary)'" onmouseout="this.style.color='#ffffff'">Contact</a>
        </nav>
    </div>
</header>
