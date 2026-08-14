<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Contact — {{ $business->name }}</title>
    @if (!empty($business))
        <meta name="description" content="{{ Str::limit(strip_tags($business->description ?? $business->about ?? $business->name), 160) }}">
    @endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: linear-gradient(135deg, #f0f2ff 0%, #e8f4ff 50%, #fff 100%); color: #1a1a2e; line-height: 1.6; -webkit-font-smoothing: antialiased; min-height: 100vh; display: flex; flex-direction: column; overflow-x: hidden; }
        main { flex: 1; }
        h1, h2, h3, h4 { font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; letter-spacing: -0.025em; line-height: 1.1; }
        a { text-decoration: none; transition: all 0.25s ease; }
        input, textarea { font-family: 'Plus Jakarta Sans', sans-serif; }
        ::selection { background: #4648d4; color: #ffffff; }
        .glass { background: rgba(255,255,255,0.7); backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.2); border-radius: 16px; }
        .glass-card { background: rgba(255,255,255,0.7); backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.2); border-radius: 16px; transition: all 0.3s ease; }
        .btn-primary { display: inline-flex; align-items: center; gap: 8px; padding: 16px 40px; background: linear-gradient(135deg, #4648d4, #006591); color: #ffffff; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 24px rgba(70,72,212,0.3); text-decoration: none; width: 100%; justify-content: center; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 32px rgba(70,72,212,0.4); }
        .btn-secondary { display: inline-flex; align-items: center; gap: 8px; padding: 16px 40px; background: rgba(255,255,255,0.7); border: 1px solid rgba(70,72,212,0.3); color: #4648d4; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; text-decoration: none; backdrop-filter: blur(8px); }
        .btn-secondary:hover { background: #4648d4; color: #ffffff; border-color: #4648d4; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #4648d4; border-radius: 3px; }
        input.glass-input, textarea.glass-input { width: 100%; padding: 16px 20px; background: rgba(255,255,255,0.7); border: 1px solid rgba(0,0,0,0.08); border-radius: 8px; color: #1a1a2e; font-size: 1rem; outline: none; transition: all 0.25s ease; }
        input.glass-input:focus, textarea.glass-input:focus { border-color: #4648d4; box-shadow: 0 0 0 3px rgba(70,72,212,0.15); }
    </style>
</head>
<body>
<div class="min-h-screen flex flex-col">
    @include('themes::modern.store.header')

    <main class="flex-1" style="padding-top: 96px; padding-bottom: 64px;">
        <div style="max-width: 1280px; margin: 0 auto; padding: 0 40px;">
            <div style="margin-bottom: 48px;">
                <a href="{{ route('business.site', $business) }}" style="display: inline-flex; align-items: center; gap: 6px; margin-bottom: 24px; font-size: 0.875rem; font-weight: 500; color: #4648d4; text-decoration: none;" onmouseover="this.style.color='#006591'" onmouseout="this.style.color='#4648d4'">
                    <span class="material-symbols-outlined" style="font-size: 16px;">arrow_back</span> Back to Profile
                </a>
                <div>
                    <span class="label" style="color: #4648d4; margin-bottom: 8px; display: block;">Contact</span>
                    <h1 style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: clamp(2rem, 4vw, 3rem); letter-spacing: -0.03em; color: #1a1a2e;">Get in Touch</h1>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(12, 1fr); gap: 24px;">
                {{-- Info (5 cols) --}}
                <div style="grid-column: 1 / 6;">
                    <div class="glass-card" style="padding: 40px;">
                        @if($business->email)
                        <div style="display: flex; align-items: flex-start; gap: 16px; margin-bottom: 32px;">
                            <div style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; background: rgba(70,72,212,0.08); border-radius: 12px;">
                                <span class="material-symbols-outlined" style="font-size: 20px; color: #4648d4;">mail</span>
                            </div>
                            <div>
                                <div style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: #9ca3af; margin-bottom: 4px;">Email</div>
                                <a href="mailto:{{ $business->email }}" style="font-size: 0.9375rem; color: #1a1a2e; font-weight: 500;" onmouseover="this.style.color='#4648d4'" onmouseout="this.style.color='#1a1a2e'">{{ $business->email }}</a>
                            </div>
                        </div>
                        @endif
                        @if($business->phone)
                        <div style="display: flex; align-items: flex-start; gap: 16px; margin-bottom: 32px;">
                            <div style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; background: rgba(70,72,212,0.08); border-radius: 12px;">
                                <span class="material-symbols-outlined" style="font-size: 20px; color: #4648d4;">call</span>
                            </div>
                            <div>
                                <div style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: #9ca3af; margin-bottom: 4px;">Phone</div>
                                <a href="tel:{{ $business->phone }}" style="font-size: 0.9375rem; color: #1a1a2e; font-weight: 500;" onmouseover="this.style.color='#4648d4'" onmouseout="this.style.color='#1a1a2e'">{{ $business->phone }}</a>
                            </div>
                        </div>
                        @endif
                        @if($business->whatsapp)
                        <div style="display: flex; align-items: flex-start; gap: 16px; margin-bottom: 32px;">
                            <div style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; background: rgba(70,72,212,0.08); border-radius: 12px;">
                                <span class="material-symbols-outlined" style="font-size: 20px; color: #4648d4;">chat</span>
                            </div>
                            <div>
                                <div style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: #9ca3af; margin-bottom: 4px;">WhatsApp</div>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $business->whatsapp) }}" target="_blank" style="font-size: 0.9375rem; color: #1a1a2e; font-weight: 500;" onmouseover="this.style.color='#4648d4'" onmouseout="this.style.color='#1a1a2e'">{{ $business->whatsapp }}</a>
                            </div>
                        </div>
                        @endif
                        @if($business->address)
                        <div style="display: flex; align-items: flex-start; gap: 16px;">
                            <div style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; background: rgba(70,72,212,0.08); border-radius: 12px;">
                                <span class="material-symbols-outlined" style="font-size: 20px; color: #4648d4;">location_on</span>
                            </div>
                            <div>
                                <div style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: #9ca3af; margin-bottom: 4px;">Address</div>
                                <span style="font-size: 0.9375rem; color: #1a1a2e;">{{ $business->address }}{{ $business->city ? ', ' . $business->city : '' }}{{ $business->state ? ', ' . $business->state : '' }}</span>
                            </div>
                        </div>
                        @endif

                        <div style="display: flex; flex-wrap: wrap; gap: 12px; margin-top: 40px; padding-top: 32px; border-top: 1px solid rgba(0,0,0,0.06);">
                            @if($business->whatsapp)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $business->whatsapp) }}" target="_blank" class="btn-primary" style="flex: 1;">
                                <span class="material-symbols-outlined" style="font-size: 16px;">chat</span> WhatsApp
                            </a>
                            @endif
                            @if($business->phone)
                            <a href="tel:{{ $business->phone }}" class="btn-secondary" style="flex: 1;">
                                <span class="material-symbols-outlined" style="font-size: 16px;">call</span> Call
                            </a>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Form (7 cols) --}}
                <div style="grid-column: 6 / 13;">
                    <div class="glass-card" style="padding: 40px;">
                        <h3 style="font-weight: 700; font-size: 1.25rem; color: #1a1a2e; margin-bottom: 32px;">Send a Message</h3>
                        <form wire:submit="submit" style="display: flex; flex-direction: column; gap: 24px;">
                            @csrf
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                                <div>
                                    <label style="display: block; font-size: 0.8125rem; font-weight: 600; color: #1a1a2e; margin-bottom: 8px;">Name *</label>
                                    <input type="text" wire:model="name" required class="glass-input">
                                    @error('name') <span style="font-size: 0.75rem; color: #b90538; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label style="display: block; font-size: 0.8125rem; font-weight: 600; color: #1a1a2e; margin-bottom: 8px;">Phone *</label>
                                    <input type="tel" wire:model="phone" required class="glass-input">
                                    @error('phone') <span style="font-size: 0.75rem; color: #b90538; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div>
                                <label style="display: block; font-size: 0.8125rem; font-weight: 600; color: #1a1a2e; margin-bottom: 8px;">Email *</label>
                                <input type="email" wire:model="email" required class="glass-input">
                                @error('email') <span style="font-size: 0.75rem; color: #b90538; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label style="display: block; font-size: 0.8125rem; font-weight: 600; color: #1a1a2e; margin-bottom: 8px;">Message *</label>
                                <textarea wire:model="message" rows="5" required class="glass-input" style="resize: vertical;"></textarea>
                                @error('message') <span style="font-size: 0.75rem; color: #b90538; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                            </div>
                            <button type="submit" wire:loading.attr="disabled" class="btn-primary" style="width: 100%; justify-content: center;">
                                Send Message
                                <span class="material-symbols-outlined" style="font-size: 18px;">send</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Map --}}
            @if($business->latitude && $business->longitude)
            <div style="margin-top: 48px;">
                <div class="glass" style="overflow: hidden;">
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
            </div>
            @endif
        </div>
    </main>

    @include('themes::modern.store.footer')
</div>
</body>
</html>
