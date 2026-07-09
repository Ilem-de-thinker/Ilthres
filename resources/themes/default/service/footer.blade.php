<footer style="border-top: 1px solid {{ $colors['text'] }}15; padding: 2rem 0;">
    <div class="container" style="text-align: center;">
        @if ($business->social_links)
            <div style="display: flex; justify-content: center; gap: 1rem; margin-bottom: 1rem;">
                @foreach ($business->social_links as $link)
                    <a href="{{ $link['url'] }}" target="_blank" rel="noopener noreferrer" style="font-size: 0.8125rem; color: {{ $colors['text'] }}80; text-decoration: none;">
                        {{ $link['platform'] }}
                    </a>
                @endforeach
            </div>
        @endif
        <p style="font-size: 0.875rem; color: {{ $colors['text'] }}60;">
            &copy; {{ date('Y') }} {{ $business->name }}. All rights reserved.
        </p>
    </div>
</footer>
