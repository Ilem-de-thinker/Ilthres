@props([
    'promotion',
    'size' => 'md',
])

@php
    $colorMap = [
        'red' => 'bg-red-500 text-white',
        'orange' => 'bg-orange-500 text-white',
        'green' => 'bg-green-500 text-white',
        'purple' => 'bg-purple-500 text-white',
        'blue' => 'bg-blue-500 text-white',
        'black' => 'bg-gray-900 text-white',
    ];

    $positionMap = [
        'top-left' => 'top-2 left-2',
        'top-right' => 'top-2 right-2',
        'bottom-left' => 'bottom-2 left-2',
        'bottom-right' => 'bottom-2 right-2',
    ];

    $sizeMap = [
        'sm' => 'px-1.5 py-0.5 text-[10px]',
        'md' => 'px-2 py-1 text-xs',
        'lg' => 'px-3 py-1.5 text-sm',
    ];

    $color = $colorMap[$promotion->badge_color] ?? 'bg-red-500 text-white';
    $position = $positionMap[$promotion->badge_position] ?? 'top-2 left-2';
    $sizeClass = $sizeMap[$size] ?? 'px-2 py-1 text-xs';
    $label = $promotion->badge_label;

    $endsToday = $promotion->ends_at && $promotion->ends_at->isToday();
@endphp

<div class="absolute {{ $position }} z-10 flex flex-col items-start gap-1">
    <span class="inline-flex items-center rounded-md font-bold uppercase leading-none shadow-sm {{ $color }} {{ $sizeClass }}">
        {{ $label }}
        @if ($endsToday)
            <span class="ml-1.5 inline-flex h-1.5 w-1.5 rounded-full bg-white animate-pulse"></span>
        @endif
    </span>
    @if ($promotion->show_countdown && $promotion->ends_at && $promotion->daysRemaining() > 0)
        <span class="inline-flex items-center rounded bg-black/70 px-1.5 py-0.5 text-[10px] font-medium text-white">
            {{ $promotion->daysRemaining() }}d left
        </span>
    @endif
</div>
