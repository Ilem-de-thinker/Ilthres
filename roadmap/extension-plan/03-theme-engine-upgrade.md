# 03 — Theme Engine Upgrade

> Wrap the existing theme resolution with a service that adds pages/sections data.

## What Already Exists

- `PublicProfile` Livewire resolves theme from `business_theme` pivot
- `PublicPageController` shares same resolution logic (duplicated)
- Theme config stored in `themes.config` JSONB
- Business customization stored in `business_theme.customization` JSONB
- CSS variable injection works via `{{ $colors['primary'] }}` etc.
- 4 themes: default, modern, bold, heritage — each with store/ and service/ variants

## What This Phase Adds

### 1. ThemeEngine Service

Centralizes theme resolution (currently duplicated in PublicProfile + PublicPageController).

```php
// app/Services/ThemeEngine.php

class ThemeEngine
{
    /**
     * Resolve the active theme for a business.
     * Currently duplicated in PublicProfile::render() and PublicPageController::resolveThemeData()
     */
    public function resolveTheme(Business $business): Theme { ... }

    /**
     * Get merged color/typography config (defaults + theme config + business customization).
     * Currently duplicated in PublicProfile::render() and PublicPageController::resolveThemeData()
     */
    public function getThemeConfig(Business $business, ?Theme $previewTheme = null): array { ... }

    /**
     * Get the view path for a business's theme.
     */
    public function getViewPath(Business $business, string $type = 'layout'): string { ... }

    /**
     * Load page sections for a website (if pages exist).
     */
    public function getPageSections(Website $website, string $pageSlug = 'home'): Collection { ... }
}
```

**File:** `app/Services/ThemeEngine.php`

### 2. Refactor Existing Code

Replace duplicated theme resolution in:

**PublicProfile Livewire:**
```php
// Before: 30+ lines of theme resolution logic
// After:
$themeEngine = app(ThemeEngine::class);
$activeTheme = $themeEngine->resolveTheme($this->business);
$config = $themeEngine->getThemeConfig($this->business, $this->previewTheme);
$view = $themeEngine->getViewPath($this->business, $type);
```

**PublicPageController:**
```php
// Before: duplicated resolveThemeData() method
// After:
$themeEngine = app(ThemeEngine::class);
$activeTheme = $themeEngine->resolveTheme($business);
$config = $themeEngine->getThemeConfig($business);
```

### 3. Sections Data Layer

ThemeEngine loads sections from the website (if they exist):

```php
public function getPageSections(Website $website, string $pageSlug = 'home'): Collection
{
    $page = $website->pages()->where('slug', $pageSlug)->first();
    if (!$page) {
        return collect();
    }
    return $page->sections()->where('is_visible', true)->orderBy('sort_order')->get();
}
```

Passed to theme views:
```php
$sections = $this->website
    ? $themeEngine->getPageSections($this->website, 'home')
    : collect();
```

### 4. Theme Blade Structure (unchanged)

```
resources/views/themes/
├── default/
│   ├── layout.blade.php          ← main wrapper
│   ├── store/                    ← product store variant
│   │   ├── layout.blade.php
│   │   ├── product-detail.blade.php
│   │   └── hero.blade.php etc.
│   └── service/                  ← service business variant
│       ├── layout.blade.php
│       ├── service-detail.blade.php
│       └── hero.blade.php etc.
├── modern/
├── bold/
└── heritage/
```

Themes that want to use sections add this to their layout:
```php
@if(isset($sections) && $sections->count())
    @foreach($sections as $section)
        @if(view()->exists("themes::{$theme->slug}.components.{$section->type}"))
            @include("themes::{$theme->slug}.components.{$section->type}", ['data' => $section->content])
        @else
            @include("themes::default.components.{$section->type}", ['data' => $section->content])
        @endif
    @endforeach
@else
    {{-- Existing hardcoded sections --}}
@endif
```

---

## Checklist

- [ ] Create `ThemeEngine` service
- [ ] Refactor `PublicProfile` to use ThemeEngine
- [ ] Refactor `PublicPageController` to use ThemeEngine
- [ ] Remove duplicated theme resolution logic
- [ ] Add `getPageSections()` method
- [ ] Pass `$sections` to theme views
- [ ] Update default theme layout to optionally render from sections
- [ ] Verify all 4 themes still render correctly without sections
