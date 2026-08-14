{{-- Heritage theme · Service footer --}}
<footer style="border-top: 0.5px solid #d4d0c8; padding: 4rem 0 3rem; background: var(--c-bg);">
    <div style="max-width: 1280px; margin: 0 auto; padding: 0 64px;">
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 3rem;">
            <div>
                <div style="margin-bottom: 1.5rem;">
                    @if($business->logo)
                        <img src="{{ Storage::url($business->logo) }}" alt="{{ $business->name }}" style="height: 32px; margin-bottom: 0.75rem;">
                    @endif
                    <span style="font-family: 'Playfair Display', serif; font-weight: 600; font-size: 1.0625rem; display: block;">{{ $business->name }}</span>
                </div>
                @if($business->description)
                    <p style="font-family: 'Hanken Grotesk', sans-serif; font-size: 0.8125rem; color: #888; line-height: 1.6;">{{ Str::limit($business->description, 120) }}</p>
                @endif
            </div>
            <div>
                <h4 class="heritage-label" style="font-size: 0.625rem; margin-bottom: 1.5rem; color: #000;">Navigation</h4>
                <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0.75rem;">
                    <li><a href="{{ route('business.about', $business) }}" style="font-family: 'Hanken Grotesk', sans-serif; font-size: 0.8125rem; color: #888; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#735c00'" onmouseout="this.style.color='#888'">About</a></li>
                    <li><a href="{{ route('business.site', $business) }}#services" style="font-family: 'Hanken Grotesk', sans-serif; font-size: 0.8125rem; color: #888; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#735c00'" onmouseout="this.style.color='#888'">Services</a></li>
                    @if($business->portfolio && count($business->portfolio) > 0)
                    <li><a href="{{ route('business.portfolio', $business) }}" style="font-family: 'Hanken Grotesk', sans-serif; font-size: 0.8125rem; color: #888; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#735c00'" onmouseout="this.style.color='#888'">Portfolio</a></li>
                    @endif
                    <li><a href="{{ route('business.contact', $business) }}" style="font-family: 'Hanken Grotesk', sans-serif; font-size: 0.8125rem; color: #888; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#735c00'" onmouseout="this.style.color='#888'">Contact</a></li>
                </ul>
            </div>
            <div>
                <h4 class="heritage-label" style="font-size: 0.625rem; margin-bottom: 1.5rem; color: #000;">Contact</h4>
                <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0.75rem;">
                    @if($business->email)
                    <li>
                        <a href="mailto:{{ $business->email }}" style="font-family: 'Hanken Grotesk', sans-serif; font-size: 0.8125rem; color: #888; text-decoration: none; display: flex; align-items: center; gap: 8px; transition: color 0.2s;" onmouseover="this.style.color='#735c00'" onmouseout="this.style.color='#888'">
                            <span class="material-symbols-outlined" style="font-size: 14px;">mail</span> {{ $business->email }}
                        </a>
                    </li>
                    @endif
                    @if($business->phone)
                    <li>
                        <a href="tel:{{ $business->phone }}" style="font-family: 'Hanken Grotesk', sans-serif; font-size: 0.8125rem; color: #888; text-decoration: none; display: flex; align-items: center; gap: 8px; transition: color 0.2s;" onmouseover="this.style.color='#735c00'" onmouseout="this.style.color='#888'">
                            <span class="material-symbols-outlined" style="font-size: 14px;">call</span> {{ $business->phone }}
                        </a>
                    </li>
                    @endif
                    @if($business->address)
                    <li style="display: flex; align-items: flex-start; gap: 8px;">
                        <span class="material-symbols-outlined" style="font-size: 14px; margin-top: 3px;">location_on</span>
                        <span style="font-family: 'Hanken Grotesk', sans-serif; font-size: 0.8125rem; color: #888;">{{ $business->address }}</span>
                    </li>
                    @endif
                </ul>
            </div>
            <div>
                <h4 class="heritage-label" style="font-size: 0.625rem; margin-bottom: 1.5rem; color: #000;">Connect</h4>
                @php $socialLinks = $business->social_links ?? []; @endphp
                @if(count($socialLinks) > 0)
                <div style="display: flex; gap: 1rem;">
                    @foreach($socialLinks as $platform => $url)
                        @if($url)
                        <a href="{{ $url }}" target="_blank" rel="noopener" style="width: 36px; height: 36px; border: 0.5px solid #d4d0c8; display: flex; align-items: center; justify-content: center; transition: all 0.3s; text-decoration: none;" onmouseover="this.style.borderColor='#735c00'; this.style.background='#735c00'; this.style.color='#fbf9f9';" onmouseout="this.style.borderColor='#d4d0c8'; this.style.background='transparent'; this.style.color='inherit';">
                            <span class="material-symbols-outlined" style="font-size: 16px;">link</span>
                        </a>
                        @endif
                    @endforeach
                </div>
                @endif
            </div>
        </div>
        <div style="margin-top: 3rem; padding-top: 1.5rem; border-top: 0.5px solid #d4d0c8; display: flex; justify-content: space-between; align-items: center;">
            <p style="font-family: 'Hanken Grotesk', sans-serif; font-size: 0.75rem; color: #bbb;">&copy; {{ date('Y') }} {{ $business->name }}. All rights reserved.</p>
            <p style="font-family: 'Hanken Grotesk', sans-serif; font-size: 0.75rem; color: #bbb;">Powered by Ilthres</p>
        </div>
    </div>
</footer>
