{{--
  Theme list grid — shows available themes for the given business type.
  Cards display name, description, thumbnail, and premium badge.
--}}
<div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
    @forelse ($themes as $theme)
        <div class="rounded-xl border border-gray-200 bg-white overflow-hidden shadow-sm hover:shadow-md transition">
            <div class="aspect-video bg-gray-100 flex items-center justify-center">
                @if ($theme->thumbnail)
                    <img src="{{ Storage::url($theme->thumbnail) }}" alt="{{ $theme->name }}" class="h-full w-full object-cover">
                @else
                    <span class="text-5xl text-gray-300">🎨</span>
                @endif
            </div>
            <div class="p-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-900">{{ $theme->name }}</h3>
                    @if ($theme->is_premium)
                        <span class="rounded-full bg-yellow-100 px-2 py-0.5 text-[10px] font-medium text-yellow-800">Premium</span>
                    @endif
                </div>
                @if ($theme->description)
                    <p class="mt-1 text-xs text-gray-500">{{ $theme->description }}</p>
                @endif
            </div>
        </div>
    @empty
        <div class="col-span-full rounded-lg border border-dashed border-gray-300 bg-white p-12 text-center">
            <p class="text-sm text-gray-500">No themes available for your business type yet.</p>
        </div>
    @endforelse
</div>
