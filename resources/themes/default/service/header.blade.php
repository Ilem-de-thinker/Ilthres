{{--
  Default theme · Service header — logo, business name, and anchored navigation.
--}}
<header style="border-bottom: 1px solid rgba(0,0,0,0.06); padding: 1rem 0; background: #ffffff;">
    <div class="container" style="display: flex; align-items: center; justify-content: space-between;">
        <div style="display: flex; align-items: center; gap: 0.75rem;">
            @if ($business->logo)
                <img src="{{ Storage::url($business->logo) }}" alt="{{ $business->name }}"
                     style="width: 2.25rem; height: 2.25rem; border-radius: var(--radius); object-fit: cover; border: 1px solid rgba(0,0,0,0.05);">
            @else
                <div style="width: 2.25rem; height: 2.25rem; border-radius: var(--radius); background: rgba(0,0,0,0.05); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.875rem;">
                    {{ substr($business->name, 0, 1) }}
                </div>
            @endif
            <a href="{{ route('business.profile', $business) }}" style="font-family: var(--font-heading); font-weight: 600; font-size: 1.125rem; color: var(--color-text); text-decoration: none;">
                {{ $business->name }}
            </a>
        </div>
        <nav style="display: flex; gap: 1.75rem; font-size: 0.875rem; font-weight: 500;">
            <a href="#about" style="color: rgba(0,0,0,0.6); text-decoration: none;">About</a>
            <a href="#portfolio" style="color: rgba(0,0,0,0.6); text-decoration: none;">Portfolio</a>
            <a href="#services" style="color: rgba(0,0,0,0.6); text-decoration: none;">Services</a>
            <a href="#contact" style="color: rgba(0,0,0,0.6); text-decoration: none;">Contact</a>
        </nav>
    </div>
</header>
