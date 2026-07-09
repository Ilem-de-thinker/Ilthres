{{--
  Bold theme · Store contact — high contrast editorial contact section.
--}}
<section id="contact" style="background: var(--color-primary); color: var(--color-background); border-bottom: none;">
    <div class="container">
        <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 3rem;">
            <div>
                <h2 style="font-size: 2.25rem; font-weight: 900; margin-bottom: 1.5rem; text-transform: uppercase; color: var(--color-background);">
                    Get In Touch
                </h2>
                <div style="font-size: 1rem; line-height: 1.8; color: var(--color-background); opacity: 0.85;">
                    @if ($business->email)
                        <p style="margin-bottom: 0.5rem;"><strong>Email:</strong> {{ $business->email }}</p>
                    @endif
                    @if ($business->phone)
                        <p style="margin-bottom: 0.5rem;"><strong>Phone:</strong> {{ $business->phone }}</p>
                    @endif
                    @if ($business->whatsapp)
                        <p style="margin-bottom: 0.5rem;"><strong>WhatsApp:</strong> {{ $business->whatsapp }}</p>
                    @endif
                    @if ($business->address)
                        <p style="margin-bottom: 0.5rem;"><strong>Address:</strong> {{ $business->address }}</p>
                    @endif
                </div>
            </div>
            
            <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                @if ($business->whatsapp)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $business->whatsapp) }}" 
                       class="btn btn-primary" 
                       style="background: var(--color-background); color: var(--color-primary); border-color: var(--color-background);" 
                       target="_blank" 
                       onclick="navigator.sendBeacon('{{ route('track.click', [$business->id, 'whatsapp_click']) }}?source=profile&url='+encodeURIComponent(location.href));">
                        WhatsApp Chat
                    </a>
                @endif
                @if ($business->phone)
                    <a href="tel:{{ $business->phone }}" 
                       class="btn btn-outline" 
                       style="border-color: var(--color-background); color: var(--color-background);" 
                       onclick="navigator.sendBeacon('{{ route('track.click', [$business->id, 'phone_click']) }}?source=profile&url='+encodeURIComponent(location.href));">
                        Call Now
                    </a>
                @endif
            </div>
        </div>
    </div>
</section>
