<!DOCTYPE html>
<html lang="en" class="dark">
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
        ::selection { background: #72ff70; color: #002203; }
        .dot-pattern { background-image: radial-gradient(circle, #444748 1px, transparent 1px); background-size: 24px 24px; }
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #131313; }
        ::-webkit-scrollbar-thumb { background: #ffffff; border: 2px solid #131313; }
    </style>
</head>
<body>
<div class="min-h-screen flex flex-col">
    @include('themes::bold.service.header')

    <main class="flex-1" style="padding-top: 96px; padding-bottom: 64px;">
        <div style="max-width: 1280px; margin: 0 auto; padding: 0 64px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-end; padding-bottom: 16px; border-bottom: 4px solid #ffffff; margin-bottom: 64px;">
                <h1 style="font-family: 'Anton', sans-serif; font-size: 3rem; color: #ffffff;">CONTACT_US</h1>
                <span style="font-family: 'Space Mono', monospace; font-size: 10px; text-transform: uppercase; letter-spacing: 0.15em; color: #e2e2e2; opacity: 0.6;">SECTION_04</span>
            </div>

            <div style="display: grid; grid-template-columns: repeat(12, 1fr); gap: 64px;">
                <div style="grid-column: 1 / 5;">
                    <div style="padding-right: 32px; border-right: 4px solid #ffffff;">
                        <p style="font-family: 'Archivo Narrow', sans-serif; font-size: 1.125rem; line-height: 1.7; color: #e2e2e2; opacity: 0.85; margin-bottom: 40px;">{{ $business->description ?? 'Get in touch with us for more information about our services.' }}</p>

                        <div style="margin-bottom: 40px;">
                            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 24px; padding-bottom: 24px; border-bottom: 2px solid #444748;">
                                <span class="material-symbols-outlined" style="color: #72ff70; font-size: 20px;">location_on</span>
                                <span style="font-family: 'Space Mono', monospace; font-size: 14px; color: #e2e2e2;">{{ $business->location }}</span>
                            </div>
                            @if($business->phone)
                            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 24px; padding-bottom: 24px; border-bottom: 2px solid #444748;">
                                <span class="material-symbols-outlined" style="color: #72ff70; font-size: 20px;">call</span>
                                <a href="tel:{{ $business->phone }}" style="color: #e2e2e2; font-family: 'Space Mono', monospace; font-size: 14px;" onmouseover="this.style.color='#72ff70'" onmouseout="this.style.color='#e2e2e2'">{{ $business->phone }}</a>
                            </div>
                            @endif
                            @if($business->email)
                            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 24px; padding-bottom: 24px; border-bottom: 2px solid #444748;">
                                <span class="material-symbols-outlined" style="color: #72ff70; font-size: 20px;">mail</span>
                                <a href="mailto:{{ $business->email }}" style="color: #e2e2e2; font-family: 'Space Mono', monospace; font-size: 14px;" onmouseover="this.style.color='#72ff70'" onmouseout="this.style.color='#e2e2e2'">{{ $business->email }}</a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div style="grid-column: 6 / 13;">
                    @if (session('success'))
                    <div style="padding: 16px 24px; background: #72ff70; color: #002203; font-family: 'Space Mono', monospace; font-size: 14px; font-weight: 700; letter-spacing: 0.15em; text-transform: uppercase; margin-bottom: 32px; border: 4px solid #ffffff;">{{ session('success') }}</div>
                    @endif
                    <form wire:submit="submit" style="display: flex; flex-direction: column; gap: 24px;">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                            <div>
                                <label style="display: block; font-family: 'Space Mono', monospace; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.15em; color: #e2e2e2; margin-bottom: 8px;">NAME *</label>
                                <input type="text" wire:model="name" required
                                    style="width: 100%; padding: 16px; background: #131313; border: 4px solid #444748; color: #ffffff; font-family: 'Archivo Narrow', sans-serif; font-size: 1rem; outline: none; transition: border-color 0.15s;"
                                    onfocus="this.style.borderColor='#ffffff'"
                                    onblur="this.style.borderColor='#444748'">
                                @error('name') <span style="font-size: 12px; color: #ff6b6b; font-family: 'Space Mono', monospace; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label style="display: block; font-family: 'Space Mono', monospace; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.15em; color: #e2e2e2; margin-bottom: 8px;">PHONE *</label>
                                <input type="tel" wire:model="phone" required
                                    style="width: 100%; padding: 16px; background: #131313; border: 4px solid #444748; color: #ffffff; font-family: 'Archivo Narrow', sans-serif; font-size: 1rem; outline: none; transition: border-color 0.15s;"
                                    onfocus="this.style.borderColor='#ffffff'"
                                    onblur="this.style.borderColor='#444748'">
                                @error('phone') <span style="font-size: 12px; color: #ff6b6b; font-family: 'Space Mono', monospace; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div>
                            <label style="display: block; font-family: 'Space Mono', monospace; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.15em; color: #e2e2e2; margin-bottom: 8px;">EMAIL *</label>
                            <input type="email" wire:model="email" required
                                style="width: 100%; padding: 16px; background: #131313; border: 4px solid #444748; color: #ffffff; font-family: 'Archivo Narrow', sans-serif; font-size: 1rem; outline: none; transition: border-color 0.15s;"
                                onfocus="this.style.borderColor='#ffffff'"
                                onblur="this.style.borderColor='#444748'">
                            @error('email') <span style="font-size: 12px; color: #ff6b6b; font-family: 'Space Mono', monospace; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label style="display: block; font-family: 'Space Mono', monospace; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.15em; color: #e2e2e2; margin-bottom: 8px;">SERVICE *</label>
                            <select wire:model="service"
                                style="width: 100%; padding: 16px; background: #131313; border: 4px solid #444748; color: #ffffff; font-family: 'Archivo Narrow', sans-serif; font-size: 1rem; outline: none; transition: border-color 0.15s; appearance: none; cursor: pointer;"
                                onfocus="this.style.borderColor='#ffffff'"
                                onblur="this.style.borderColor='#444748'">
                                <option value="" disabled selected style="background: #131313; color: #6c757d;">Select a service</option>
                                @foreach($business->services as $svc)
                                <option value="{{ $svc->name }}" style="background: #131313; color: #ffffff;">{{ $svc->name }}</option>
                                @endforeach
                            </select>
                            @error('service') <span style="font-size: 12px; color: #ff6b6b; font-family: 'Space Mono', monospace; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label style="display: block; font-family: 'Space Mono', monospace; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.15em; color: #e2e2e2; margin-bottom: 8px;">MESSAGE *</label>
                            <textarea wire:model="message" rows="5" required
                                style="width: 100%; padding: 16px; background: #131313; border: 4px solid #444748; color: #ffffff; font-family: 'Archivo Narrow', sans-serif; font-size: 1rem; outline: none; transition: border-color 0.15s; resize: vertical;"
                                onfocus="this.style.borderColor='#ffffff'"
                                onblur="this.style.borderColor='#444748'"></textarea>
                            @error('message') <span style="font-size: 12px; color: #ff6b6b; font-family: 'Space Mono', monospace; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                        </div>
                        <button type="submit" wire:loading.attr="disabled"
                            style="width: 100%; padding: 20px 48px; background: #ffffff; color: #131313; font-family: 'Space Mono', monospace; font-size: 14px; font-weight: 700; letter-spacing: 0.2em; text-transform: uppercase; border: 4px solid #ffffff; cursor: pointer; transition: all 0.15s;"
                            onmouseover="this.style.background='#72ff70'; this.style.borderColor='#72ff70';"
                            onmouseout="this.style.background='#ffffff'; this.style.borderColor='#ffffff';">
                            SEND_MESSAGE
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    @include('themes::bold.service.footer')
</div>
</body>
</html>
