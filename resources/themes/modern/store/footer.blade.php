<footer style="border-top: 1px solid {{ $colors['text'] }}10; padding: 2.5rem 0; text-align: center;">
    <div class="container">
        @if ($business->social_links)
            <div style="display: flex; justify-content: center; gap: 1.25rem; margin-bottom: 1.25rem;">
                @foreach ($business->social_links as $link)
                    <a href="{{ $link['url'] }}" target="_blank" rel="noopener noreferrer" style="font-size: 0.8125rem; color: {{ $colors['text'] }}60; text-decoration: none; transition: color 0.2s;">
                        {{ $link['platform'] }}
                    </a>
                @endforeach
            </div>
        @endif
        <p style="font-size: 0.8125rem; color: {{ $colors['text'] }}50;">
            &copy; {{ date('Y') }} {{ $business->name }}. All rights reserved.
        </p>
    </div>
</footer>
