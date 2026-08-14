{{-- Bold theme · Footer — High-Impact Editorial --}}
<footer style="background: #131313; border-top: 4px solid #ffffff; padding: 48px 0; margin-top: auto;">
    <div style="max-width: 1280px; margin: 0 auto; padding: 0 64px; display: flex; justify-content: space-between; align-items: center;">
        <div style="display: flex; align-items: center; gap: 16px;">
            @if($business->logo)
                <img src="{{ Storage::url($business->logo) }}" alt="{{ $business->name }}" style="height: 32px; width: 32px; object-fit: cover; border: 2px solid #ffffff; filter: grayscale(1);">
            @endif
            <span style="font-family: 'Space Mono', monospace; font-size: 12px; color: #e2e2e2; opacity: 0.5; text-transform: uppercase; letter-spacing: 0.15em;">
                {{ $business->name }} // © {{ date('Y') }}
            </span>
        </div>
        <div style="display: flex; gap: 24px;">
            @if($business->instagram)<a href="{{ $business->instagram }}" target="_blank" rel="noopener" style="color: #e2e2e2; opacity: 0.5; transition: opacity 0.15s;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.5'"><span class="material-symbols-outlined" style="font-size: 20px;">photo_camera</span></a>@endif
            @if($business->facebook)<a href="{{ $business->facebook }}" target="_blank" rel="noopener" style="color: #e2e2e2; opacity: 0.5; transition: opacity 0.15s;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.5'"><span class="material-symbols-outlined" style="font-size: 20px;">thumb_up</span></a>@endif
            @if($business->tiktok)<a href="{{ $business->tiktok }}" target="_blank" rel="noopener" style="color: #e2e2e2; opacity: 0.5; transition: opacity 0.15s;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.5'"><span class="material-symbols-outlined" style="font-size: 20px;">videocam</span></a>@endif
            @if($business->youtube)<a href="{{ $business->youtube }}" target="_blank" rel="noopener" style="color: #e2e2e2; opacity: 0.5; transition: opacity 0.15s;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.5'"><span class="material-symbols-outlined" style="font-size: 20px;">play_circle</span></a>@endif
            @if($business->whatsapp)<a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $business->whatsapp) }}" target="_blank" rel="noopener" style="color: #e2e2e2; opacity: 0.5; transition: opacity 0.15s;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.5'"><span class="material-symbols-outlined" style="font-size: 20px;">chat</span></a>@endif
        </div>
    </div>
</footer>
