<form wire:submit="login" class="space-y-6">
    <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input wire:model="email" id="email" type="email" required
            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-dark-gold focus:ring-1 focus:ring-dark-gold">
        @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
        <input wire:model="password" id="password" type="password" required
            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-dark-gold focus:ring-1 focus:ring-dark-gold">
        @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="flex items-center justify-between">
        <label class="flex items-center gap-2 text-sm text-gray-600">
            <input wire:model="remember" type="checkbox" class="rounded border-gray-300 text-dark-gold focus:ring-dark-gold">
            Remember me
        </label>
        <a href="{{ route('password.request') }}" class="text-sm text-gray-600 hover:text-dark-gold">Forgot password?</a>
    </div>

    <button type="submit"
        class="w-full rounded-lg bg-dark-gold px-4 py-2 text-sm font-medium text-white shadow-sm shadow-dark-gold/30 hover:bg-dark-gold-500">
        Sign in
    </button>
</form>
