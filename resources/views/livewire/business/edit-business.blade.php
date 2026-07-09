<div>
    <div class="mb-8 rounded-lg border border-gray-200 bg-gray-50 p-4">
        <label for="selectedBusinessId" class="block text-sm font-medium text-gray-700">Select business</label>
        <select wire:model="selectedBusinessId" id="selectedBusinessId"
            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
            <option value="">Choose a business to edit</option>
            @foreach ($businesses as $biz)
                <option value="{{ $biz->id }}">{{ $biz->name }} ({{ $biz->isProductStore() ? 'Store' : 'Service' }})</option>
            @endforeach
        </select>
    </div>

    @if (! $business)
        <div class="rounded-lg border border-dashed border-gray-300 bg-white p-12 text-center">
            <p class="text-sm text-gray-500">Select a business above to edit its details.</p>
        </div>
        @php return; @endphp
    @endif

    @if ($successMessage)
        <div class="mb-6 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-700 flex items-center gap-3">
            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ $successMessage }}</span>
            <button wire:click="$set('successMessage', null)" class="ml-auto">&times;</button>
        </div>
    @endif
    @if ($errorMessage)
        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">{{ $errorMessage }}</div>
    @endif

    <form wire:submit="save" class="space-y-8">

        {{-- ═══ Section: Business details ═══ --}}
        <div class="rounded-xl border border-gray-200 bg-white p-6">
            <h2 class="text-lg font-semibold text-gray-900">Business details</h2>
            <p class="mt-1 text-sm text-gray-500">Basic information about your business.</p>

            <div class="mt-6 space-y-5">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Business name <span class="text-red-500">*</span></label>
                    <input wire:model="name" id="name" type="text" required
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900
                        @error('name') border-red-300 @enderror">
                    @error('name') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Short description</label>
                    <textarea wire:model="description" id="description" rows="2"
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900"></textarea>
                </div>

                <div>
                    <label for="about" class="block text-sm font-medium text-gray-700">About us</label>
                    <p class="text-xs text-gray-500">Detailed description of your business, history, team, etc.</p>
                    <textarea wire:model="about" id="about" rows="6"
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900"></textarea>
                    @error('about') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="categoryId" class="block text-sm font-medium text-gray-700">Category</label>
                    <select wire:model="categoryId" id="categoryId"
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                        <option value="">Select a category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center gap-3 rounded-lg border border-gray-200 bg-gray-50 p-4">
                    <input wire:model="isDiscoverable" id="isDiscoverable" type="checkbox"
                        class="rounded border-gray-300 text-gray-900 focus:ring-gray-900">
                    <div>
                        <label for="isDiscoverable" class="text-sm font-medium text-gray-900">Discoverable in Explore</label>
                        <p class="text-xs text-gray-500">Allow your business to appear in the public Explore directory and search results.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══ Section: Contact & address ═══ --}}
        <div class="rounded-xl border border-gray-200 bg-white p-6">
            <h2 class="text-lg font-semibold text-gray-900">Contact & address</h2>
            <p class="mt-1 text-sm text-gray-500">How customers reach you.</p>

            <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input wire:model="email" id="email" type="email"
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                    @error('email') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                    <input wire:model="phone" id="phone" type="tel"
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                    @error('phone') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="whatsapp" class="block text-sm font-medium text-gray-700">WhatsApp</label>
                    <input wire:model="whatsapp" id="whatsapp" type="tel"
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                    @error('whatsapp') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="address" class="block text-sm font-medium text-gray-700">Street address</label>
                    <input wire:model="address" id="address" type="text"
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                    @error('address') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                    <input wire:model="city" id="city" type="text"
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                    @error('city') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="state" class="block text-sm font-medium text-gray-700">State</label>
                    <input wire:model="state" id="state" type="text"
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                    @error('state') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- ═══ Section: Location & map ═══ --}}
        <div class="rounded-xl border border-gray-200 bg-white p-6"
             x-data="{
                 map: null,
                 marker: null,
                 searchResults: [],
                 searching: false,
                 searchError: '',
                 initMap() {
                     const container = this.$refs.mapContainer;
                     if (!container) return;

                     // Default center of Nigeria (Abuja area) if no coordinates are set
                     const lat = parseFloat(this.$wire.latitude) || 9.0820;
                     const lng = parseFloat(this.$wire.longitude) || 8.6753;
                     const defaultZoom = (this.$wire.latitude && this.$wire.longitude) ? 13 : 6;

                     this.map = L.map(container).setView([lat, lng], defaultZoom);

                     setTimeout(() => {
                         this.map.invalidateSize();
                     }, 200);

                     L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                         attribution: '&copy; OpenStreetMap contributors'
                     }).addTo(this.map);

                     this.marker = L.marker([lat, lng], { draggable: true }).addTo(this.map);

                     this.marker.on('dragend', () => {
                         this.$wire.latitude = this.marker.getLatLng().lat;
                         this.$wire.longitude = this.marker.getLatLng().lng;
                     });

                     this.map.on('click', (e) => {
                         this.marker.setLatLng(e.latlng);
                         this.$wire.latitude = e.latlng.lat;
                         this.$wire.longitude = e.latlng.lng;
                     });

                     // Watchers for coordinate inputs
                     this.$watch('$wire.latitude', (value) => {
                         const latVal = parseFloat(value);
                         if (!isNaN(latVal) && this.marker) {
                             const currentLatLng = this.marker.getLatLng();
                             if (Math.abs(currentLatLng.lat - latVal) > 0.00001) {
                                 this.marker.setLatLng([latVal, currentLatLng.lng]);
                                 this.map.panTo([latVal, currentLatLng.lng]);
                             }
                         }
                     });

                     this.$watch('$wire.longitude', (value) => {
                         const lngVal = parseFloat(value);
                         if (!isNaN(lngVal) && this.marker) {
                             const currentLatLng = this.marker.getLatLng();
                             if (Math.abs(currentLatLng.lng - lngVal) > 0.00001) {
                                 this.marker.setLatLng([currentLatLng.lat, lngVal]);
                                 this.map.panTo([currentLatLng.lat, lngVal]);
                             }
                         }
                     });

                     this.$watch('$wire.selectedBusinessId', () => {
                         setTimeout(() => {
                             const newLat = parseFloat(this.$wire.latitude) || 9.0820;
                             const newLng = parseFloat(this.$wire.longitude) || 8.6753;
                             const zoom = (this.$wire.latitude && this.$wire.longitude) ? 13 : 6;
                             if (this.marker) {
                                 this.marker.setLatLng([newLat, newLng]);
                                 this.map.setView([newLat, newLng], zoom);
                             }
                         }, 100);
                     });
                 },
                 searchLocation() {
                     const query = this.$refs.searchInput.value.trim();
                     if (!query) return;

                     this.searching = true;
                     this.searchError = '';
                     this.searchResults = [];

                     const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&countrycodes=ng&limit=5`;

                     fetch(url, {
                         headers: {
                             'Accept-Language': 'en'
                         }
                     })
                     .then(res => res.json())
                     .then(data => {
                         this.searching = false;
                         if (data && data.length > 0) {
                             this.searchResults = data;
                         } else {
                             this.searchError = 'No locations found in Nigeria. Please check spelling or try a broader search.';
                         }
                     })
                     .catch(err => {
                         this.searching = false;
                         this.searchError = 'Search service is currently offline. Please try again later.';
                         console.error(err);
                     });
                 },
                 selectResult(result) {
                     const lat = parseFloat(result.lat);
                     const lng = parseFloat(result.lon);

                     this.marker.setLatLng([lat, lng]);
                     this.map.setView([lat, lng], 15);

                     this.$wire.latitude = lat;
                     this.$wire.longitude = lng;

                     this.searchResults = [];
                     this.$refs.searchInput.value = result.display_name;
                 }
             }"
             x-init="initMap()">
            <h2 class="text-lg font-semibold text-gray-900">Location & map</h2>
            <p class="mt-1 text-sm text-gray-500">Pin your business location on the map so customers can find you.</p>

            <!-- Location Search in Nigeria -->
            <div class="mt-6">
                <label for="map-search" class="block text-sm font-medium text-gray-700">Search location / address in Nigeria</label>
                <div class="mt-1 flex gap-2">
                    <input x-ref="searchInput" type="text" id="map-search" placeholder="Enter street address, city, or area in Nigeria..." 
                        class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900"
                        @keydown.enter.prevent="searchLocation()">
                    <button type="button" @click="searchLocation()"
                        class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800 shrink-0">
                        Search
                    </button>
                </div>
                <!-- Suggestions -->
                <div x-show="searchResults.length > 0" class="mt-2 rounded-lg border border-gray-200 bg-white shadow-lg overflow-hidden z-10 relative">
                    <template x-for="result in searchResults" :key="result.place_id">
                        <button type="button" @click="selectResult(result)"
                            class="w-full px-4 py-2.5 text-left text-sm hover:bg-gray-50 border-b border-gray-100 last:border-0 block truncate">
                            <span x-text="result.display_name" class="text-gray-700 font-medium"></span>
                        </button>
                    </template>
                </div>
                <p x-show="searchError" x-text="searchError" class="mt-1.5 text-xs text-red-600"></p>
                <p x-show="searching" class="mt-1.5 text-xs text-gray-500">Searching...</p>
            </div>

            <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div>
                    <label for="latitude" class="block text-sm font-medium text-gray-700">Latitude</label>
                    <input wire:model="latitude" id="latitude" type="number" step="any"
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                    @error('latitude') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="longitude" class="block text-sm font-medium text-gray-700">Longitude</label>
                    <input wire:model="longitude" id="longitude" type="number" step="any"
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                    @error('longitude') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div wire:ignore class="mt-4" style="height: 350px; border-radius: 8px; overflow: hidden;">
                <div x-ref="mapContainer" id="edit-map" style="width: 100%; height: 100%;"></div>
            </div>
        </div>

        {{-- ═══ Section: Social links ═══ --}}
        <div class="rounded-xl border border-gray-200 bg-white p-6">
            <h2 class="text-lg font-semibold text-gray-900">Social links</h2>
            <p class="mt-1 text-sm text-gray-500">Add your social media profiles. These will appear in your site footer.</p>

            <div x-data="socialLinksData" x-init="init()" class="mt-6 space-y-4">
                <template x-if="socialLinks.length === 0">
                    <p class="text-sm text-gray-400">No social links added yet. Click "Add link" below.</p>
                </template>
                <template x-for="(link, index) in socialLinks" :key="index">
                    <div class="flex items-center gap-3 rounded-lg border border-gray-200 bg-gray-50 p-3">
                        <div class="flex-1">
                            <select x-model="link.platform" x-on:change="updateSocialLinksJson()" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                                <option value="">Select platform</option>
                                <option value="Facebook">Facebook</option>
                                <option value="Instagram">Instagram</option>
                                <option value="Twitter / X">Twitter / X</option>
                                <option value="LinkedIn">LinkedIn</option>
                                <option value="YouTube">YouTube</option>
                                <option value="TikTok">TikTok</option>
                                <option value="Snapchat">Snapchat</option>
                                <option value="Pinterest">Pinterest</option>
                                <option value="GitHub">GitHub</option>
                                <option value="Telegram">Telegram</option>
                                <option value="WhatsApp">WhatsApp</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="flex-[2]">
                            <input x-model="link.url" x-on:change="updateSocialLinksJson()" type="url" placeholder="https://..."
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                        </div>
                        <button type="button" x-on:click="socialLinks.splice(index, 1); updateSocialLinksJson();"
                            class="shrink-0 rounded-lg bg-red-50 px-3 py-2 text-sm text-red-600 hover:bg-red-100">&times;</button>
                    </div>
                </template>
                <button type="button" x-on:click="socialLinks.push({ platform: '', url: '' }); updateSocialLinksJson();"
                    class="rounded-lg border border-dashed border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-600 hover:border-gray-400 hover:text-gray-900">
                    + Add social link
                </button>
            </div>

            <input type="hidden" wire:model="socialLinksJson" id="socialLinksJson">
        </div>

        <script>
            document.addEventListener('livewire:init', function () {
                Alpine.data('socialLinksData', () => ({
                    socialLinks: [],
                    init() {
                        try {
                            const raw = document.getElementById('socialLinksJson')?.value || '[]';
                            this.socialLinks = JSON.parse(raw);
                        } catch {
                            this.socialLinks = [];
                        }
                    },
                    updateSocialLinksJson() {
                        document.getElementById('socialLinksJson').value = JSON.stringify(this.socialLinks);
                        document.getElementById('socialLinksJson').dispatchEvent(new Event('input'));
                        Livewire.find('{{ $this->getId() }}').set('socialLinksJson', JSON.stringify(this.socialLinks));
                    }
                }));
            });
        </script>

        {{-- ═══ Section: Logo & cover ═══ --}}
        <div class="rounded-xl border border-gray-200 bg-white p-6">
            <h2 class="text-lg font-semibold text-gray-900">Logo & cover image</h2>
            <p class="mt-1 text-sm text-gray-500">Upload or replace your branding images.</p>

            <div class="mt-6 space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Logo</label>
                    <p class="text-xs text-gray-500">JPEG, PNG, WebP. Max 2MB.</p>
                    <div class="mt-2 flex items-center gap-6">
                        <div class="h-20 w-20 shrink-0 overflow-hidden rounded-lg bg-gray-100">
                            @if ($business->logo && ! $newLogo)
                                <img src="{{ Storage::url($business->logo) }}" alt="Logo" class="h-full w-full object-cover">
                            @elseif ($newLogo)
                                <img src="{{ $newLogo->temporaryUrl() }}" alt="Preview" class="h-full w-full object-cover">
                            @else
                                <div class="flex h-full items-center justify-center text-2xl font-bold text-gray-400">{{ substr($business->name, 0, 2) }}</div>
                            @endif
                        </div>
                        <label class="cursor-pointer text-sm text-gray-900 hover:text-gray-700">
                            <span class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-xs font-medium hover:bg-gray-50">Choose file</span>
                            <input wire:model="newLogo" type="file" accept="image/png,image/jpeg,image/webp" class="sr-only">
                            @if ($newLogo) <button type="button" wire:click="$set('newLogo', null)" class="ml-2 text-xs text-red-600">Cancel</button> @endif
                        </label>
                    </div>
                    @error('newLogo') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Cover image</label>
                    <p class="text-xs text-gray-500">JPEG, PNG, WebP. Max 5MB.</p>
                    @if ($business->cover && ! $newCover)
                        <div class="mt-2 overflow-hidden rounded-lg bg-gray-100 h-32 w-full max-w-lg">
                            <img src="{{ Storage::url($business->cover) }}" alt="Cover" class="h-full w-full object-cover">
                        </div>
                    @endif
                    @if ($newCover)
                        <div class="mt-2 overflow-hidden rounded-lg bg-gray-100 h-24 w-full max-w-lg">
                            <img src="{{ $newCover->temporaryUrl() }}" alt="Cover preview" class="h-full w-full object-cover">
                        </div>
                    @endif
                    <div class="mt-2">
                        <label class="cursor-pointer text-sm text-gray-900 hover:text-gray-700">
                            <span class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-xs font-medium hover:bg-gray-50">Choose file</span>
                            <input wire:model="newCover" type="file" accept="image/png,image/jpeg,image/webp" class="sr-only">
                            @if ($newCover) <button type="button" wire:click="$set('newCover', null)" class="ml-2 text-xs text-red-600">Cancel</button> @endif
                        </label>
                    </div>
                    @error('newCover') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- ═══ Section: Theme ═══ --}}
        <div class="rounded-xl border border-gray-200 bg-white p-6">
            <h2 class="text-lg font-semibold text-gray-900">Theme</h2>
            <p class="mt-1 text-sm text-gray-500">Choose a theme and customise its colours for your public page.</p>

            <div class="mt-6 grid grid-cols-1 gap-3 sm:grid-cols-3">
                @foreach ($themes as $theme)
                    @php $cfg = $theme->config; @endphp
                    <button wire:click="selectTheme({{ $theme->id }})" type="button"
                        class="relative rounded-xl border-2 p-4 text-left transition
                        {{ $themeId === $theme->id ? 'border-gray-900 bg-gray-50 ring-1 ring-gray-900' : 'border-gray-200 hover:border-gray-300' }}">
                        <div class="flex gap-1 mb-2">
                            <span class="h-5 w-5 rounded" style="background:{{ $cfg['colors']['primary'] ?? '#4F46E5' }}"></span>
                            <span class="h-5 w-5 rounded" style="background:{{ $cfg['colors']['secondary'] ?? '#10B981' }}"></span>
                            <span class="h-5 w-5 rounded" style="background:{{ $cfg['colors']['accent'] ?? '#F59E0B' }}"></span>
                        </div>
                        <h3 class="text-sm font-semibold text-gray-900">{{ $theme->name }}</h3>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $theme->description }}</p>
                        @if ($theme->is_premium)
                            <span class="mt-1.5 inline-block rounded-full bg-amber-100 px-2 py-0.5 text-[10px] font-semibold text-amber-700">Premium</span>
                        @endif
                    </button>
                @endforeach
            </div>
            @error('themeId') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror

            @if ($themeId)
                <div class="mt-6 rounded-lg border border-gray-200 bg-gray-50 p-5">
                    <h3 class="text-sm font-semibold text-gray-900">Customise colours</h3>
                    <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-xs font-medium text-gray-600">Primary</label>
                            <div class="mt-1 flex items-center gap-2">
                                <input wire:model="themePrimary" type="color" class="h-9 w-14 rounded-lg border p-0.5 cursor-pointer">
                                <input wire:model="themePrimary" type="text" maxlength="7" class="w-24 rounded-lg border px-2.5 py-2 text-xs">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600">Secondary</label>
                            <div class="mt-1 flex items-center gap-2">
                                <input wire:model="themeSecondary" type="color" class="h-9 w-14 rounded-lg border p-0.5 cursor-pointer">
                                <input wire:model="themeSecondary" type="text" maxlength="7" class="w-24 rounded-lg border px-2.5 py-2 text-xs">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600">Background</label>
                            <div class="mt-1 flex items-center gap-2">
                                <input wire:model="themeBackground" type="color" class="h-9 w-14 rounded-lg border p-0.5 cursor-pointer">
                                <input wire:model="themeBackground" type="text" maxlength="7" class="w-24 rounded-lg border px-2.5 py-2 text-xs">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600">Text</label>
                            <div class="mt-1 flex items-center gap-2">
                                <input wire:model="themeText" type="color" class="h-9 w-14 rounded-lg border p-0.5 cursor-pointer">
                                <input wire:model="themeText" type="text" maxlength="7" class="w-24 rounded-lg border px-2.5 py-2 text-xs">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600">Accent</label>
                            <div class="mt-1 flex items-center gap-2">
                                <input wire:model="themeAccent" type="color" class="h-9 w-14 rounded-lg border p-0.5 cursor-pointer">
                                <input wire:model="themeAccent" type="text" maxlength="7" class="w-24 rounded-lg border px-2.5 py-2 text-xs">
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center gap-3 rounded-lg border bg-white p-3">
                        <span class="h-8 w-8 rounded-lg" style="background:{{ $themePrimary }}"></span>
                        <span class="h-8 w-8 rounded-lg" style="background:{{ $themeSecondary }}"></span>
                        <span class="h-8 w-8 rounded-lg border" style="background:{{ $themeBackground }}"></span>
                        <span class="text-xs font-medium" style="color:{{ $themeText }}">Aa</span>
                        <span class="h-8 w-8 rounded-lg" style="background:{{ $themeAccent }}"></span>
                        <span class="text-xs text-gray-400 ml-auto">Live preview</span>
                    </div>
                </div>
            @endif
        </div>

        {{-- Submit --}}
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('dashboard') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">Cancel</a>
            <button type="submit"
                class="rounded-lg bg-gray-900 px-8 py-2.5 text-sm font-semibold text-white hover:bg-gray-800">
                Save changes
            </button>
        </div>
    </form>
</div>



