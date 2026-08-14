<?php

namespace App\Domains\Business\Livewire;

use App\Domains\Analytics\Actions\RecordEvent;
use App\Models\AnalyticsEvent;
use App\Models\Business;
use App\Models\Product;
use App\Models\PromoBanner;
use App\Models\Service;
use App\Services\ThemeEngine;
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
        if (! isset($this->business)) {
            $resolved = current_business();
            if ($resolved) {
                $this->business = $resolved;
            }
        }

        if (! isset($this->business)) {
            abort(404);
        }

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
        $themeEngine = app(ThemeEngine::class);

        $activeTheme = $themeEngine->resolveTheme($this->business, $this->previewTheme);
        $config = $themeEngine->getThemeConfig($this->business, $this->previewTheme);

        $type = $this->business->isProductStore() ? 'store' : 'service';

        $products = $this->business->isProductStore()
            ? $this->business->products()->latest()->get()
            : collect();
        $services = $this->business->isServiceBusiness()
            ? $this->business->services()->latest()->get()
            : collect();

        $heroBanners = PromoBanner::where('business_id', $this->business->id)
            ->hero()
            ->active()
            ->ordered()
            ->with($this->business->isProductStore() ? 'products' : 'services')
            ->get();

        $website = $this->business->website;
        $sections = $website
            ? $themeEngine->getPageSections($website, 'home')
            : collect();

        if ($this->product) {
            $view = "themes::{$config['slug']}.{$type}.product-detail";
        } elseif ($this->service) {
            $view = "themes::{$config['slug']}.{$type}.service-detail";
        } else {
            $view = "themes::{$config['slug']}.{$type}.layout";
        }

        if (! view()->exists($view)) {
            $view = "themes::default.{$type}.".($this->product ? 'product-detail' : ($this->service ? 'service-detail' : 'layout'));
        }

        return view($view, [
            'business' => $this->business,
            'colors' => $config['colors'],
            'typography' => $config['typography'],
            'radius' => $config['radius'],
            'animations' => $config['animations'],
            'products' => $products,
            'services' => $services,
            'product' => $this->product,
            'service' => $this->service,
            'heroBanners' => $heroBanners,
            'sections' => $sections,
            'theme' => $activeTheme,
        ]);
    }
}
