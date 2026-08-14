<div>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Reviews</h2>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Manage customer reviews</p>
    </div>

    @if(session()->has('success'))
        <div class="mb-4 p-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-lg text-green-700 dark:text-green-400">
            {{ session('success') }}
        </div>
    @endif

    {{-- Filters --}}
    <div class="flex flex-wrap gap-3 mb-6">
        <select wire:model="ratingFilter" class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
            <option value="all">All Ratings</option>
            <option value="5">5 Stars</option>
            <option value="4">4 Stars</option>
            <option value="3">3 Stars</option>
            <option value="2">2 Stars</option>
            <option value="1">1 Star</option>
        </select>

        <input type="text" wire:model="searchQuery" class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-white flex-1 min-w-[200px]" placeholder="Search reviews..." />
    </div>

    {{-- Reviews List --}}
    <div class="space-y-3">
        @forelse($this->reviews as $review)
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <h3 class="font-semibold text-gray-900 dark:text-white">{{ $review->reviewer_name }}</h3>
                            <div class="flex text-yellow-400 text-sm">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $review->rating)
                                        &#9733;
                                    @else
                                        &#9734;
                                    @endif
                                @endfor
                            </div>
                            <span class="text-xs text-gray-500 dark:text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                        </div>

                        @if($review->comment)
                            <p class="text-gray-700 dark:text-gray-300 text-sm mt-1">{{ $review->comment }}</p>
                        @endif

                        @if($review->order)
                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">Order #{{ $review->order_id }}</p>
                        @endif

                        @if($review->booking)
                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">Booking #{{ $review->booking_id }}</p>
                        @endif

                        @if($review->seller_response)
                            <div class="mt-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Your Response:</p>
                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $review->seller_response }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="flex items-center gap-2 ml-4">
                        <button wire:click="viewReview({{ $review->id }})" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400">Respond</button>
                        <button wire:click="flagReview({{ $review->id }})" class="text-sm text-red-600 hover:text-red-800 dark:text-red-400">Flag</button>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                <p class="text-gray-500 dark:text-gray-400">No reviews yet.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $this->reviews->links() }}
    </div>

    {{-- Response Modal --}}
    @if($selectedReview)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-500/75 dark:bg-gray-900/75" wire:click="$set('selectedReview', null)"></div>
                <div class="relative bg-white dark:bg-gray-800 rounded-lg max-w-lg w-full p-6 shadow-xl">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Review Details</h3>

                    <div class="space-y-3 mb-4">
                        <div class="flex items-center gap-2">
                            <span class="font-medium text-gray-900 dark:text-white">{{ $selectedReview->reviewer_name }}</span>
                            <div class="flex text-yellow-400 text-sm">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $selectedReview->rating) &#9733; @else &#9734; @endif
                                @endfor
                            </div>
                        </div>
                        @if($selectedReview->comment)
                            <p class="text-gray-700 dark:text-gray-300">{{ $selectedReview->comment }}</p>
                        @endif
                    </div>

                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Your Response</label>
                        <textarea wire:model="responseText" rows="3" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-white mb-2" placeholder="Write your response..."></textarea>
                        @error('responseText') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex gap-3 mt-4">
                        <button wire:click="$set('selectedReview', null)" class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 text-sm">Close</button>
                        <button wire:click="respondToReview" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">Submit Response</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
