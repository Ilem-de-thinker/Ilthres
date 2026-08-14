<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Contact Us — {{ $business->name }}</title>
    @if (!empty($business))
        <meta name="description" content="{{ Str::limit(strip_tags($business->description ?? $business->about ?? $business->name), 160) }}">
    @endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400;1,700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #fcf9f2; color: #1c1c18; line-height: 1.6; -webkit-font-smoothing: antialiased; min-height: 100vh; display: flex; flex-direction: column; overflow-x: hidden; }
        main { flex: 1; }
        h1, h2, h3, h4 { font-family: 'Playfair Display', serif; line-height: 1.15; }
        a { text-decoration: none; transition: all 0.2s ease; }
        input, textarea, select { font-family: 'Plus Jakarta Sans', sans-serif; }
        ::selection { background: #6c5e06; color: #fff; }
        .thin-frame { padding: 16px; border: 0.5px solid #E5E0D5; }
        .subtle-shadow { box-shadow: 0px 10px 40px rgba(0,0,0,0.03); }
        .section-label { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.6875rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1eee7; }
        ::-webkit-scrollbar-thumb { background: #c5c7c1; border-radius: 3px; }
    </style>
</head>
<body>
<div class="min-h-screen flex flex-col">
    @include('themes::default.service.header')

    <main class="flex-1" style="padding-top: 112px; padding-bottom: 96px;">
        <div style="max-width: 1280px; margin: 0 auto; padding: 0 64px;">
            <div style="margin-bottom: 64px;">
                <a href="{{ route('business.site', $business) }}" style="display: inline-flex; align-items: center; gap: 6px; margin-bottom: 24px; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #454843; text-decoration: none;" onmouseover="this.style.color='#6c5e06'" onmouseout="this.style.color='#454843'">
                    <span class="material-symbols-outlined" style="font-size: 16px;">arrow_back</span> Back to Profile
                </a>
                <h1 style="font-family: 'Playfair Display', serif; font-style: italic; font-size: clamp(2rem, 4vw, 3rem); color: #1c1c18;">Contact Us</h1>
            </div>

            <div style="display: grid; grid-template-columns: repeat(12, 1fr); gap: 32px; margin-bottom: 64px;">
                {{-- Left: Contact Info (5 cols) --}}
                <div style="grid-column: 1 / 6;">
                    <p style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.9375rem; line-height: 1.7; color: #454843; margin-bottom: 32px;">{{ $business->description ?? 'Get in touch with us for more information about our services.' }}</p>

                    <div class="thin-frame" style="border-radius: 0.5rem;">
                        @if($business->location)
                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px; padding-bottom: 20px; border-bottom: 0.5px solid #E5E0D5;">
                            <span class="material-symbols-outlined" style="color: #6c5e06; font-size: 20px;">location_on</span>
                            <span style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.875rem; color: #454843;">{{ $business->location }}</span>
                        </div>
                        @endif
                        @if($business->phone)
                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px; padding-bottom: 20px; border-bottom: 0.5px solid #E5E0D5;">
                            <span class="material-symbols-outlined" style="color: #6c5e06; font-size: 20px;">call</span>
                            <a href="tel:{{ $business->phone }}" style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.875rem; color: #454843;" onmouseover="this.style.color='#1c1c18'" onmouseout="this.style.color='#454843'">{{ $business->phone }}</a>
                        </div>
                        @endif
                        @if($business->email)
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <span class="material-symbols-outlined" style="color: #6c5e06; font-size: 20px;">mail</span>
                            <a href="mailto:{{ $business->email }}" style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.875rem; color: #454843;" onmouseover="this.style.color='#1c1c18'" onmouseout="this.style.color='#454843'">{{ $business->email }}</a>
                        </div>
                        @endif
                    </div>

                    @if($business->whatsapp)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $business->whatsapp) }}" target="_blank"
                       style="display: inline-flex; align-items: center; gap: 8px; padding: 16px 32px; background: #1c1c18; color: #fcf9f2; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.6875rem; font-weight: 600; letter-spacing: 0.05em; text-transform: uppercase; border: 0.5px solid #1c1c18; border-radius: 0.25rem; transition: all 0.2s; text-decoration: none; margin-top: 24px;"
                       onmouseover="this.style.background='#454843'; this.style.borderColor='#454843';"
                       onmouseout="this.style.background='#1c1c18'; this.style.borderColor='#1c1c18';">
                        <span class="material-symbols-outlined" style="font-size: 16px;">chat</span> CHAT ON WHATSAPP
                    </a>
                    @endif
                </div>

                {{-- Right: Contact Form (7 cols) --}}
                <div style="grid-column: 6 / 13;">
                    @if (session('success'))
                    <div style="padding: 16px 24px; background: #6c5e06; color: #fff; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.75rem; font-weight: 600; letter-spacing: 0.05em; text-transform: uppercase; margin-bottom: 24px; border-radius: 0.25rem;">{{ session('success') }}</div>
                    @endif
                    <div class="thin-frame subtle-shadow" style="padding: 40px; border-radius: 0.5rem;">
                        <h3 style="font-family: 'Playfair Display', serif; font-style: italic; font-size: 1.25rem; color: #1c1c18; margin-bottom: 24px;">Send a Message</h3>
                        <form wire:submit="submit" style="display: flex; flex-direction: column; gap: 20px;">
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                                <div>
                                    <label class="section-label" style="color: #757873; display: block; margin-bottom: 8px;">NAME *</label>
                                    <input type="text" wire:model="name" required
                                        style="width: 100%; padding: 14px 16px; background: transparent; border: 0.5px solid #E5E0D5; color: #1c1c18; font-size: 0.875rem; outline: none; transition: border-color 0.2s; border-radius: 0.25rem;"
                                        onfocus="this.style.borderColor='#6c5e06'"
                                        onblur="this.style.borderColor='#E5E0D5'">
                                    @error('name') <span style="font-size: 0.6875rem; color: #b91c1c; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="section-label" style="color: #757873; display: block; margin-bottom: 8px;">PHONE *</label>
                                    <input type="tel" wire:model="phone" required
                                        style="width: 100%; padding: 14px 16px; background: transparent; border: 0.5px solid #E5E0D5; color: #1c1c18; font-size: 0.875rem; outline: none; transition: border-color 0.2s; border-radius: 0.25rem;"
                                        onfocus="this.style.borderColor='#6c5e06'"
                                        onblur="this.style.borderColor='#E5E0D5'">
                                    @error('phone') <span style="font-size: 0.6875rem; color: #b91c1c; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div>
                                <label class="section-label" style="color: #757873; display: block; margin-bottom: 8px;">EMAIL *</label>
                                <input type="email" wire:model="email" required
                                    style="width: 100%; padding: 14px 16px; background: transparent; border: 0.5px solid #E5E0D5; color: #1c1c18; font-size: 0.875rem; outline: none; transition: border-color 0.2s; border-radius: 0.25rem;"
                                    onfocus="this.style.borderColor='#6c5e06'"
                                    onblur="this.style.borderColor='#E5E0D5'">
                                @error('email') <span style="font-size: 0.6875rem; color: #b91c1c; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="section-label" style="color: #757873; display: block; margin-bottom: 8px;">SERVICE *</label>
                                <select wire:model="service"
                                    style="width: 100%; padding: 14px 16px; background: transparent; border: 0.5px solid #E5E0D5; color: #1c1c18; font-size: 0.875rem; outline: none; transition: border-color 0.2s; appearance: none; cursor: pointer; border-radius: 0.25rem;"
                                    onfocus="this.style.borderColor='#6c5e06'"
                                    onblur="this.style.borderColor='#E5E0D5'">
                                    <option value="" disabled selected style="background: #fcf9f2; color: #757873;">Select a service</option>
                                    @foreach($business->services as $svc)
                                    <option value="{{ $svc->name }}" style="background: #fcf9f2; color: #1c1c18;">{{ $svc->name }}</option>
                                    @endforeach
                                </select>
                                @error('service') <span style="font-size: 0.6875rem; color: #b91c1c; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="section-label" style="color: #757873; display: block; margin-bottom: 8px;">MESSAGE *</label>
                                <textarea wire:model="message" rows="5" required
                                    style="width: 100%; padding: 14px 16px; background: transparent; border: 0.5px solid #E5E0D5; color: #1c1c18; font-size: 0.875rem; outline: none; transition: border-color 0.2s; resize: vertical; border-radius: 0.25rem;"
                                    onfocus="this.style.borderColor='#6c5e06'"
                                    onblur="this.style.borderColor='#E5E0D5'"></textarea>
                                @error('message') <span style="font-size: 0.6875rem; color: #b91c1c; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                            </div>
                            <button type="submit" wire:loading.attr="disabled"
                                style="width: 100%; padding: 16px 40px; background: #1c1c18; color: #fcf9f2; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.6875rem; font-weight: 600; letter-spacing: 0.05em; text-transform: uppercase; border: 0.5px solid #1c1c18; border-radius: 0.25rem; cursor: pointer; transition: all 0.2s;"
                                onmouseover="this.style.background='#454843'; this.style.borderColor='#454843';"
                                onmouseout="this.style.background='#1c1c18'; this.style.borderColor='#1c1c18';">
                                SEND MESSAGE
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            @if($business->latitude && $business->longitude)
            <div class="thin-frame" style="overflow: hidden; border-radius: 0.5rem;">
                <div id="contact-map" style="width: 100%; height: 400px;"></div>
                <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
                <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        var map = L.map('contact-map').setView([{{ $business->latitude }}, {{ $business->longitude }}], 15);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap' }).addTo(map);
                        L.marker([{{ $business->latitude }}, {{ $business->longitude }}]).addTo(map).bindPopup('{{ addslashes($business->name) }}').openPopup();
                    });
                </script>
            </div>
            @endif
        </div>
    </main>

    @include('themes::default.service.footer')
</div>
</body>
</html>
