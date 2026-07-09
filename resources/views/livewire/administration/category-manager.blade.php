<div>
    <div class="mb-6 flex items-start justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Categories</h1>
            <p class="mt-1 text-sm text-gray-500">Manage business categories across the platform.</p>
        </div>
        <button wire:click="$set('showForm', true)" class="rounded-lg bg-dark-gold px-4 py-2 text-sm font-medium text-white hover:bg-dark-gold-500">
            Add category
        </button>
    </div>

    @if ($showForm)
        <form wire:submit="{{ $editingCategoryId ? 'update' : 'create' }}" class="mb-6 rounded-lg border border-gray-200 bg-white p-6">
            <h2 class="text-lg font-semibold text-gray-900">{{ $editingCategoryId ? 'Edit category' : 'New category' }}</h2>
            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input wire:model="name" id="name" type="text" required
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-dark-gold focus:ring-1 focus:ring-dark-gold">
                    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="icon" class="block text-sm font-medium text-gray-700">Icon (emoji)</label>
                    <input wire:model="icon" id="icon" type="text" placeholder="👗"
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-dark-gold focus:ring-1 focus:ring-dark-gold">
                    @error('icon') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
            <div class="mt-4 flex items-center gap-3">
                <button type="submit" class="rounded-lg bg-dark-gold px-4 py-2 text-sm font-medium text-white hover:bg-dark-gold-500">
                    {{ $editingCategoryId ? 'Update' : 'Create' }}
                </button>
                <button type="button" wire:click="cancel" class="text-sm text-gray-600 hover:text-gray-900">Cancel</button>
            </div>
        </form>
    @endif

    <div class="overflow-x-auto rounded-lg border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Icon</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Slug</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Order</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Active</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @forelse ($categories as $category)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-900 text-lg">{{ $category->icon }}</td>
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $category->name }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $category->slug }}</td>
                        <td class="px-4 py-3 text-center text-sm text-gray-500">{{ $category->sort_order }}</td>
                        <td class="px-4 py-3 text-center">
                            @if ($category->is_active)
                                <span class="inline-flex rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-700">Yes</span>
                            @else
                                <span class="inline-flex rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-700">No</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button wire:click="edit({{ $category->id }})"
                                    class="rounded px-3 py-1 text-xs font-medium border border-gray-200 bg-white text-gray-700 hover:bg-gray-50">
                                    Edit
                                </button>
                                <button wire:click="toggleActive({{ $category->id }})"
                                    class="rounded px-3 py-1 text-xs font-medium border {{ $category->is_active ? 'border-red-200 bg-red-50 text-red-700 hover:bg-red-100' : 'border-green-200 bg-green-50 text-green-700 hover:bg-green-100' }}">
                                    {{ $category->is_active ? 'Disable' : 'Enable' }}
                                </button>
                                <button wire:click="delete({{ $category->id }})"
                                    wire:confirm="Delete this category?"
                                    class="rounded px-3 py-1 text-xs font-medium border border-red-200 bg-red-50 text-red-700 hover:bg-red-100">
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-sm text-gray-500">No categories found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $categories->links() }}
    </div>
</div>
