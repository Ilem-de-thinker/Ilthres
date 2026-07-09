{{--
  Default theme · Service contact — contact info, social links, booking CTA.
--}}
<section id="contact" style="background: {{ $colors['text'] }}08;">
    <div class="container">
        <h2 class="section-title">Contact</h2>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
            <div>
                @if ($business->email)
                    <p style="margin-bottom: 0.75rem;"><strong>Email:</strong> {{ $business->email }}</p>
                @endif
                @if ($business->phone)
                    <p style="margin-bottom: 0.75rem;"><strong>Phone:</strong> {{ $business->phone }}</p>
                @endif
                @if ($business->whatsapp)
                    <p style="margin-bottom: 0.75rem;"><strong>WhatsApp:</strong> {{ $business->whatsapp }}</p>
                @endif
                @if ($business->address)
                    <p style="margin-bottom: 0.75rem;"><strong>Address:</strong> {{ $business->address }}</p>
                @endif
            </div>
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                @if ($business->whatsapp)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $business->whatsapp) }}" class="btn btn-primary" target="_blank" onclick="navigator.sendBeacon('{{ route('track.click', [$business->id, 'whatsapp_click']) }}?source=profile&url='+encodeURIComponent(location.href));">Book on WhatsApp</a>
                @endif
                @if ($business->phone)
                    <a href="tel:{{ $business->phone }}" class="btn btn-outline" onclick="navigator.sendBeacon('{{ route('track.click', [$business->id, 'phone_click']) }}?source=profile&url='+encodeURIComponent(location.href));">Call {{ $business->phone }}</a>
                @endif
            </div>
        </div>
    </div>
</section>
