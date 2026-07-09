{{--
  Theme preview iframe — embeds ThemeRenderer in preview mode with sample data.
  Merchants can browse themes and see how their content will look before activating.
--}}
<div class="rounded-xl border border-gray-200 bg-white overflow-hidden">
    <div class="flex items-center justify-between border-b border-gray-200 bg-gray-50 px-4 py-2">
        <p class="text-xs font-medium text-gray-600">Preview: {{ $theme->name }}</p>
        <span class="rounded-full bg-blue-100 px-2 py-0.5 text-[10px] font-medium text-blue-700">Preview mode</span>
    </div>
    <div class="aspect-[4/3] w-full bg-gray-100">
        <iframe src="{{ route('business.profile', $business) }}?theme_preview={{ $theme->id }}"
            class="h-full w-full" frameborder="0"></iframe>
    </div>
</div>
