{{--
  Public-facing business profile rendered by the PublicProfile Livewire component.
  Injects active theme CSS variables and shows business info, contact, and listings.
--}}
<div>
    {{-- Theme CSS variables — injected when the business has a customised theme. --}}
    @if ($themeColors)
        <style>
            :root {
                --color-primary: {{ $themeColors['primary'] ?? '#4F46E5' }};
                --color-secondary: {{ $themeColors['secondary'] ?? '#10B981' }};
                --color-background: {{ $themeColors['background'] ?? '#FFFFFF' }};
                --color-text: {{ $themeColors['text'] ?? '#111827' }};
                --color-accent: {{ $themeColors['accent'] ?? '#F59E0B' }};
            }
        </style>
    @endif

    <div class="mx-auto max-w-5xl px-4 py-12 sm:px-6 lg:px-8"
         style="@if ($themeColors) background-color: var(--color-background); color: var(--color-text); @endif">

        {{-- Business header --}}
        <div class="flex flex-col items-center text-center sm:flex-row sm:text-left sm:items-start sm:gap-8">
            <div class="flex h-24 w-24 shrink-0 items-center justify-center rounded-full text-3xl font-bold"
                 style="background-color: {{ $themeColors['primary'] ?? '#4F46E5' }}20; color: {{ $themeColors['primary'] ?? '#4F46E5' }}">
                {{ substr($business->name, 0, 2) }}
            </div>
            <div class="mt-4 sm:mt-0 sm:flex-1">
                <div class="flex items-center gap-3 justify-center sm:justify-start">
                    <h1 class="text-2xl font-bold" style="color: var(--color-text)">{{ $business->name }}</h1>
                    <span class="rounded-full px-2.5 py-0.5 text-xs font-medium"
                          style="background-color: {{ $themeColors['primary'] ?? '#4F46E5' }}15; color: {{ $themeColors['primary'] ?? '#4F46E5' }}">
                        {{ $business->isProductStore() ? 'Store' : 'Service' }}
                    </span>
                </div>
                @if ($business->category)
                    <p class="mt-1 text-sm" style="color: {{ $themeColors['text'] ?? '#111827' }}80">{{ $business->category->name }}</p>
                @endif
                <p class="mt-2" style="color: {{ $themeColors['text'] ?? '#111827' }}cc">{{ $business->description }}</p>

                {{-- Contact links. --}}
                <div class="mt-4 flex flex-wrap gap-4 justify-center sm:justify-start">
                    @if ($business->email)
                        <a href="mailto:{{ $business->email }}" class="inline-flex items-center gap-1.5 text-sm hover:underline"
                           style="color: {{ $themeColors['primary'] ?? '#4F46E5' }}">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            {{ $business->email }}
                        </a>
                    @endif
                    @if ($business->phone)
                        <a href="tel:{{ $business->phone }}" class="inline-flex items-center gap-1.5 text-sm hover:underline"
                           style="color: {{ $themeColors['primary'] ?? '#4F46E5' }}"
                           onclick="navigator.sendBeacon('{{ route('track.click', [$business->id, 'phone_click']) }}?source=profile&url='+encodeURIComponent(location.href));">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            {{ $business->phone }}
                        </a>
                    @endif
                    @if ($business->whatsapp)
                        <a href="https://wa.me/{{ $business->whatsapp }}" target="_blank" class="inline-flex items-center gap-1.5 text-sm hover:underline"
                           style="color: {{ $themeColors['primary'] ?? '#4F46E5' }}"
                           onclick="navigator.sendBeacon('{{ route('track.click', [$business->id, 'whatsapp_click']) }}?source=profile&url='+encodeURIComponent(location.href));">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            WhatsApp
                        </a>
                    @endif
                </div>

                @if ($business->address)
                    <p class="mt-3 text-sm" style="color: {{ $themeColors['text'] ?? '#111827' }}80">
                        {{ $business->address }}{{ $business->city ? ', ' . $business->city : '' }}{{ $business->state ? ', ' . $business->state : '' }}
                    </p>
                @endif
            </div>
        </div>

        {{-- Products or services listing area — populated when those domains are built. --}}
        <div class="mt-12">
            <h2 class="text-xl font-bold" style="color: var(--color-text)">
                {{ $business->isProductStore() ? 'Products' : 'Services' }}
            </h2>
            <p class="mt-4 text-sm" style="color: {{ $themeColors['text'] ?? '#111827' }}80">
                {{ $business->isProductStore()
                    ? 'No products listed yet. Check back later.'
                    : 'No services listed yet. Check back later.' }}
            </p>
        </div>
    </div>
</div>
