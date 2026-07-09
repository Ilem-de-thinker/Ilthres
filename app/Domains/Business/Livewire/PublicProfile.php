<?php

namespace App\Domains\Business\Livewire;

use App\Domains\Analytics\Actions\RecordEvent;
use App\Models\AnalyticsEvent;
use App\Models\Business;
use App\Models\Product;
use App\Models\Service;
use App\Models\Theme;
use Livewire\Attributes\Layout;
use Livewire\Component;

/**
 * Renders a business's public profile through its active theme.
 *
 * Resolves the active theme (or falls back to "default"), merges its
 * default config with per-business customization, and renders the
 * correct template set (store/* or service/*) based on business type.
 */
#[Layout('layouts.theme')]
class PublicProfile extends Component
{
    public Business $business;
    public ?Product $product = null;
    public ?Service $service = null;

    public ?Theme $previewTheme = null;

    public function mount(?Product $product = null, ?Service $service = null): void
    {
        abort_if(! $this->business->is_active, 404);

        if ($product) {
            abort_if($product->business_id !== $this->business->id, 404);
            $this->product = $product;
        }
        if ($service) {
            abort_if($service->business_id !== $this->business->id, 404);
            $this->service = $service;
        }

        $previewId = request()->query('theme_preview');
        if ($previewId) {
            $this->previewTheme = Theme::find($previewId);
        }

        $this->recordViewEvent();
    }

    private function recordViewEvent(): void
    {
        try {
            if ($this->product) {
                app(RecordEvent::class)(
                    $this->business,
                    AnalyticsEvent::TYPE_PRODUCT_VIEW,
                    metadata: ['product_id' => $this->product->id, 'product_name' => $this->product->name],
                );
            } elseif ($this->service) {
                app(RecordEvent::class)(
                    $this->business,
                    AnalyticsEvent::TYPE_SERVICE_VIEW,
                    metadata: ['service_id' => $this->service->id, 'service_name' => $this->service->name],
                );
            } else {
                app(RecordEvent::class)(
                    $this->business,
                    AnalyticsEvent::TYPE_PAGE_VIEW,
                );
            }
        } catch (\Throwable $e) {
            report($e);
        }
    }

    public function render()
    {
        if ($this->previewTheme) {
            $activeTheme = $this->previewTheme;
        } else {
            $activeTheme = $this->business->themes()
                ->wherePivot('is_active', true)
                ->first();
        }

        if (! $activeTheme) {
            $activeTheme = Theme::whereNull('business_type')
                ->orWhere('business_type', $this->business->type)
                ->orderByRaw("CASE WHEN business_type = ? THEN 0 ELSE 1 END", [$this->business->type])
                ->first() ?? Theme::where('slug', 'default')->first();
        }

        $slug = $activeTheme?->slug ?? 'default';
        $type = $this->business->isProductStore() ? 'store' : 'service';

        $config = $activeTheme?->config ?? [];
        $customization = $this->previewTheme ? [] : ($activeTheme?->pivot->customization ?? []);

        $defaultColors = [
            'primary' => '#111827',
            'secondary' => '#4B5563',
            'background' => '#FFFFFF',
            'text' => '#111827',
            'accent' => '#9CA3AF',
        ];

        $colors = array_merge(
            $defaultColors,
            $config['colors'] ?? [],
            $customization['colors'] ?? []
        );
        $typography = array_merge(
            $config['typography'] ?? [],
            $customization['typography'] ?? []
        );
        $radius = $customization['radius'] ?? $config['radius'] ?? '0.5rem';

        $products = $this->business->isProductStore()
            ? $this->business->products()->latest()->get()
            : collect();
        $services = $this->business->isServiceBusiness()
            ? $this->business->services()->latest()->get()
            : collect();

        if ($this->product) {
            $view = "themes::{$slug}.{$type}.product-detail";
        } elseif ($this->service) {
            $view = "themes::{$slug}.{$type}.service-detail";
        } else {
            $view = "themes::{$slug}.{$type}.layout";
        }

        if (! view()->exists($view)) {
            $view = "themes::default.{$type}." . ($this->product ? 'product-detail' : ($this->service ? 'service-detail' : 'layout'));
        }

        return view($view, [
            'business' => $this->business,
            'colors' => $colors,
            'typography' => $typography,
            'radius' => $radius,
            'products' => $products,
            'services' => $services,
            'product' => $this->product,
            'service' => $this->service,
        ]);
    }
}
