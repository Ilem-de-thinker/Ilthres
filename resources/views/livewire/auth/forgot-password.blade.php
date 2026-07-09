<form wire:submit="sendResetLink" class="space-y-6">
    <p class="text-sm text-gray-600">Forgot your password? No problem. Just let us know your email address and we will email you a password reset link.</p>

    @if ($status)
        <p class="text-sm text-green-600">{{ $status }}</p>
    @endif

    <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input wire:model="email" id="email" type="email" required autocomplete="email"
            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-dark-gold focus:ring-1 focus:ring-dark-gold">
        @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <button type="submit"
        class="w-full rounded-lg bg-dark-gold px-4 py-2 text-sm font-medium text-white shadow-sm shadow-dark-gold/30 hover:bg-dark-gold-500">
        Email password reset link
    </button>
</form>
