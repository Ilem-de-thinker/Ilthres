{{-- Bold theme · Store footer — High-Impact Editorial --}}
<footer style="background: #0e0e0e; color: #e2e2e2; border-top: 4px solid #ffffff; padding: 48px 0;">
    <div style="max-width: 1280px; margin: 0 auto; padding: 0 64px;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 32px;">
            {{-- Brand --}}
            <div>
                <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 12px;">
                    @if($business->logo)
                        <img src="{{ Storage::url($business->logo) }}" alt="{{ $business->name }}" style="height: 2.5rem; width: 2.5rem; object-fit: cover; border: 2px solid #ffffff;">
                    @endif
                    <span style="font-family: 'Anton', sans-serif; font-size: 1.25rem; text-transform: uppercase; color: #ffffff;">{{ $business->name }}</span>
                </div>
                @if($business->description)
                    <p style="font-size: 0.875rem; color: #e2e2e2; opacity: 0.5; max-width: 300px; font-family: 'Archivo Narrow', sans-serif;">{{ Str::limit($business->description, 120) }}</p>
                @endif
            </div>

            {{-- Social Links --}}
            @if ($business->social_links)
                <div style="display: flex; gap: 16px;">
                    @foreach ($business->social_links as $link)
                        <a href="{{ $link['url'] }}" target="_blank" rel="noopener noreferrer"
                           style="font-family: 'Space Mono', monospace; font-size: 10px; text-transform: uppercase; letter-spacing: 0.15em; font-weight: 700; color: #e2e2e2; transition: color 0.15s; text-decoration: none;"
                           onmouseover="this.style.color='#72ff70'" onmouseout="this.style.color='#e2e2e2'">
                            {{ $link['platform'] }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        <div style="margin-top: 32px; padding-top: 24px; border-top: 2px solid #444748; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px;">
            <p style="font-family: 'Space Mono', monospace; font-size: 10px; text-transform: uppercase; letter-spacing: 0.15em; color: #e2e2e2; opacity: 0.4;">
                &copy; {{ date('Y') }} {{ $business->name }}. ALL RIGHTS RESERVED.
            </p>
            <p style="font-family: 'Space Mono', monospace; font-size: 10px; text-transform: uppercase; letter-spacing: 0.15em; color: #e2e2e2; opacity: 0.4;">
                POWERED BY ILTHRES
            </p>
        </div>
    </div>
</footer>
