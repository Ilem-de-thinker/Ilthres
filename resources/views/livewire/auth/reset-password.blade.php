<form wire:submit="resetPassword" class="space-y-6">
    <input type="hidden" wire:model="token">

    <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input wire:model="email" id="email" type="email" required autocomplete="email"
            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-dark-gold focus:ring-1 focus:ring-dark-gold">
        @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
        <input wire:model="password" id="password" type="password" required autocomplete="new-password"
            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-dark-gold focus:ring-1 focus:ring-dark-gold">
        @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="passwordConfirmation" class="block text-sm font-medium text-gray-700">Confirm password</label>
        <input wire:model="passwordConfirmation" id="passwordConfirmation" type="password" required autocomplete="new-password"
            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-dark-gold focus:ring-1 focus:ring-dark-gold">
    </div>

    <button type="submit"
        class="w-full rounded-lg bg-dark-gold px-4 py-2 text-sm font-medium text-white shadow-sm shadow-dark-gold/30 hover:bg-dark-gold-500">
        Reset password
    </button>
</form>
