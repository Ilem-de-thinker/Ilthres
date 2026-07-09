{{--
  Theme renderer — injects CSS variables and renders the active theme layout.
  Merges theme defaults with per-business customization for the full visual shell.
--}}
<div>
    {{-- Inject theme CSS variables into <head> via Livewire's @push. --}}
    @push('styles')
        <style>
            :root {
                --color-primary: {{ $colors['primary'] ?? '#3B82F6' }};
                --color-secondary: {{ $colors['secondary'] ?? '#10B981' }};
                --color-background: {{ $colors['background'] ?? '#FFFFFF' }};
                --color-text: {{ $colors['text'] ?? '#111827' }};
                --color-accent: {{ $colors['accent'] ?? '#F59E0B' }};
                --font-heading: '{{ $typography['heading'] ?? 'Inter' }}', sans-serif;
                --font-body: '{{ $typography['body'] ?? 'Inter' }}', sans-serif;
                --radius: {{ $radius ?? '0.5rem' }};
                --animations: {{ $animations ? '1' : '0' }};
            }
        </style>
    @endpush

    {{-- The business profile content is rendered by the PublicProfile component.
         This renderer only provides the visual shell (CSS variables). --}}
    <div class="theme-shell" style="
        background-color: var(--color-background);
        color: var(--color-text);
        font-family: var(--font-body);
        border-radius: var(--radius);
        animation-enabled: var(--animations);
    ">
        {{ $slot ?? '' }}
    </div>
</div>
