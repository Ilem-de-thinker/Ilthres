{{--
  Default theme · Service services — clean, structured, minimal services showcase.
--}}
<section id="services">
    <div class="container">
        <h2 class="section-title">Services</h2>
        
        @if ($services && $services->isNotEmpty())
            {{-- Category Filter Pills --}}
            @php
                $uniqueCategories = $services->pluck('category')->filter()->unique('id');
            @endphp
            @if ($uniqueCategories->isNotEmpty())
                <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 0.5rem; margin-bottom: 3rem;" x-data="{ activeCat: 'all' }">
                    <button @click="activeCat = 'all'; $dispatch('filter-category', { categoryId: 'all' })" 
                            :class="activeCat === 'all' ? 'active-filter' : 'inactive-filter'"
                            style="padding: 0.5rem 1.25rem; font-size: 0.8125rem; font-weight: 500; border: 1px solid color-mix(in srgb, var(--color-text) 12%, transparent); border-radius: var(--radius); cursor: pointer; transition: all 0.15s; background: transparent; color: var(--color-text);">
                        All Services
                    </button>
                    @foreach ($uniqueCategories as $cat)
                        <button @click="activeCat = '{{ $cat->id }}'; $dispatch('filter-category', { categoryId: '{{ $cat->id }}' })"
                                :class="activeCat === '{{ $cat->id }}' ? 'active-filter' : 'inactive-filter'"
                                style="padding: 0.5rem 1.25rem; font-size: 0.8125rem; font-weight: 500; border: 1px solid color-mix(in srgb, var(--color-text) 12%, transparent); border-radius: var(--radius); cursor: pointer; transition: all 0.15s; background: transparent; color: var(--color-text);">
                            {{ $cat->name }}
                        </button>
                    @endforeach
                </div>
                
                <style>
                    .active-filter { background: var(--color-primary) !important; color: var(--color-background) !important; border-color: var(--color-primary) !important; }
                    .inactive-filter:hover { background: color-mix(in srgb, var(--color-text) 4%, transparent); }
                </style>
            @endif

            {{-- Services List --}}
            <div style="display: flex; flex-direction: column; border-top: 1px solid color-mix(in srgb, var(--color-text) 10%, transparent);" x-data="{ currentCategory: 'all' }" @filter-category.window="currentCategory = $event.detail.categoryId">
                @foreach ($services->sortBy('sort_order') as $service)
                    <div x-show="currentCategory === 'all' || currentCategory == '{{ $service->category_id ?? 0 }}'"
                         style="background: var(--color-background); border-bottom: 1px solid color-mix(in srgb, var(--color-text) 10%, transparent); padding: 1.5rem 0; display: flex; align-items: flex-start; justify-content: space-between; gap: 2rem;">
                        
                        <div style="flex-grow: 1;">
                            <span style="font-size: 0.75rem; color: color-mix(in srgb, var(--color-text) 40%, transparent); margin-bottom: 0.25rem; display: block; font-weight: 500;">
                                {{ $service->category?->name ?? 'General' }}
                            </span>
                            <h3 style="font-size: 1.0625rem; font-weight: 600; margin-bottom: 0.5rem; line-height: 1.3;">
                                <a href="{{ route('business.service', [$business, $service]) }}" style="color: var(--color-text); text-decoration: none;">
                                    {{ $service->name }}
                                </a>
                            </h3>
                            @if ($service->description)
                                <p style="font-size: 0.875rem; color: color-mix(in srgb, var(--color-text) 60%, transparent); line-height: 1.6; max-width: 800px;">
                                    {{ $service->description }}
                                </p>
                            @endif
                        </div>
                        
                        <div style="text-align: right; flex-shrink: 0; display: flex; flex-direction: column; gap: 0.375rem; align-items: flex-end;">
                            @if ($service->price)
                                <span style="font-weight: 600; font-size: 1.0625rem; color: var(--color-text);">
                                    {{ number_format($service->price, 2) }}
                                </span>
                            @endif
                            @if ($service->duration_minutes)
                                <span style="font-size: 0.75rem; color: color-mix(in srgb, var(--color-text) 40%, transparent); font-weight: 500;">
                                    {{ $service->duration_minutes }} mins
                                </span>
                            @endif
                            <a href="{{ route('business.service', [$business, $service]) }}" style="font-size: 0.8125rem; font-weight: 500; color: var(--color-primary); text-decoration: none; margin-top: 0.5rem;">
                                View Service &rarr;
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div style="text-align: center; padding: 4rem 0; color: color-mix(in srgb, var(--color-text) 40%, transparent);">
                <p>No services listed yet.</p>
            </div>
        @endif
    </div>
</section>
