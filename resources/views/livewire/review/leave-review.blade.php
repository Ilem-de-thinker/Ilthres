<div class="max-w-lg mx-auto">
    @if($submitted)
        <div class="text-center py-12">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Thank You!</h3>
            <p class="text-gray-600 dark:text-gray-400">Your review has been submitted successfully.</p>
        </div>
    @else
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Leave a Review</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Share your experience with {{ $business->name }}</p>
        </div>

        @if(session()->has('error'))
            <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-lg text-red-700 dark:text-red-400">
                {{ session('error') }}
            </div>
        @endif

        <div class="space-y-4">
            {{-- Rating --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Rating *</label>
                <div class="flex gap-2" x-data="{ hover: 0 }">
                    @foreach([1, 2, 3, 4, 5] as $star)
                        <button
                            type="button"
                            wire:click="setRating({{ $star }})"
                            @mouseenter="hover = {{ $star }}"
                            @mouseleave="hover = 0"
                            class="text-3xl transition-colors
                                {{ $rating >= $star ? 'text-yellow-400' : (hover >= $star ? 'text-yellow-300' : 'text-gray-300 dark:text-gray-600') }}"
                        >
                            &#9733;
                        </button>
                    @endforeach
                </div>
                @error('rating')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Reviewer Name --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Your Name *</label>
                <input
                    type="text"
                    wire:model="reviewerName"
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-3 text-gray-900 dark:text-white bg-white dark:bg-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Your name"
                />
                @error('reviewerName')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Comment --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Review (optional)</label>
                <textarea
                    wire:model="comment"
                    rows="4"
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-3 text-gray-900 dark:text-white bg-white dark:bg-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Tell others about your experience..."
                ></textarea>
                @error('comment')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Submit --}}
            <button
                wire:click="submit"
                wire:loading.attr="disabled"
                class="w-full px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 font-medium"
            >
                <span wire:loading.remove wire:target="submit">Submit Review</span>
                <span wire:loading wire:target="submit">Submitting...</span>
            </button>
        </div>
    @endif
</div>
