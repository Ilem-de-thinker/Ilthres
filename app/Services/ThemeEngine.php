<?php

namespace App\Services;

use App\Models\Business;
use App\Models\Theme;
use App\Models\Website;
use Illuminate\Support\Collection;

class ThemeEngine
{
    /**
     * Resolve the active theme for a business.
     */
    public function resolveTheme(Business $business, ?Theme $previewTheme = null): ?Theme
    {
        if ($previewTheme) {
            return $previewTheme;
        }

        $activeTheme = $business->themes()
            ->wherePivot('is_active', true)
            ->first();

        if (! $activeTheme) {
            $activeTheme = Theme::whereNull('business_type')
                ->orWhere('business_type', $business->type)
                ->orderByRaw('CASE WHEN business_type = ? THEN 0 ELSE 1 END', [$business->type])
                ->first() ?? Theme::where('slug', 'default')->first();
        }

        return $activeTheme;
    }

    /**
     * Get merged theme configuration (defaults + theme config + business customization).
     */
    public function getThemeConfig(Business $business, ?Theme $previewTheme = null): array
    {
        $activeTheme = $this->resolveTheme($business, $previewTheme);

        $config = $activeTheme?->config ?? [];
        $customization = $previewTheme ? [] : ($activeTheme?->pivot?->customization ?? []);

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
        $animations = $customization['animations'] ?? $config['animations'] ?? true;

        return [
            'activeTheme' => $activeTheme,
            'slug' => $activeTheme?->slug ?? 'default',
            'colors' => $colors,
            'typography' => $typography,
            'radius' => $radius,
            'animations' => $animations,
        ];
    }

    /**
     * Get the view path for a business's theme.
     */
    public function getViewPath(Business $business, string $type = 'layout', ?Theme $previewTheme = null): string
    {
        $themeConfig = $this->getThemeConfig($business, $previewTheme);
        $slug = $themeConfig['slug'];
        $businessType = $business->isProductStore() ? 'store' : 'service';

        $view = "themes::{$slug}.{$businessType}.{$type}";

        if (! view()->exists($view)) {
            $view = "themes::default.{$businessType}.{$type}";
        }

        return $view;
    }

    /**
     * Load page sections for a website.
     */
    public function getPageSections(Website $website, string $pageSlug = 'home'): Collection
    {
        $page = $website->pages()->where('slug', $pageSlug)->first();

        if (! $page) {
            return collect();
        }

        return $page->sections()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }
}
