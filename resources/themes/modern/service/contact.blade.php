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
        input, textarea, select { font-family: 'Plus Jakarta Sans', sans-serif; }
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
        input.glass-input, textarea.glass-input, select.glass-input { width: 100%; padding: 16px 20px; background: rgba(255,255,255,0.7); border: 1px solid rgba(0,0,0,0.08); border-radius: 8px; color: #1a1a2e; font-size: 1rem; outline: none; transition: all 0.25s ease; }
        input.glass-input:focus, textarea.glass-input:focus, select.glass-input:focus { border-color: #4648d4; box-shadow: 0 0 0 3px rgba(70,72,212,0.15); }
    </style>
</head>
<body>
<div class="min-h-screen flex flex-col">
    @include('themes::modern.service.header')

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

            <div style="display: grid; grid-template-columns: repeat(12, 1fr); gap: 64px;">
                <div style="grid-column: 1 / 5;">
                    <p style="font-size: 1rem; line-height: 1.7; color: #4b5563; margin-bottom: 40px;">{{ $business->description ?? 'Get in touch with us for more information about our services.' }}</p>

                    <div class="glass-card" style="padding: 32px;">
                        @if($business->location)
                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 24px; padding-bottom: 24px; border-bottom: 1px solid rgba(0,0,0,0.06);">
                            <span style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background: rgba(70,72,212,0.08); color: #4648d4; border-radius: 10px;">
                                <span class="material-symbols-outlined" style="font-size: 18px;">location_on</span>
                            </span>
                            <span style="font-size: 0.9375rem; color: #1a1a2e;">{{ $business->location }}</span>
                        </div>
                        @endif
                        @if($business->phone)
                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 24px; padding-bottom: 24px; border-bottom: 1px solid rgba(0,0,0,0.06);">
                            <span style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background: rgba(70,72,212,0.08); color: #4648d4; border-radius: 10px;">
                                <span class="material-symbols-outlined" style="font-size: 18px;">call</span>
                            </span>
                            <a href="tel:{{ $business->phone }}" style="font-size: 0.9375rem; color: #1a1a2e; font-weight: 500;" onmouseover="this.style.color='#4648d4'" onmouseout="this.style.color='#1a1a2e'">{{ $business->phone }}</a>
                        </div>
                        @endif
                        @if($business->email)
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <span style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background: rgba(70,72,212,0.08); color: #4648d4; border-radius: 10px;">
                                <span class="material-symbols-outlined" style="font-size: 18px;">mail</span>
                            </span>
                            <a href="mailto:{{ $business->email }}" style="font-size: 0.9375rem; color: #1a1a2e; font-weight: 500;" onmouseover="this.style.color='#4648d4'" onmouseout="this.style.color='#1a1a2e'">{{ $business->email }}</a>
                        </div>
                        @endif
                    </div>
                </div>

                <div style="grid-column: 6 / 13;">
                    @if (session('success'))
                    <div style="padding: 16px 24px; background: linear-gradient(135deg, #4648d4, #006591); color: #ffffff; border-radius: 8px; margin-bottom: 32px; font-weight: 600;">{{ session('success') }}</div>
                    @endif
                    <div class="glass-card" style="padding: 40px;">
                        <h3 style="font-weight: 700; font-size: 1.25rem; color: #1a1a2e; margin-bottom: 32px;">Send a Message</h3>
                        <form wire:submit="submit" style="display: flex; flex-direction: column; gap: 24px;">
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
                                <label style="display: block; font-size: 0.8125rem; font-weight: 600; color: #1a1a2e; margin-bottom: 8px;">Service *</label>
                                <select wire:model="service" class="glass-input">
                                    <option value="" disabled selected>Select a service</option>
                                    @foreach($business->services as $svc)
                                    <option value="{{ $svc->name }}">{{ $svc->name }}</option>
                                    @endforeach
                                </select>
                                @error('service') <span style="font-size: 0.75rem; color: #b90538; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
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
        </div>
    </main>

    @include('themes::modern.service.footer')
</div>
</body>
</html>
