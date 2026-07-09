<div>
    @if (! $hasBusiness)
        <div class="rounded-lg border border-dashed border-gray-300 bg-white p-12 text-center">
            <p class="text-sm text-gray-500">Create a business to build lead forms.</p>
        </div>
        @php return; @endphp
    @endif

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <div class="rounded-xl border border-gray-200 bg-white p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Lead Forms</h2>
                        <p class="text-sm text-gray-500">Select a form to edit or create a new one.</p>
                    </div>
                    <select wire:model.live="editId" class="rounded-lg border border-gray-300 px-3 py-1.5 text-sm">
                        <option value="">— New form —</option>
                        @foreach ($existingForms as $form)
                            <option value="{{ $form->id }}">{{ $form->name }}</option>
                        @endforeach
                    </select>
                </div>

                @if ($existingForms->isEmpty() && ! $editId)
                    <p class="mt-6 text-center text-sm text-gray-400">No forms yet. Fill in the form builder to create one.</p>
                @endif
            </div>
        </div>

        <div>
            <div class="rounded-xl border border-gray-200 bg-white p-6">
                <h2 class="text-sm font-semibold text-gray-900">Form Details</h2>
                <form wire:submit="save" class="mt-4 space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700">Name</label>
                        <input wire:model="name" type="text" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm @error('name') border-red-300 @enderror">
                        @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700">Description</label>
                        <textarea wire:model="description" rows="2" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700">Template</label>
                        <select wire:model="template" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                            <option value="contact_us">Contact Us</option>
                            <option value="get_a_quote">Get a Quote</option>
                            <option value="book_a_call">Book a Call</option>
                            <option value="custom">Custom</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <input wire:model="isActive" type="checkbox" id="isActive" class="rounded border-gray-300">
                        <label for="isActive" class="text-xs font-medium text-gray-700">Active</label>
                    </div>

                    <hr class="border-gray-200">

                    <div>
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-medium text-gray-700">Fields</label>
                            <button type="button" wire:click="addField" class="text-xs font-medium text-gray-600 hover:text-gray-900">+ Add Field</button>
                        </div>
                        @error('fields') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror

                        <div class="mt-2 space-y-2">
                            @foreach ($fields as $i => $field)
                                <div class="rounded-lg border border-gray-200 p-3">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-medium text-gray-500">Field #{{ $i + 1 }}</span>
                                        <button type="button" wire:click="removeField({{ $i }})" class="text-xs text-red-600 hover:text-red-700">Remove</button>
                                    </div>
                                    <div class="mt-2 grid grid-cols-2 gap-2">
                                        <div>
                                            <input wire:model="fields.{{ $i }}.label" type="text" placeholder="Label" class="block w-full rounded-lg border border-gray-300 px-2 py-1 text-xs @error('fields.{{ $i }}.label') border-red-300 @enderror">
                                        </div>
                                        <div>
                                            <select wire:model="fields.{{ $i }}.type" class="block w-full rounded-lg border border-gray-300 px-2 py-1 text-xs">
                                                <option value="text">Text</option>
                                                <option value="textarea">Textarea</option>
                                                <option value="email">Email</option>
                                                <option value="phone">Phone</option>
                                                <option value="select">Select</option>
                                                <option value="checkbox">Checkbox</option>
                                                <option value="radio">Radio</option>
                                                <option value="file">File</option>
                                                <option value="date">Date</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mt-1 flex items-center gap-2">
                                        <input wire:model="fields.{{ $i }}.required" type="checkbox" id="req_{{ $i }}" class="rounded border-gray-300">
                                        <label for="req_{{ $i }}" class="text-xs text-gray-500">Required</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <button type="submit" class="w-full rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800">{{ $editId ? 'Update' : 'Create' }} Form</button>
                </form>
            </div>
        </div>
    </div>
</div>
