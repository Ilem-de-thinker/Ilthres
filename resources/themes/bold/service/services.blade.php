{{--
  Bold theme · Service services — high-contrast editorial services list.
--}}
<section id="services" style="background: var(--color-background); border-top: 2px solid var(--color-text);">
    <div class="container">
        <h2 class="section-title">Services</h2>
        <p class="section-subtitle">Our Professional Expertise</p>
        
        @if ($services && $services->isNotEmpty())
            {{-- Category Filter --}}
            @php
                $uniqueCategories = $services->pluck('category')->filter()->unique('id');
            @endphp
            @if ($uniqueCategories->isNotEmpty())
                <div style="display: flex; flex-wrap: wrap; justify-content: flex-start; gap: 0.5rem; margin-bottom: 3.5rem;" x-data="{ activeCat: 'all' }">
                    <button @click="activeCat = 'all'; $dispatch('filter-category', { categoryId: 'all' })" 
                            :class="activeCat === 'all' ? 'active-filter-bold' : 'inactive-filter-bold'"
                            style="padding: 0.625rem 1.25rem; font-family: var(--font-heading); font-size: 0.8125rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; border: 2px solid var(--color-text); cursor: pointer; transition: all 0.15s; background: transparent; color: var(--color-text);">
                        All Services
                    </button>
                    @foreach ($uniqueCategories as $cat)
                        <button @click="activeCat = '{{ $cat->id }}'; $dispatch('filter-category', { categoryId: '{{ $cat->id }}' })"
                                :class="activeCat === '{{ $cat->id }}' ? 'active-filter-bold' : 'inactive-filter-bold'"
                                style="padding: 0.625rem 1.25rem; font-family: var(--font-heading); font-size: 0.8125rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; border: 2px solid var(--color-text); cursor: pointer; transition: all 0.15s; background: transparent; color: var(--color-text);">
                            {{ $cat->name }}
                        </button>
                    @endforeach
                </div>
                
                <style>
                    .active-filter-bold { 
                        background: var(--color-text) !important; 
                        color: var(--color-background) !important;
                    }
                    .inactive-filter-bold:hover { 
                        background: color-mix(in srgb, var(--color-text) 5%, transparent); 
                    }
                </style>
            @endif

            {{-- Services Grid --}}
            <div class="grid grid-2" x-data="{ currentCategory: 'all' }" @filter-category.window="currentCategory = $event.detail.categoryId">
                @foreach ($services->sortBy('sort_order') as $service)
                    <div x-show="currentCategory === 'all' || currentCategory == '{{ $service->category_id ?? 0 }}'"
                         class="card" style="padding: 2.25rem; display: flex; flex-direction: column; justify-content: space-between; height: 100%;">
                        
                        <div>
                            {{-- Header --}}
                            <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem; margin-bottom: 0.75rem;">
                                <span style="font-size: 0.75rem; color: var(--color-text); font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; opacity: 0.6;">
                                    {{ $service->category?->name ?? 'Service' }}
                                </span>
                                
                                @if ($service->duration_minutes)
                                    <span style="font-size: 0.75rem; border: 1.5px solid var(--color-text); color: var(--color-text); font-weight: 700; padding: 0.25rem 0.625rem; text-transform: uppercase; letter-spacing: 0.05em;">
                                        {{ $service->duration_minutes }} min
                                    </span>
                                @endif
                            </div>

                            <h3 style="font-size: 1.375rem; font-weight: 800; margin-bottom: 0.75rem; line-height: 1.2; text-transform: uppercase;">
                                <a href="{{ route('business.service', [$business, $service]) }}" style="color: inherit; text-decoration: none;">
                                    {{ $service->name }}
                                </a>
                            </h3>

                            @if ($service->description)
                                <p style="font-size: 0.9375rem; color: var(--color-text); margin-bottom: 1.5rem; line-height: 1.6; opacity: 0.8;">
                                    {{ Str::limit($service->description, 140) }}
                                </p>
                            @endif
                        </div>

                        {{-- Price and Action Area --}}
                        <div style="border-top: 2px solid var(--color-text); padding-top: 1.25rem; display: flex; align-items: center; justify-content: space-between; margin-top: auto;">
                            <div>
                                @if ($service->price)
                                    <span style="font-weight: 900; font-size: 1.375rem; font-family: var(--font-heading);">
                                        {{ number_format($service->price, 2) }}
                                    </span>
                                @else
                                    <span style="font-weight: 800; font-size: 0.9375rem; text-transform: uppercase; opacity: 0.5;">
                                        Contact Us
                                    </span>
                                @endif
                            </div>

                            <a href="{{ route('business.service', [$business, $service]) }}" class="btn" style="padding: 0.5rem 1rem; font-size: 0.75rem; border-width: 2px; box-shadow: none;">
                                View Service &rarr;
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div style="text-align: center; padding: 4rem 0; color: var(--color-text); font-weight: 800; text-transform: uppercase;">
                <p>No services listed yet.</p>
            </div>
        @endif
    </div>
</section>
