<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold tracking-tight text-gray-900">AI Settings</h1>
        <p class="mt-1 text-sm text-gray-500">Configure the AI provider and model used for AI-powered features (search, descriptions, SEO, etc.).</p>
    </div>

    <div class="rounded-lg border border-gray-200 bg-white p-6">
        <form wire:submit="save" class="space-y-6">
            {{-- Provider --}}
            <div>
                <label for="provider" class="block text-sm font-medium text-gray-900">AI Provider</label>
                <p class="mt-0.5 text-xs text-gray-500">Changes take effect immediately. No restart required.</p>
                <select wire:model.live="provider" id="provider"
                    class="mt-2 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                    @foreach ($availableProviders as $key => $name)
                        <option value="{{ $key }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Model --}}
            <div>
                <label for="model" class="block text-sm font-medium text-gray-900">Model</label>
                <p class="mt-0.5 text-xs text-gray-500">The model identifier (e.g. <code class="rounded bg-gray-100 px-1 py-0.5 text-[10px]">deepseek-chat</code>, <code class="rounded bg-gray-100 px-1 py-0.5 text-[10px]">gpt-4o-mini</code>).</p>
                <input wire:model="model" type="text" id="model"
                    class="mt-2 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900"
                    placeholder="e.g. deepseek-chat">
                @error('model')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- API Key --}}
            <div>
                <label for="apiKey" class="block text-sm font-medium text-gray-900">API Key</label>
                <p class="mt-0.5 text-xs text-gray-500">Stored in the database. Will also be written to <code class="rounded bg-gray-100 px-1 py-0.5 text-[10px]">config/prism.php</code> at runtime.</p>
                <input wire:model="apiKey" type="password" id="apiKey"
                    class="mt-2 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900"
                    placeholder="sk-...">
                @error('apiKey')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Timeout --}}
            <div>
                <label for="timeout" class="block text-sm font-medium text-gray-900">Request Timeout (seconds)</label>
                <p class="mt-0.5 text-xs text-gray-500">How long to wait for AI responses before timing out.</p>
                <input wire:model="timeout" type="number" id="timeout" min="5" max="300"
                    class="mt-2 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                @error('timeout')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Buttons --}}
            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                    class="rounded-lg bg-gray-900 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-gray-800 transition">
                    Save settings
                </button>
                <button type="button" wire:click="testConnection" wire:loading.attr="disabled"
                    class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 transition">
                    <span wire:loading.remove wire:target="testConnection">Test connection</span>
                    <span wire:loading wire:target="testConnection">Testing...</span>
                </button>
            </div>
        </form>
    </div>

    {{-- Provider reference --}}
    <div class="mt-6 rounded-lg border border-gray-200 bg-white p-6">
        <h2 class="text-sm font-semibold text-gray-900">Provider Reference</h2>
        <p class="mt-1 text-xs text-gray-500">Set the corresponding environment variable in your <code class="rounded bg-gray-100 px-1 py-0.5">.env</code> file for the API key to take effect at the system level. The API key above is used at runtime.</p>

        <div class="mt-4 overflow-hidden rounded-lg border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200 text-xs">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left font-medium text-gray-500">Provider</th>
                        <th class="px-4 py-2 text-left font-medium text-gray-500">Env Variable</th>
                        <th class="px-4 py-2 text-left font-medium text-gray-500">Default Model</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($availableProviders as $key => $name)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 text-gray-900">{{ $name }}</td>
                            <td class="px-4 py-2 font-mono text-gray-500">{{ strtoupper($key) }}_API_KEY</td>
                            <td class="px-4 py-2 font-mono text-gray-500">{{ $defaultModels[$key] ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
