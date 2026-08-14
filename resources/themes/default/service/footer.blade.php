{{-- Default theme · Service footer — Serene Ivory --}}
<footer style="background: #f1eee7; border-top: 0.5px solid #E5E0D5; margin-top: auto;">
    <div style="max-width: 1280px; margin: 0 auto; padding: 0 64px;">
        <div style="display: grid; grid-template-columns: 1.5fr 1fr 1fr 1fr; gap: 48px; padding: 64px 0;">
            {{-- Brand --}}
            <div>
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                    @if($business->logo)
                        <img src="{{ Storage::url($business->logo) }}" alt="{{ $business->name }}" style="height: 2rem; width: 2rem; object-fit: cover; border-radius: 0.25rem;">
                    @endif
                    <span style="font-family: 'Playfair Display', serif; font-size: 1.125rem; color: #1c1c18;">{{ $business->name }}</span>
                </div>
                @if($business->description)
                    <p style="font-size: 0.8125rem; color: #757873; max-width: 280px; line-height: 1.7;">{{ Str::limit($business->description, 120) }}</p>
                @endif
            </div>

            {{-- Services --}}
            <div>
                <h4 class="section-label" style="color: #1c1c18; margin-bottom: 16px;">Services</h4>
                <ul style="list-style: none; padding: 0; display: flex; flex-direction: column; gap: 10px;">
                    <li><a href="{{ route('business.site', $business) }}#services" style="font-size: 0.8125rem; color: #757873; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#1c1c18'" onmouseout="this.style.color='#757873'">All Services</a></li>
                    @if($business->portfolio && count($business->portfolio) > 0)
                    <li><a href="{{ route('business.portfolio', $business) }}" style="font-size: 0.8125rem; color: #757873; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#1c1c18'" onmouseout="this.style.color='#757873'">Portfolio</a></li>
                    @endif
                </ul>
            </div>

            {{-- Customer Care --}}
            <div>
                <h4 class="section-label" style="color: #1c1c18; margin-bottom: 16px;">Customer Care</h4>
                <ul style="list-style: none; padding: 0; display: flex; flex-direction: column; gap: 10px;">
                    <li><a href="{{ route('business.about', $business) }}" style="font-size: 0.8125rem; color: #757873; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#1c1c18'" onmouseout="this.style.color='#757873'">About Us</a></li>
                    <li><a href="{{ route('business.contact', $business) }}" style="font-size: 0.8125rem; color: #757873; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#1c1c18'" onmouseout="this.style.color='#757873'">Contact</a></li>
                </ul>
            </div>

            {{-- Social --}}
            <div>
                <h4 class="section-label" style="color: #1c1c18; margin-bottom: 16px;">Follow Us</h4>
                <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                    @if($business->instagram)<a href="{{ $business->instagram }}" target="_blank" rel="noopener" style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; border: 0.5px solid #E5E0D5; color: #757873; border-radius: 0.25rem; transition: all 0.2s;" onmouseover="this.style.background='#1c1c18'; this.style.color='#fcf9f2'; this.style.borderColor='#1c1c18';" onmouseout="this.style.background='transparent'; this.style.color='#757873'; this.style.borderColor='#E5E0D5';"><span class="material-symbols-outlined" style="font-size: 18px;">photo_camera</span></a>@endif
                    @if($business->facebook)<a href="{{ $business->facebook }}" target="_blank" rel="noopener" style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; border: 0.5px solid #E5E0D5; color: #757873; border-radius: 0.25rem; transition: all 0.2s;" onmouseover="this.style.background='#1c1c18'; this.style.color='#fcf9f2'; this.style.borderColor='#1c1c18';" onmouseout="this.style.background='transparent'; this.style.color='#757873'; this.style.borderColor='#E5E0D5';"><span class="material-symbols-outlined" style="font-size: 18px;">thumb_up</span></a>@endif
                    @if($business->tiktok)<a href="{{ $business->tiktok }}" target="_blank" rel="noopener" style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; border: 0.5px solid #E5E0D5; color: #757873; border-radius: 0.25rem; transition: all 0.2s;" onmouseover="this.style.background='#1c1c18'; this.style.color='#fcf9f2'; this.style.borderColor='#1c1c18';" onmouseout="this.style.background='transparent'; this.style.color='#757873'; this.style.borderColor='#E5E0D5';"><span class="material-symbols-outlined" style="font-size: 18px;">videocam</span></a>@endif
                    @if($business->youtube)<a href="{{ $business->youtube }}" target="_blank" rel="noopener" style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; border: 0.5px solid #E5E0D5; color: #757873; border-radius: 0.25rem; transition: all 0.2s;" onmouseover="this.style.background='#1c1c18'; this.style.color='#fcf9f2'; this.style.borderColor='#1c1c18';" onmouseout="this.style.background='transparent'; this.style.color='#757873'; this.style.borderColor='#E5E0D5';"><span class="material-symbols-outlined" style="font-size: 18px;">play_circle</span></a>@endif
                    @if($business->whatsapp)<a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $business->whatsapp) }}" target="_blank" rel="noopener" style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; border: 0.5px solid #E5E0D5; color: #757873; border-radius: 0.25rem; transition: all 0.2s;" onmouseover="this.style.background='#1c1c18'; this.style.color='#fcf9f2'; this.style.borderColor='#1c1c18';" onmouseout="this.style.background='transparent'; this.style.color='#757873'; this.style.borderColor='#E5E0D5';"><span class="material-symbols-outlined" style="font-size: 18px;">chat</span></a>@endif
                </div>
            </div>
        </div>

        <div style="border-top: 0.5px solid #E5E0D5; padding: 24px 0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px;">
            <p style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.6875rem; color: #757873;">
                &copy; {{ date('Y') }} {{ $business->name }}. All rights reserved.
            </p>
            <p style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.6875rem; color: #757873;">
                Powered by Ilthres
            </p>
        </div>
    </div>
</footer>
