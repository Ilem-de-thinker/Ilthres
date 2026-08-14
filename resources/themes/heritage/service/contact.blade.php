<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle ?? ($business->name . ' — Contact') }}</title>
    @if (!empty($business))
        <meta name="description" content="{{ Str::limit(strip_tags($business->description ?? $business->about ?? $business->name), 160) }}">
    @endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&family=Hanken+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Hanken Grotesk', sans-serif; background: {{ $colors['background'] ?? '#fbf9f9' }}; color: {{ $colors['text'] ?? '#000000' }}; line-height: 1.6; -webkit-font-smoothing: antialiased; min-height: 100vh; display: flex; flex-direction: column; }
        main { flex: 1; }
        h1, h2, h3, h4 { font-family: 'Playfair Display', serif; font-weight: 600; }
        a { text-decoration: none; transition: all 0.2s ease; }
        input, textarea { font-family: 'Hanken Grotesk', sans-serif; }
        ::selection { background: {{ $colors['secondary'] ?? '#735c00' }}; color: {{ $colors['background'] ?? '#fbf9f9' }}; }
        :root { --c-primary: {{ $colors['primary'] ?? '#000000' }}; --c-secondary: {{ $colors['secondary'] ?? '#735c00' }}; --c-bg: {{ $colors['background'] ?? '#fbf9f9' }}; --c-text: {{ $colors['text'] ?? '#000000' }}; --c-accent: {{ $colors['accent'] ?? '#fed65b' }}; --c-radius: {{ $radius ?? '0.125rem' }}; }
        .heritage-input {
            width: 100%; padding: 0.75rem 1rem; border: 0.5px solid #d4d0c8; background: transparent;
            font-size: 0.875rem; color: var(--c-text); outline: none; transition: border-color 0.3s;
        }
        .heritage-input:focus { border-color: #735c00; }
        .heritage-input::placeholder { color: #bbb; }
    </style>
</head>
<body>
<div class="min-h-screen flex flex-col">
    @include('themes::heritage.service.header')

    <main class="flex-1" style="padding-top: 8rem; padding-bottom: 6rem;">
        <div style="max-width: 1280px; margin: 0 auto; padding: 0 64px;">
            <div style="text-align: center; margin-bottom: 4rem;">
                <a href="{{ route('business.site', $business) }}" style="display: inline-flex; align-items: center; gap: 8px; font-family: 'Hanken Grotesk', sans-serif; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; color: #735c00; text-decoration: none; margin-bottom: 2rem; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.6'" onmouseout="this.style.opacity='1'">
                    <span class="material-symbols-outlined" style="font-size: 16px;">arrow_back</span> Back to Profile
                </a>
                <div style="width: 32px; height: 0.5px; background: #735c00; margin: 0 auto 1.5rem;"></div>
                <h1 style="font-family: 'Playfair Display', serif; font-style: italic; font-weight: 500; font-size: 2.5rem; margin-bottom: 0.75rem;">Get in Touch</h1>
                <span class="heritage-label" style="font-size: 0.6875rem; color: #735c00;">We welcome your inquiry</span>
            </div>

            <div style="display: grid; grid-template-columns: 5fr 7fr; gap: 4rem; margin-bottom: 4rem;">
                <div>
                    <h3 style="font-family: 'Playfair Display', serif; font-size: 1.125rem; font-weight: 600; margin-bottom: 2rem;">Contact Information</h3>

                    @if($business->email)
                    <div style="margin-bottom: 1.5rem;">
                        <div class="heritage-label" style="font-size: 0.5625rem; color: #735c00; margin-bottom: 0.375rem;">Email</div>
                        <a href="mailto:{{ $business->email }}" style="font-family: 'Hanken Grotesk', sans-serif; font-size: 0.9375rem; color: #000; transition: color 0.2s;" onmouseover="this.style.color='#735c00'" onmouseout="this.style.color='#000'">{{ $business->email }}</a>
                    </div>
                    @endif

                    @if($business->phone)
                    <div style="margin-bottom: 1.5rem;">
                        <div class="heritage-label" style="font-size: 0.5625rem; color: #735c00; margin-bottom: 0.375rem;">Phone</div>
                        <a href="tel:{{ $business->phone }}" style="font-family: 'Hanken Grotesk', sans-serif; font-size: 0.9375rem; color: #000; transition: color 0.2s;" onmouseover="this.style.color='#735c00'" onmouseout="this.style.color='#000'">{{ $business->phone }}</a>
                    </div>
                    @endif

                    @if($business->whatsapp)
                    <div style="margin-bottom: 1.5rem;">
                        <div class="heritage-label" style="font-size: 0.5625rem; color: #735c00; margin-bottom: 0.375rem;">WhatsApp</div>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $business->whatsapp) }}" target="_blank" style="font-family: 'Hanken Grotesk', sans-serif; font-size: 0.9375rem; color: #000; transition: color 0.2s;" onmouseover="this.style.color='#735c00'" onmouseout="this.style.color='#000'">{{ $business->whatsapp }}</a>
                    </div>
                    @endif

                    @if($business->address)
                    <div style="margin-bottom: 1.5rem;">
                        <div class="heritage-label" style="font-size: 0.5625rem; color: #735c00; margin-bottom: 0.375rem;">Address</div>
                        <span style="font-family: 'Hanken Grotesk', sans-serif; font-size: 0.9375rem; color: #000;">{{ $business->address }}{{ $business->city ? ', ' . $business->city : '' }}{{ $business->state ? ', ' . $business->state : '' }}</span>
                    </div>
                    @endif

                    <div style="width: 100%; height: 0.5px; background: #d4d0c8; margin: 2rem 0;"></div>

                    <div style="display: flex; flex-wrap: wrap; gap: 1rem;">
                        @if($business->whatsapp)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $business->whatsapp) }}" target="_blank" class="heritage-btn-primary" style="display: inline-flex; align-items: center; gap: 8px; text-decoration: none;">
                            <span class="material-symbols-outlined" style="font-size: 18px;">chat</span> Chat on WhatsApp
                        </a>
                        @endif
                        @if($business->phone)
                        <a href="tel:{{ $business->phone }}" class="heritage-btn-secondary" style="display: inline-flex; align-items: center; gap: 8px; text-decoration: none;">
                            <span class="material-symbols-outlined" style="font-size: 18px;">call</span> Call Now
                        </a>
                        @endif
                    </div>
                </div>

                <div style="border: 0.5px solid #d4d0c8; padding: 2.5rem;">
                    <h3 style="font-family: 'Playfair Display', serif; font-size: 1.125rem; font-weight: 600; margin-bottom: 2rem;">Send a Message</h3>
                    <form style="display: flex; flex-direction: column; gap: 1.25rem;">
                        @csrf
                        <div>
                            <label class="heritage-label" style="font-size: 0.5625rem; display: block; margin-bottom: 0.5rem; color: #000;">Name</label>
                            <input type="text" name="name" required class="heritage-input" placeholder="Your name">
                        </div>
                        <div>
                            <label class="heritage-label" style="font-size: 0.5625rem; display: block; margin-bottom: 0.5rem; color: #000;">Email</label>
                            <input type="email" name="email" required class="heritage-input" placeholder="your@email.com">
                        </div>
                        <div>
                            <label class="heritage-label" style="font-size: 0.5625rem; display: block; margin-bottom: 0.5rem; color: #000;">Message</label>
                            <textarea name="message" rows="4" required class="heritage-input" style="resize: none;" placeholder="How can we assist you?"></textarea>
                        </div>
                        <button type="submit" class="heritage-btn-primary" style="width: 100%; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;">
                            Send Message <span class="material-symbols-outlined" style="font-size: 18px;">send</span>
                        </button>
                    </form>
                </div>
            </div>

            @if($business->latitude && $business->longitude)
            <div style="border: 0.5px solid #d4d0c8; overflow: hidden;">
                <div id="contact-map" style="width: 100%; height: 400px;"></div>
                <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
                <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        var map = L.map('contact-map').setView([{{ $business->latitude }}, {{ $business->longitude }}], 15);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap contributors' }).addTo(map);
                        L.marker([{{ $business->latitude }}, {{ $business->longitude }}]).addTo(map).bindPopup('{{ addslashes($business->name) }}').openPopup();
                    });
                </script>
            </div>
            @endif
        </div>
    </main>

    @include('themes::heritage.service.footer')
</div>
</body>
</html>
