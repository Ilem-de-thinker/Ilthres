<form wire:submit="register" class="space-y-6">
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Full name</label>
        <input wire:model="name" id="name" type="text" required autocomplete="name"
            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-dark-gold focus:ring-1 focus:ring-dark-gold">
        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

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

    <div>
        <label class="flex items-start gap-2 text-sm text-gray-600">
            <input wire:model="agree" type="checkbox"
                class="mt-0.5 rounded border-gray-300 text-dark-gold focus:ring-dark-gold">
            <span>I agree to the
                <a href="{{ route('terms') }}" target="_blank" class="font-medium text-dark-gold hover:text-dark-gold-500">Terms & Conditions</a>
                and
                <a href="{{ route('privacy') }}" target="_blank" class="font-medium text-dark-gold hover:text-dark-gold-500">Privacy Policy</a>
            </span>
        </label>
        @error('agree') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <button type="submit"
        class="w-full rounded-lg bg-dark-gold px-4 py-2 text-sm font-medium text-white shadow-sm shadow-dark-gold/30 hover:bg-dark-gold-500">
        Create account
    </button>
</form>
