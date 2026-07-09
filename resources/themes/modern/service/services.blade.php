{{--
  Modern theme · Service services — premium service grid with soft shadows and refined details.
--}}
<section id="services" style="background: #ffffff; border-top: 1px solid color-mix(in srgb, var(--color-text) 5%, transparent);">
    <div class="container">
        <h2 class="section-title">Our Services</h2>
        <p class="section-subtitle">Browse our high-quality professional services</p>
        
        @if ($services && $services->isNotEmpty())
            {{-- Category Filter Pills --}}
            @php
                $uniqueCategories = $services->pluck('category')->filter()->unique('id');
            @endphp
            @if ($uniqueCategories->isNotEmpty())
                <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 0.75rem; margin-bottom: 3rem;" x-data="{ activeCat: 'all' }">
                    <button @click="activeCat = 'all'; $dispatch('filter-category', { categoryId: 'all' })" 
                            :class="activeCat === 'all' ? 'active-filter-modern' : 'inactive-filter-modern'"
                            style="padding: 0.625rem 1.25rem; font-size: 0.8125rem; font-family: var(--font-heading); font-weight: 600; border: 1px solid color-mix(in srgb, var(--color-text) 8%, transparent); border-radius: 9999px; cursor: pointer; transition: all 0.2s; background: transparent; color: var(--color-text);">
                        All Services
                    </button>
                    @foreach ($uniqueCategories as $cat)
                        <button @click="activeCat = '{{ $cat->id }}'; $dispatch('filter-category', { categoryId: '{{ $cat->id }}' })"
                                :class="activeCat === '{{ $cat->id }}' ? 'active-filter-modern' : 'inactive-filter-modern'"
                                style="padding: 0.625rem 1.25rem; font-size: 0.8125rem; font-family: var(--font-heading); font-weight: 600; border: 1px solid color-mix(in srgb, var(--color-text) 8%, transparent); border-radius: 9999px; cursor: pointer; transition: all 0.2s; background: transparent; color: var(--color-text);">
                            {{ $cat->name }}
                        </button>
                    @endforeach
                </div>
                
                <style>
                    .active-filter-modern { 
                        background: var(--color-primary) !important; 
                        color: var(--color-background) !important; 
                        border-color: var(--color-primary) !important;
                        box-shadow: 0 4px 12px color-mix(in srgb, var(--color-primary) 15%, transparent);
                    }
                    .inactive-filter-modern:hover { 
                        background: color-mix(in srgb, var(--color-text) 4%, transparent); 
                    }
                </style>
            @endif

            {{-- Services Grid --}}
            <div class="grid grid-2" x-data="{ currentCategory: 'all' }" @filter-category.window="currentCategory = $event.detail.categoryId">
                @foreach ($services->sortBy('sort_order') as $service)
                    <div x-show="currentCategory === 'all' || currentCategory == '{{ $service->category_id ?? 0 }}'"
                         class="card" style="display: flex; flex-direction: column; justify-content: space-between; padding: 2rem; height: 100%;">
                        
                        <div>
                            {{-- Header --}}
                            <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem; margin-bottom: 0.75rem;">
                                <span style="font-size: 0.75rem; color: var(--color-primary); font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">
                                    {{ $service->category?->name ?? 'Service' }}
                                </span>
                                
                                @if ($service->duration_minutes)
                                    <span style="font-size: 0.75rem; background: color-mix(in srgb, var(--color-text) 4%, transparent); color: color-mix(in srgb, var(--color-text) 60%, transparent); font-weight: 600; padding: 0.25rem 0.625rem; border-radius: 9999px; display: inline-flex; align-items: center; gap: 0.25rem;">
                                        <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ $service->duration_minutes }} min
                                    </span>
                                @endif
                            </div>

                            <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 0.75rem; line-height: 1.3;">
                                <a href="{{ route('business.service', [$business, $service]) }}" style="color: inherit; text-decoration: none;">
                                    {{ $service->name }}
                                </a>
                            </h3>

                            @if ($service->description)
                                <p style="font-size: 0.875rem; color: color-mix(in srgb, var(--color-text) 60%, transparent); margin-bottom: 1.5rem; line-height: 1.6;">
                                    {{ Str::limit($service->description, 140) }}
                                </p>
                            @endif
                        </div>

                        {{-- Footer area --}}
                        <div style="border-top: 1px solid color-mix(in srgb, var(--color-text) 6%, transparent); padding-top: 1.25rem; display: flex; align-items: center; justify-content: space-between; margin-top: auto;">
                            <div>
                                @if ($service->price)
                                    <span style="font-weight: 800; font-size: 1.25rem; color: var(--color-text);">
                                        {{ number_format($service->price, 2) }}
                                    </span>
                                @else
                                    <span style="font-weight: 600; font-size: 0.875rem; color: color-mix(in srgb, var(--color-text) 40%, transparent);">
                                        Contact for quote
                                    </span>
                                @endif
                            </div>

                            <a href="{{ route('business.service', [$business, $service]) }}" style="font-size: 0.875rem; font-weight: 600; color: var(--color-primary); display: inline-flex; align-items: center; gap: 0.25rem;">
                                Booking Details &rarr;
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div style="text-align: center; padding: 4rem 0; color: color-mix(in srgb, var(--color-text) 30%, transparent);">
                <p>No services listed yet.</p>
            </div>
        @endif
    </div>
</section>
