{{--
  Upload media form — inline upload with business selector,
  file preview, and collection tag.
--}}
<div>
    {{-- Business selector. --}}
    <div class="mb-4">
        <label for="upload-biz-select" class="block text-xs font-medium text-gray-600">Business</label>
        <select wire:model.live="selectedBusinessId" id="upload-biz-select"
            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-xs shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
            <option value="">Choose a business</option>
            @foreach ($businesses as $biz)
                <option value="{{ $biz->id }}">{{ $biz->name }}</option>
            @endforeach
        </select>
    </div>

    @if (! $business)
        <p class="text-xs text-gray-500">Select a business to upload files.</p>
        @php return; @endphp
    @endif

    @if ($uploadError)
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700">{{ $uploadError }}</div>
    @endif

    <div class="rounded-lg border-2 border-dashed border-gray-300 bg-white p-6">
        @if ($file)
            <div class="flex items-center gap-4">
                @if (str_starts_with($file->getMimeType(), 'image/'))
                    <img src="{{ $file->temporaryUrl() }}" alt="Preview" class="h-20 w-20 rounded-lg object-cover">
                @else
                    <div class="flex h-20 w-20 items-center justify-center rounded-lg bg-gray-100 text-2xl text-gray-400">📄</div>
                @endif
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ $file->getClientOriginalName() }}</p>
                    <p class="text-xs text-gray-500">{{ number_format($file->getSize() / 1024, 1) }} KB</p>
                </div>
                <button wire:click="clearFile" type="button" class="text-sm text-red-600 hover:text-red-800">Remove</button>
            </div>

            <div class="mt-4">
                <label for="collection" class="block text-xs font-medium text-gray-600">Collection (optional)</label>
                <input wire:model="collection" id="collection" type="text" placeholder="e.g. gallery, logos, documents"
                    class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-xs shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
            </div>

            <div class="mt-4 flex justify-end">
                <button wire:click="handleUpload" wire:loading.attr="disabled"
                    class="rounded-lg bg-gray-900 px-6 py-2 text-sm font-semibold text-white hover:bg-gray-800 disabled:opacity-50">
                    Upload
                </button>
            </div>
        @else
            <label class="flex cursor-pointer flex-col items-center justify-center py-8">
                <svg class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
                <p class="mt-3 text-sm text-gray-600"><span class="font-medium text-gray-900">Click to upload</span> or drag and drop</p>
                <p class="mt-1 text-xs text-gray-400">JPEG, PNG, WebP, GIF, PDF, CSV — up to 10MB</p>
                <input wire:model="file" type="file" accept="image/jpeg,image/png,image/webp,image/gif,application/pdf,text/csv" class="sr-only">
            </label>
        @endif
    </div>
    @error('file') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
</div>
