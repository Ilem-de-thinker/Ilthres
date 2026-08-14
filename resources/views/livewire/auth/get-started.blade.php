<div class="min-h-[70vh] flex items-center justify-center px-4">
    <div class="w-full max-w-2xl">
        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Welcome to Ilthres</h1>
            <p class="mt-2 text-base text-gray-500">How would you like to get started?</p>
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
            {{-- Link existing business --}}
            <button wire:click="linkBusiness"
                class="group relative rounded-2xl border-2 border-gray-200 bg-white p-8 text-left transition hover:border-dark-gold hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-dark-gold focus:ring-offset-2">
                <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-amber-50 text-dark-gold transition group-hover:bg-dark-gold group-hover:text-white">
                    <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m9.86-2.416a4.5 4.5 0 00-6.364 0l-4.5 4.5a4.5 4.5 0 001.242 7.244" />
                    </svg>
                </div>
                <h2 class="mt-4 text-lg font-semibold text-gray-900">Link my business</h2>
                <p class="mt-2 text-sm text-gray-500 leading-relaxed">
                    I already have a business on Ilthres. Connect it to my account to manage it from my dashboard.
                </p>
                <span class="mt-4 inline-flex items-center gap-1 text-sm font-medium text-dark-gold">
                    Get started
                    <svg class="h-4 w-4 transition group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                </span>
            </button>

            {{-- Create new business --}}
            <button wire:click="createBusiness"
                class="group relative rounded-2xl border-2 border-gray-200 bg-white p-8 text-left transition hover:border-dark-gold hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-dark-gold focus:ring-offset-2">
                <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-blue-50 text-blue-600 transition group-hover:bg-blue-600 group-hover:text-white">
                    <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                </div>
                <h2 class="mt-4 text-lg font-semibold text-gray-900">Create a business</h2>
                <p class="mt-2 text-sm text-gray-500 leading-relaxed">
                    Start fresh. Set up a new business profile and launch your online presence in minutes.
                </p>
                <span class="mt-4 inline-flex items-center gap-1 text-sm font-medium text-dark-gold">
                    Get started
                    <svg class="h-4 w-4 transition group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                </span>
            </button>
        </div>

        <p class="mt-8 text-center text-xs text-gray-400">
            You can always create or link additional businesses later from your dashboard.
        </p>
    </div>
</div>
