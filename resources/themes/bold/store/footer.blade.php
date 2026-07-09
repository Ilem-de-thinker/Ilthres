<footer style="background: var(--color-primary); color: color-mix(in srgb, var(--color-background) 60%, transparent); padding: 2.5rem 0; text-align: center; border-top: 1px solid color-mix(in srgb, var(--color-background) 10%, transparent);">
    <div class="container">
        @if ($business->social_links)
            <div style="display: flex; justify-content: center; gap: 1.5rem; margin-bottom: 1.25rem;">
                @foreach ($business->social_links as $link)
                    <a href="{{ $link['url'] }}" target="_blank" rel="noopener noreferrer" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; font-family: var(--font-heading); font-weight: 800; color: var(--color-background); text-decoration: none;">
                        {{ $link['platform'] }}
                    </a>
                @endforeach
            </div>
        @endif
        <p style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; font-family: var(--font-heading); font-weight: 800;">
            &copy; {{ date('Y') }} {{ $business->name }}. All rights reserved.
        </p>
    </div>
</footer>
