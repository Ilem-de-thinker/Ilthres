<!DOCTYPE html>
<html lang="en" class="dark">
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
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Archivo+Narrow:wght@400;500;600;700&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Archivo Narrow', sans-serif; background: #131313; color: #e2e2e2; line-height: 1.6; -webkit-font-smoothing: antialiased; min-height: 100vh; display: flex; flex-direction: column; overflow-x: hidden; }
        main { flex: 1; }
        h1, h2, h3, h4 { font-family: 'Anton', sans-serif; text-transform: uppercase; letter-spacing: 0.01em; line-height: 1.05; }
        a { text-decoration: none; transition: all 0.15s ease; }
        input, textarea { font-family: 'Archivo Narrow', sans-serif; }
        ::selection { background: #72ff70; color: #002203; }
        .dot-pattern { background-image: radial-gradient(circle, #444748 1px, transparent 1px); background-size: 24px 24px; }
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #131313; }
        ::-webkit-scrollbar-thumb { background: #ffffff; border: 2px solid #131313; }
    </style>
</head>
<body>
<div class="min-h-screen flex flex-col">
    @include('themes::bold.store.header')

    <main class="flex-1" style="padding-top: 96px; padding-bottom: 64px;">
        <div style="max-width: 1280px; margin: 0 auto; padding: 0 64px;">
            {{-- Page Header --}}
            <div style="margin-bottom: 64px;">
                <a href="{{ route('business.site', $business) }}" style="display: inline-flex; align-items: center; gap: 6px; margin-bottom: 24px; font-family: 'Space Mono', monospace; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.15em; color: #e2e2e2; text-decoration: none;" onmouseover="this.style.color='#72ff70'" onmouseout="this.style.color='#e2e2e2'">
                    <span class="material-symbols-outlined" style="font-size: 16px;">arrow_back</span> BACK_TO_PROFILE
                </a>
                <div style="display: flex; justify-content: space-between; align-items: flex-end; padding-bottom: 16px; border-bottom: 4px solid #ffffff;">
                    <h1 style="font-family: 'Anton', sans-serif; font-size: 3rem; color: #ffffff;">CONTACT_US</h1>
                    <span style="font-family: 'Space Mono', monospace; font-size: 10px; text-transform: uppercase; letter-spacing: 0.15em; color: #e2e2e2; opacity: 0.6;">SECTION_04</span>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(12, 1fr); gap: 24px; margin-bottom: 64px;">
                {{-- Contact Info (5 cols) --}}
                <div style="grid-column: 1 / 6;">
                    @if($business->email)
                    <div style="display: flex; align-items: flex-start; gap: 16px; margin-bottom: 32px;">
                        <div style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; border: 2px solid #ffffff;">
                            <span class="material-symbols-outlined" style="font-size: 20px; color: #ffffff;">mail</span>
                        </div>
                        <div>
                            <div style="font-family: 'Space Mono', monospace; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.15em; color: #e2e2e2; opacity: 0.5; margin-bottom: 4px;">Email</div>
                            <a href="mailto:{{ $business->email }}" style="font-size: 1rem; color: #e2e2e2;" onmouseover="this.style.color='#72ff70'" onmouseout="this.style.color='#e2e2e2'">{{ $business->email }}</a>
                        </div>
                    </div>
                    @endif
                    @if($business->phone)
                    <div style="display: flex; align-items: flex-start; gap: 16px; margin-bottom: 32px;">
                        <div style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; border: 2px solid #ffffff;">
                            <span class="material-symbols-outlined" style="font-size: 20px; color: #ffffff;">call</span>
                        </div>
                        <div>
                            <div style="font-family: 'Space Mono', monospace; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.15em; color: #e2e2e2; opacity: 0.5; margin-bottom: 4px;">Phone</div>
                            <a href="tel:{{ $business->phone }}" style="font-size: 1rem; color: #e2e2e2;" onmouseover="this.style.color='#72ff70'" onmouseout="this.style.color='#e2e2e2'">{{ $business->phone }}</a>
                        </div>
                    </div>
                    @endif
                    @if($business->whatsapp)
                    <div style="display: flex; align-items: flex-start; gap: 16px; margin-bottom: 32px;">
                        <div style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; border: 2px solid #ffffff;">
                            <span class="material-symbols-outlined" style="font-size: 20px; color: #ffffff;">chat</span>
                        </div>
                        <div>
                            <div style="font-family: 'Space Mono', monospace; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.15em; color: #e2e2e2; opacity: 0.5; margin-bottom: 4px;">WhatsApp</div>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $business->whatsapp) }}" target="_blank" style="font-size: 1rem; color: #e2e2e2;" onmouseover="this.style.color='#72ff70'" onmouseout="this.style.color='#e2e2e2'">{{ $business->whatsapp }}</a>
                        </div>
                    </div>
                    @endif
                    @if($business->address)
                    <div style="display: flex; align-items: flex-start; gap: 16px; margin-bottom: 32px;">
                        <div style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; border: 2px solid #ffffff;">
                            <span class="material-symbols-outlined" style="font-size: 20px; color: #ffffff;">location_on</span>
                        </div>
                        <div>
                            <div style="font-family: 'Space Mono', monospace; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.15em; color: #e2e2e2; opacity: 0.5; margin-bottom: 4px;">Address</div>
                            <span style="font-size: 1rem; color: #e2e2e2;">{{ $business->address }}{{ $business->city ? ', ' . $business->city : '' }}{{ $business->state ? ', ' . $business->state : '' }}</span>
                        </div>
                    </div>
                    @endif

                    {{-- Action Buttons --}}
                    <div style="display: flex; flex-wrap: wrap; gap: 16px; margin-top: 32px;">
                        @if($business->whatsapp)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $business->whatsapp) }}" target="_blank"
                           style="display: inline-flex; align-items: center; gap: 8px; padding: 16px 40px; background: #ffffff; color: #131313; font-family: 'Space Mono', monospace; font-size: 14px; font-weight: 700; letter-spacing: 0.2em; text-transform: uppercase; border: 4px solid #ffffff; transition: all 0.15s; text-decoration: none;"
                           onmouseover="this.style.background='#72ff70'; this.style.borderColor='#72ff70';"
                           onmouseout="this.style.background='#ffffff'; this.style.borderColor='#ffffff';">
                            <span class="material-symbols-outlined" style="font-size: 16px;">chat</span> CHAT_ON_WHATSAPP
                        </a>
                        @endif
                        @if($business->phone)
                        <a href="tel:{{ $business->phone }}"
                           style="display: inline-flex; align-items: center; gap: 8px; padding: 16px 40px; border: 4px solid #ffffff; color: #ffffff; font-family: 'Space Mono', monospace; font-size: 14px; font-weight: 700; letter-spacing: 0.2em; text-transform: uppercase; transition: all 0.15s; text-decoration: none;"
                           onmouseover="this.style.background='rgba(255,255,255,0.1)';"
                           onmouseout="this.style.background='transparent';">
                            <span class="material-symbols-outlined" style="font-size: 16px;">call</span> CALL_NOW
                        </a>
                        @endif
                    </div>
                </div>

                {{-- Contact Form (7 cols) --}}
                <div style="grid-column: 6 / 13; border: 4px solid #ffffff; padding: 32px;">
                    <h3 style="font-family: 'Anton', sans-serif; font-size: 1.25rem; color: #ffffff; margin-bottom: 24px; text-transform: uppercase;">SEND_A_MESSAGE</h3>
                    <form style="display: flex; flex-direction: column; gap: 20px;">
                        @csrf
                        <div>
                            <label style="display: block; font-family: 'Space Mono', monospace; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.15em; color: #e2e2e2; opacity: 0.5; margin-bottom: 8px;">Name</label>
                            <input type="text" name="name" required
                                   style="width: 100%; padding: 14px 16px; background: transparent; border: 2px solid #444748; color: #e2e2e2; font-size: 1rem; transition: border-color 0.15s; outline: none;"
                                   onfocus="this.style.borderColor='#ffffff'" onblur="this.style.borderColor='#444748'"
                                   placeholder="Your name">
                        </div>
                        <div>
                            <label style="display: block; font-family: 'Space Mono', monospace; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.15em; color: #e2e2e2; opacity: 0.5; margin-bottom: 8px;">Email</label>
                            <input type="email" name="email" required
                                   style="width: 100%; padding: 14px 16px; background: transparent; border: 2px solid #444748; color: #e2e2e2; font-size: 1rem; transition: border-color 0.15s; outline: none;"
                                   onfocus="this.style.borderColor='#ffffff'" onblur="this.style.borderColor='#444748'"
                                   placeholder="your@email.com">
                        </div>
                        <div>
                            <label style="display: block; font-family: 'Space Mono', monospace; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.15em; color: #e2e2e2; opacity: 0.5; margin-bottom: 8px;">Message</label>
                            <textarea name="message" rows="4" required
                                      style="width: 100%; padding: 14px 16px; background: transparent; border: 2px solid #444748; color: #e2e2e2; font-size: 1rem; resize: none; transition: border-color 0.15s; outline: none;"
                                      onfocus="this.style.borderColor='#ffffff'" onblur="this.style.borderColor='#444748'"
                                      placeholder="How can we help you?"></textarea>
                        </div>
                        <button type="submit"
                                style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 8px; padding: 16px 40px; background: #ffffff; color: #131313; font-family: 'Space Mono', monospace; font-size: 14px; font-weight: 700; letter-spacing: 0.2em; text-transform: uppercase; border: 4px solid #ffffff; cursor: pointer; transition: all 0.15s;"
                                onmouseover="this.style.background='#72ff70'; this.style.borderColor='#72ff70';"
                                onmouseout="this.style.background='#ffffff'; this.style.borderColor='#ffffff';">
                            SEND_MESSAGE
                            <span class="material-symbols-outlined" style="font-size: 16px;">send</span>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Map --}}
            @if($business->latitude && $business->longitude)
            <div style="border: 4px solid #ffffff; overflow: hidden;">
                <div id="contact-map" style="width: 100%; height: 400px;"></div>
                <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
                <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        var map = L.map('contact-map').setView([{{ $business->latitude }}, {{ $business->longitude }}], 15);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '&copy; OpenStreetMap contributors'
                        }).addTo(map);
                        L.marker([{{ $business->latitude }}, {{ $business->longitude }}]).addTo(map)
                            .bindPopup('{{ addslashes($business->name) }}').openPopup();
                    });
                </script>
            </div>
            @endif
        </div>
    </main>

    @include('themes::bold.store.footer')
</div>
</body>
</html>
