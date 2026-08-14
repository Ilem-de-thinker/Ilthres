<div>
    {{-- Rating Distribution --}}
    @php
        $distribution = $business->ratingDistribution;
        $total = array_sum($distribution);
    @endphp

    @if($total > 0)
        <div class="mb-6">
            <div class="flex items-center gap-4 mb-4">
                <div class="text-center">
                    <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($business->average_rating, 1) }}</div>
                    <div class="flex text-yellow-400 text-lg">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= round($business->average_rating)) &#9733; @else &#9734; @endif
                        @endfor
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $total }} review{{ $total !== 1 ? 's' : '' }}</div>
                </div>
                <div class="flex-1 space-y-1">
                    @for($i = 5; $i >= 1; $i--)
                        @php $count = $distribution[$i] ?? 0; @endphp
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-gray-600 dark:text-gray-400 w-12">{{ $i }} star{{ $i !== 1 ? 's' : '' }}</span>
                            <div class="flex-1 h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                <div class="h-full bg-yellow-400 rounded-full" style="width: {{ $total > 0 ? ($count / $total) * 100 : 0 }}%"></div>
                            </div>
                            <span class="text-sm text-gray-500 dark:text-gray-500 w-8 text-right">{{ $count }}</span>
                        </div>
                    @endfor
                </div>
            </div>

            {{-- Filter Buttons --}}
            <div class="flex gap-2 mb-4">
                @for($i = 5; $i >= 1; $i--)
                    <button
                        wire:click="filterByRating({{ $i }})"
                        class="px-3 py-1 text-sm rounded-full border {{ $ratingFilter === $i ? 'bg-blue-600 text-white border-blue-600' : 'border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
                    >
                        {{ $i }} &#9733;
                    </button>
                @endfor
            </div>
        </div>
    @endif

    {{-- Reviews List --}}
    <div class="space-y-4">
        @forelse($this->reviews as $review)
            <div class="border-b border-gray-200 dark:border-gray-700 pb-4 last:border-b-0">
                <div class="flex items-center gap-2 mb-1">
                    <span class="font-medium text-gray-900 dark:text-white">{{ $review->reviewer_name }}</span>
                    <div class="flex text-yellow-400 text-sm">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $review->rating) &#9733; @else &#9734; @endif
                        @endfor
                    </div>
                    <span class="text-xs text-gray-500 dark:text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                </div>

                @if($review->comment)
                    <p class="text-gray-700 dark:text-gray-300 text-sm">{{ $review->comment }}</p>
                @endif

                @if($review->seller_response)
                    <div class="mt-2 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Reply from {{ $business->name }}:</p>
                        <p class="text-sm text-gray-700 dark:text-gray-300">{{ $review->seller_response }}</p>
                    </div>
                @endif
            </div>
        @empty
            <p class="text-gray-500 dark:text-gray-400 text-center py-8">No reviews yet. Be the first to leave one!</p>
        @endforelse
    </div>
</div>
