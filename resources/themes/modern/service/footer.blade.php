{{-- Modern theme · Service footer — Lumina SaaS glass footer --}}
<footer style="background: rgba(255,255,255,0.5); backdrop-filter: blur(12px); border-top: 1px solid rgba(70,72,212,0.15); padding: 48px 0 32px; margin-top: auto;">
    <div style="max-width: 1280px; margin: 0 auto; padding: 0 40px;">
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 32px; margin-bottom: 40px;">
            <div style="grid-column: 1 / 2;">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                    @if($business->logo)
                        <img src="{{ Storage::url($business->logo) }}" alt="{{ $business->name }}" style="height: 32px; width: 32px; object-fit: cover; border-radius: 8px;">
                    @endif
                    <span style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1rem; font-weight: 700; color: #1a1a2e;">{{ $business->name }}</span>
                </div>
                @if($business->description)
                    <p style="font-size: 0.8125rem; color: #6b7280; line-height: 1.6;">{{ Str::limit($business->description, 120) }}</p>
                @endif
            </div>

            <div>
                <span style="display: block; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: #4648d4; margin-bottom: 16px;">Navigate</span>
                <ul style="list-style: none; display: flex; flex-direction: column; gap: 8px;">
                    <li><a href="{{ route('business.about', $business) }}" style="font-size: 0.875rem; color: #6b7280; text-decoration: none;" onmouseover="this.style.color='#4648d4'" onmouseout="this.style.color='#6b7280'">About</a></li>
                    <li><a href="{{ route('business.site', $business) }}#services" style="font-size: 0.875rem; color: #6b7280; text-decoration: none;" onmouseover="this.style.color='#4648d4'" onmouseout="this.style.color='#6b7280'">Services</a></li>
                    <li><a href="{{ route('business.portfolio', $business) }}" style="font-size: 0.875rem; color: #6b7280; text-decoration: none;" onmouseover="this.style.color='#4648d4'" onmouseout="this.style.color='#6b7280'">Portfolio</a></li>
                    <li><a href="{{ route('business.contact', $business) }}" style="font-size: 0.875rem; color: #6b7280; text-decoration: none;" onmouseover="this.style.color='#4648d4'" onmouseout="this.style.color='#6b7280'">Contact</a></li>
                </ul>
            </div>

            <div>
                <span style="display: block; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: #4648d4; margin-bottom: 16px;">Contact</span>
                @if($business->email)<p style="font-size: 0.875rem; color: #6b7280; margin-bottom: 8px;">{{ $business->email }}</p>@endif
                @if($business->phone)<p style="font-size: 0.875rem; color: #6b7280; margin-bottom: 8px;">{{ $business->phone }}</p>@endif
                @if($business->address)<p style="font-size: 0.875rem; color: #6b7280; line-height: 1.5;">{{ $business->address }}</p>@endif
            </div>

            <div>
                <span style="display: block; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: #4648d4; margin-bottom: 16px;">Follow Us</span>
                <div style="display: flex; gap: 12px;">
                    @if($business->social_links)
                        @foreach ($business->social_links as $link)
                            <a href="{{ $link['url'] }}" target="_blank" rel="noopener noreferrer"
                               style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background: rgba(70,72,212,0.08); color: #4648d4; border-radius: 10px; text-decoration: none; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; transition: all 0.2s;"
                               onmouseover="this.style.background='#4648d4'; this.style.color='#ffffff'" onmouseout="this.style.background='rgba(70,72,212,0.08)'; this.style.color='#4648d4'">
                                {{ substr($link['platform'], 0, 2) }}
                            </a>
                        @endforeach
                    @endif
                    @if($business->instagram)
                        <a href="{{ $business->instagram }}" target="_blank" rel="noopener" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background: rgba(70,72,212,0.08); color: #4648d4; border-radius: 10px; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background='#4648d4'; this.style.color='#ffffff'" onmouseout="this.style.background='rgba(70,72,212,0.08)'; this.style.color='#4648d4'"><span class="material-symbols-outlined" style="font-size: 18px;">photo_camera</span></a>
                    @endif
                    @if($business->facebook)
                        <a href="{{ $business->facebook }}" target="_blank" rel="noopener" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background: rgba(70,72,212,0.08); color: #4648d4; border-radius: 10px; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background='#4648d4'; this.style.color='#ffffff'" onmouseout="this.style.background='rgba(70,72,212,0.08)'; this.style.color='#4648d4'"><span class="material-symbols-outlined" style="font-size: 18px;">thumb_up</span></a>
                    @endif
                    @if($business->whatsapp)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $business->whatsapp) }}" target="_blank" rel="noopener" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background: rgba(70,72,212,0.08); color: #4648d4; border-radius: 10px; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background='#4648d4'; this.style.color='#ffffff'" onmouseout="this.style.background='rgba(70,72,212,0.08)'; this.style.color='#4648d4'"><span class="material-symbols-outlined" style="font-size: 18px;">chat</span></a>
                    @endif
                </div>
            </div>
        </div>

        <div style="padding-top: 24px; border-top: 1px solid rgba(0,0,0,0.06); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px;">
            <p style="font-size: 0.8125rem; color: #9ca3af;">
                &copy; {{ date('Y') }} {{ $business->name }}. All rights reserved.
            </p>
            <p style="font-size: 0.75rem; font-weight: 600; color: #4648d4;">
                Powered by Ilthres
            </p>
        </div>
    </div>
</footer>
