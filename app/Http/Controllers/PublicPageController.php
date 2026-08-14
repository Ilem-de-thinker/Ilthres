<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Services\ThemeEngine;
use Illuminate\View\View;

class PublicPageController extends Controller
{
    public function __construct(
        private ThemeEngine $themeEngine,
    ) {}

    public function portfolio(string $business): View
    {
        $businessModel = Business::where('slug', $business)->firstOrFail();
        abort_unless($businessModel->is_active, 404);

        $config = $this->themeEngine->getThemeConfig($businessModel);
        $type = $businessModel->isProductStore() ? 'store' : 'service';

        $view = "themes::{$config['slug']}.{$type}.portfolio";
        if (! view()->exists($view)) {
            $view = "themes::default.{$type}.portfolio";
        }

        $website = $businessModel->website;
        $sections = $website
            ? $this->themeEngine->getPageSections($website, 'portfolio')
            : collect();

        return view($view, array_merge($config, [
            'business' => $businessModel,
            'product' => null,
            'service' => null,
            'sections' => $sections,
        ]));
    }

    public function about(string $business): View
    {
        $businessModel = Business::where('slug', $business)->firstOrFail();
        abort_unless($businessModel->is_active, 404);

        $config = $this->themeEngine->getThemeConfig($businessModel);
        $type = $businessModel->isProductStore() ? 'store' : 'service';

        $view = "themes::{$config['slug']}.{$type}.about-page";
        if (! view()->exists($view)) {
            $view = "themes::default.{$type}.about-page";
        }

        $website = $businessModel->website;
        $sections = $website
            ? $this->themeEngine->getPageSections($website, 'about')
            : collect();

        return view($view, array_merge($config, [
            'business' => $businessModel,
            'product' => null,
            'service' => null,
            'sections' => $sections,
        ]));
    }

    public function contact(string $business): View
    {
        $businessModel = Business::where('slug', $business)->firstOrFail();
        abort_unless($businessModel->is_active, 404);

        $config = $this->themeEngine->getThemeConfig($businessModel);
        $type = $businessModel->isProductStore() ? 'store' : 'service';

        $view = "themes::{$config['slug']}.{$type}.contact";
        if (! view()->exists($view)) {
            $view = "themes::default.{$type}.contact";
        }

        $website = $businessModel->website;
        $sections = $website
            ? $this->themeEngine->getPageSections($website, 'contact')
            : collect();

        return view($view, array_merge($config, [
            'business' => $businessModel,
            'product' => null,
            'service' => null,
            'sections' => $sections,
        ]));
    }
}
