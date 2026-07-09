<div class="rounded-lg border border-gray-200 bg-white p-5">
    <h3 class="text-sm font-semibold text-gray-900">Visitors over time</h3>
    <p class="mt-0.5 text-xs text-gray-500">Last {{ $days }} days</p>

    <div class="mt-4 flex items-end gap-1" style="height:120px">
        @foreach ($viewsByDay as $date => $count)
            @php
                $pct = $max > 0 ? ($count / $max) * 100 : 0;
                $day = \Carbon\Carbon::parse($date)->format('D');
            @endphp
            <div class="flex flex-1 flex-col items-center justify-end gap-1">
                <span class="text-[10px] font-medium {{ $count > 0 ? 'text-gray-700' : 'text-gray-300' }}">{{ $count }}</span>
                <div class="w-full rounded-t bg-gray-900 transition-all" style="height:{{ max(2, $pct) }}%"></div>
                <span class="text-[10px] text-gray-400">{{ $day }}</span>
            </div>
        @endforeach
    </div>
</div>
