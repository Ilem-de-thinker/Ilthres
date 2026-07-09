{{--
  Notification preferences form.
--}}
<div>
    <h1 class="mb-6 text-2xl font-bold text-gray-900">Notification Preferences</h1>

    <form wire:submit="save" class="max-w-lg space-y-6">
        <fieldset class="space-y-4">
            <legend class="text-sm font-medium text-gray-700">Email notifications</legend>

            <label class="flex items-start gap-3">
                <input type="checkbox" wire:model="emailReviews" class="mt-0.5 rounded border-gray-300 text-gray-900">
                <div>
                    <p class="text-sm font-medium text-gray-900">Reviews</p>
                    <p class="text-xs text-gray-500">When someone leaves a review on your business.</p>
                </div>
            </label>

            <label class="flex items-start gap-3">
                <input type="checkbox" wire:model="emailSubscription" class="mt-0.5 rounded border-gray-300 text-gray-900">
                <div>
                    <p class="text-sm font-medium text-gray-900">Subscription</p>
                    <p class="text-xs text-gray-500">Subscription expiry and renewal reminders.</p>
                </div>
            </label>

            <label class="flex items-start gap-3">
                <input type="checkbox" wire:model="emailMarketing" class="mt-0.5 rounded border-gray-300 text-gray-900">
                <div>
                    <p class="text-sm font-medium text-gray-900">Marketing</p>
                    <p class="text-xs text-gray-500">Tips, promotions, and product updates.</p>
                </div>
            </label>
        </fieldset>

        @if (session('saved'))
            <p class="text-sm text-green-600">Preferences saved.</p>
        @endif

        <button type="submit" class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800">Save</button>
    </form>
</div>
