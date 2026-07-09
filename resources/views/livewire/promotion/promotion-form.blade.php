<div>
    @if (! $hasBusiness)
        <div class="rounded-lg border border-dashed border-gray-300 bg-white p-12 text-center">
            <p class="text-sm text-gray-500">Create a business to create promotions.</p>
        </div>
        @php return; @endphp
    @endif

    <form wire:submit="save" class="space-y-6">
        {{-- Basic Info --}}
        <div class="rounded-xl border border-gray-200 bg-white p-6">
            <h2 class="text-lg font-semibold text-gray-900">{{ $editing ? 'Edit' : 'Create' }} Promotion</h2>
            <p class="mt-1 text-sm text-gray-500">Set up a promotion campaign for your products or services.</p>

            <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700">Name <span class="text-red-500">*</span></label>
                    <input wire:model="name" id="name" type="text" placeholder="e.g. Summer Sale"
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900 @error('name') border-red-300 @enderror">
                    @error('name') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea wire:model="description" id="description" rows="3" placeholder="Describe the promotion..."
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900"></textarea>
                </div>

                <div>
                    <label for="promotionType" class="block text-sm font-medium text-gray-700">Promotion Type <span class="text-red-500">*</span></label>
                    <select wire:model.live="promotionType" id="promotionType"
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                        <option value="percentage">Percentage Discount</option>
                        <option value="fixed_amount">Fixed Amount Discount</option>
                        <option value="fixed_price">Fixed Price</option>
                        <option value="buy_x_get_y">Buy X Get Y</option>
                        <option value="free_shipping">Free Shipping</option>
                        <option value="free_item">Free Item</option>
                    </select>
                    @error('promotionType') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="scope" class="block text-sm font-medium text-gray-700">Scope <span class="text-red-500">*</span></label>
                    <select wire:model.live="scope" id="scope"
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                        <option value="all">All Products & Services</option>
                        <option value="category">Specific Categories</option>
                        <option value="selected_products">Selected Products</option>
                        <option value="selected_services">Selected Services</option>
                    </select>
                </div>

                @if (in_array($promotionType, ['percentage', 'fixed_amount']))
                    <div>
                        <label for="discountType" class="block text-sm font-medium text-gray-700">Discount Type</label>
                        <select wire:model="discountType" id="discountType"
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                            <option value="percentage">Percentage</option>
                            <option value="fixed">Fixed Amount</option>
                        </select>
                    </div>

                    <div>
                        <label for="discountValue" class="block text-sm font-medium text-gray-700">
                            {{ $promotionType === 'percentage' ? 'Discount (%)' : 'Discount Amount (₦)' }} <span class="text-red-500">*</span>
                        </label>
                        <input wire:model="discountValue" id="discountValue" type="number" step="any" min="0"
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900 @error('discountValue') border-red-300 @enderror">
                        @error('discountValue') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                @endif

                @if ($promotionType === 'fixed_price')
                    <div>
                        <label for="fixedPrice" class="block text-sm font-medium text-gray-700">Fixed Price (₦) <span class="text-red-500">*</span></label>
                        <input wire:model="fixedPrice" id="fixedPrice" type="number" step="any" min="0"
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900 @error('fixedPrice') border-red-300 @enderror">
                        @error('fixedPrice') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                @endif

                @if ($promotionType === 'buy_x_get_y')
                    <div>
                        <label for="buyX" class="block text-sm font-medium text-gray-700">Buy</label>
                        <input wire:model="buyX" id="buyX" type="number" min="1"
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                    </div>
                    <div>
                        <label for="buyY" class="block text-sm font-medium text-gray-700">Get</label>
                        <input wire:model="buyY" id="buyY" type="number" min="1"
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                    </div>
                @endif

                @if ($promotionType === 'free_shipping')
                    <div>
                        <label for="minOrderAmount" class="block text-sm font-medium text-gray-700">Min. Order Amount (₦)</label>
                        <input wire:model="minOrderAmount" id="minOrderAmount" type="number" step="any" min="0"
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                    </div>
                @endif
            </div>
        </div>

        {{-- Schedule & Limits --}}
        <div class="rounded-xl border border-gray-200 bg-white p-6">
            <h3 class="text-base font-semibold text-gray-900">Schedule & Limits</h3>

            <div class="mt-4 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                <div>
                    <label for="startsAt" class="block text-sm font-medium text-gray-700">Starts At</label>
                    <input wire:model="startsAt" id="startsAt" type="datetime-local"
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                </div>

                <div>
                    <label for="endsAt" class="block text-sm font-medium text-gray-700">Ends At</label>
                    <input wire:model="endsAt" id="endsAt" type="datetime-local"
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                    @error('endsAt') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select wire:model="status" id="status"
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                        <option value="draft">Draft</option>
                        <option value="scheduled">Scheduled</option>
                        <option value="active">Active</option>
                        <option value="paused">Paused</option>
                    </select>
                </div>

                <div>
                    <label for="maxUses" class="block text-sm font-medium text-gray-700">Max Uses</label>
                    <input wire:model="maxUses" id="maxUses" type="number" min="1" placeholder="Unlimited"
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                </div>

                <div>
                    <label for="customerLimit" class="block text-sm font-medium text-gray-700">Per Customer</label>
                    <input wire:model="customerLimit" id="customerLimit" type="number" min="1" placeholder="Unlimited"
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                </div>

                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700">Priority</label>
                    <input wire:model="priority" id="priority" type="number" min="0"
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                    <p class="mt-1 text-xs text-gray-400">Higher priority wins when multiple promotions apply.</p>
                </div>
            </div>
        </div>

        {{-- Scope Selection --}}
        @if ($scope === 'category')
            <div class="rounded-xl border border-gray-200 bg-white p-6">
                <h3 class="text-base font-semibold text-gray-900">Categories</h3>
                <p class="mt-1 text-sm text-gray-500">Select categories to apply this promotion to.</p>

                <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                    @forelse ($categories as $category)
                        <label class="flex items-center gap-3 rounded-lg border border-gray-200 p-3 cursor-pointer hover:bg-gray-50 {{ in_array($category->id, $selectedCategoryIds) ? 'border-gray-900 bg-gray-50' : '' }}">
                            <input type="checkbox" wire:click="toggleCategory({{ $category->id }})" {{ in_array($category->id, $selectedCategoryIds) ? 'checked' : '' }}
                                class="h-4 w-4 rounded border-gray-300 text-gray-900 focus:ring-gray-900">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $category->name }}</p>
                                @if ($category->description)
                                    <p class="text-xs text-gray-500">{{ Str::limit($category->description, 60) }}</p>
                                @endif
                            </div>
                        </label>
                    @empty
                        <p class="col-span-full text-sm text-gray-400">No categories found.</p>
                    @endforelse
                </div>
                @error('selectedCategoryIds') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        @endif

        @if ($scope === 'selected_products')
            <div class="rounded-xl border border-gray-200 bg-white p-6">
                <h3 class="text-base font-semibold text-gray-900">Products</h3>
                <p class="mt-1 text-sm text-gray-500">Search and select products to apply this promotion to.</p>

                <div class="mt-4">
                    <input wire:model.live="productSearch" type="text" placeholder="Search products..."
                        class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                </div>

                <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                    @forelse ($products as $product)
                        <label class="flex items-center gap-3 rounded-lg border border-gray-200 p-3 cursor-pointer hover:bg-gray-50 {{ in_array($product->id, $selectedProductIds) ? 'border-gray-900 bg-gray-50' : '' }}">
                            <input type="checkbox" wire:click="toggleProduct({{ $product->id }})" {{ in_array($product->id, $selectedProductIds) ? 'checked' : '' }}
                                class="h-4 w-4 rounded border-gray-300 text-gray-900 focus:ring-gray-900">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $product->name }}</p>
                                <p class="text-xs text-gray-500">₦{{ number_format($product->price, 2) }}</p>
                            </div>
                        </label>
                    @empty
                        <p class="col-span-full text-sm text-gray-400">{{ $productSearch ? 'No products match your search.' : 'No products found.' }}</p>
                    @endforelse
                </div>
                @error('selectedProductIds') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        @endif

        @if ($scope === 'selected_services')
            <div class="rounded-xl border border-gray-200 bg-white p-6">
                <h3 class="text-base font-semibold text-gray-900">Services</h3>
                <p class="mt-1 text-sm text-gray-500">Search and select services to apply this promotion to.</p>

                <div class="mt-4">
                    <input wire:model.live="serviceSearch" type="text" placeholder="Search services..."
                        class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                </div>

                <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                    @forelse ($services as $service)
                        <label class="flex items-center gap-3 rounded-lg border border-gray-200 p-3 cursor-pointer hover:bg-gray-50 {{ in_array($service->id, $selectedServiceIds) ? 'border-gray-900 bg-gray-50' : '' }}">
                            <input type="checkbox" wire:click="toggleService({{ $service->id }})" {{ in_array($service->id, $selectedServiceIds) ? 'checked' : '' }}
                                class="h-4 w-4 rounded border-gray-300 text-gray-900 focus:ring-gray-900">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $service->name }}</p>
                                <p class="text-xs text-gray-500">₦{{ number_format($service->price, 2) }}</p>
                            </div>
                        </label>
                    @empty
                        <p class="col-span-full text-sm text-gray-400">{{ $serviceSearch ? 'No services match your search.' : 'No services found.' }}</p>
                    @endforelse
                </div>
                @error('selectedServiceIds') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        @endif

        {{-- Badge & Display Settings --}}
        <div class="rounded-xl border border-gray-200 bg-white p-6">
            <h3 class="text-base font-semibold text-gray-900">Display Settings</h3>

            <div class="mt-4 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
                <div>
                    <label for="badgeColor" class="block text-sm font-medium text-gray-700">Badge Color</label>
                    <select wire:model="badgeConfig.color" id="badgeColor"
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                        <option value="red">Red</option>
                        <option value="orange">Orange</option>
                        <option value="green">Green</option>
                        <option value="purple">Purple</option>
                        <option value="blue">Blue</option>
                        <option value="black">Black</option>
                    </select>
                </div>

                <div>
                    <label for="badgePosition" class="block text-sm font-medium text-gray-700">Badge Position</label>
                    <select wire:model="badgeConfig.position" id="badgePosition"
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                        <option value="top-left">Top Left</option>
                        <option value="top-right">Top Right</option>
                        <option value="bottom-left">Bottom Left</option>
                        <option value="bottom-right">Bottom Right</option>
                    </select>
                </div>

                <div>
                    <label for="customText" class="block text-sm font-medium text-gray-700">Custom Badge Text</label>
                    <input wire:model="badgeConfig.custom_text" id="customText" type="text" placeholder="Auto-generated"
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Options</label>
                    <div class="mt-2 space-y-2">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" wire:model="showBadge" class="h-4 w-4 rounded border-gray-300 text-gray-900 focus:ring-gray-900">
                            <span class="text-sm text-gray-700">Show Badge</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" wire:model="showCountdown" class="h-4 w-4 rounded border-gray-300 text-gray-900 focus:ring-gray-900">
                            <span class="text-sm text-gray-700">Show Countdown</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" wire:model="featured" class="h-4 w-4 rounded border-gray-300 text-gray-900 focus:ring-gray-900">
                            <span class="text-sm text-gray-700">Featured Promotion</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('dashboard.promotions') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">Cancel</a>
            <button type="submit" class="rounded-lg bg-gray-900 px-8 py-2.5 text-sm font-semibold text-white hover:bg-gray-800">
                {{ $editing ? 'Update' : 'Create' }} Promotion
            </button>
        </div>
    </form>
</div>
