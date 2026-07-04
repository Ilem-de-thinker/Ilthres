# Theme Engine

> The theme engine renders merchant business pages with customizable appearance. Themes provide the visual shell; businesses provide the data.

---

## Architecture

```
Theme Engine
    │
    ├── Models
    │   ├── Theme           # Available theme templates
    │   └── BusinessTheme   # Pivot: business + theme + customization
    │
    ├── Actions
    │   ├── ActivateTheme.php
    │   └── CustomizeTheme.php
    │
    ├── Livewire
    │   ├── ThemeList.php         # Grid of available themes
    │   ├── ThemePreview.php      # Live iframe preview
    │   ├── ThemeCustomizer.php   # Color, font, radius controls
    │   └── ThemeRenderer.php     # Renders active theme on public page
    │
    └── resources/themes/         # Theme Blade files
```

---

## Theme Model

```php
/**
 * Represents a theme template available for businesses.
 *
 * Themes define the visual appearance of public business pages.
 * They are not page builders — they only style and arrange data
 * that belongs to the business.
 */
class Theme extends Model
{
    protected $casts = [
        'config' => 'array',  // Default colors, fonts, radii
        'is_premium' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function businesses(): BelongsToMany
    {
        return $this->belongsToMany(Business::class)
            ->using(BusinessTheme::class)
            ->withPivot(['customization', 'is_active'])
            ->withTimestamps();
    }
}
```

---

## Theme Customization

Each business customizes its active theme through the `BusinessTheme` pivot:

```php
/**
 * Pivot model connecting a business to a theme with customization data.
 *
 * customization JSON:
 * {
 *   "colors": {
 *     "primary": "#3B82F6",
 *     "secondary": "#10B981",
 *     "background": "#FFFFFF",
 *     "text": "#111827",
 *     "accent": "#F59E0B"
 *   },
 *   "typography": {
 *     "heading": "Inter",
 *     "body": "Inter"
 *   },
 *   "radius": "0.5rem",
 *   "animations": true
 * }
 */
class BusinessTheme extends Pivot
{
    protected $casts = [
        'customization' => 'array',
        'is_active' => 'boolean',
    ];
}
```

---

## Theme Rendering Pipeline

```
Request → Route → Controller → Livewire: ThemeRenderer
                                      │
                                      ├── Resolve Business + active Theme
                                      │
                                      ├── Merge Theme config + Business customization
                                      │
                                      ├── Inject CSS variables into <head>
                                      │
                                      └── Render theme layout with business data
```

### CSS Variable Injection

```html
<style>
    :root {
        --color-primary: {{ $customization['colors']['primary'] }};
        --color-secondary: {{ $customization['colors']['secondary'] }};
        --color-background: {{ $customization['colors']['background'] }};
        --color-text: {{ $customization['colors']['text'] }};
        --color-accent: {{ $customization['colors']['accent'] }};
        --font-heading: '{{ $customization['typography']['heading'] }}';
        --font-body: '{{ $customization['typography']['body'] }}';
        --radius: {{ $customization['radius'] }};
    }
</style>
```

---

## Theme Files Structure

```
resources/themes/
├── default/                    # Free theme (always available)
│   ├── layout.blade.php        # Main layout
│   ├── header.blade.php        # Business header with logo
│   ├── hero.blade.php          # Hero/cover section
│   ├── products.blade.php      # Products grid
│   ├── services.blade.php      # Services list
│   ├── about.blade.php         # About section
│   ├── gallery.blade.php       # Media gallery
│   ├── contact.blade.php       # Contact + actions
│   ├── footer.blade.php        # Footer
│   └── theme.json              # Theme metadata + default config
│
├── modern/                     # Premium theme
│   ├── layout.blade.php
│   ├── ...
│   └── theme.json
│
└── bold/                       # Premium theme
    ├── layout.blade.php
    ├── ...
    └── theme.json
```

### theme.json (Example)

```json
{
    "name": "Default",
    "slug": "default",
    "description": "Clean, minimal theme suitable for all business types.",
    "thumbnail": "/images/themes/default.png",
    "is_premium": false,
    "config": {
        "colors": {
            "primary": "#3B82F6",
            "secondary": "#10B981",
            "background": "#FFFFFF",
            "text": "#111827",
            "accent": "#F59E0B"
        },
        "typography": {
            "heading": "Inter",
            "body": "Inter"
        },
        "radius": "0.5rem",
        "animations": true
    }
}
```

---

## Key Rules

1. **Themes only render data** — they never own or modify data. All data comes from the Business, Product, and Service models.
2. **Themes cannot execute PHP** — only Blade directives and Alpine.js. No business logic.
3. **Customization is saved per-business** — never modifies the base theme.
4. **Fallback chain** — missing customization values fall back to theme defaults, then to the engine defaults.
5. **Preview mode** — `ThemePreview` renders a theme with sample data so merchants can try before activating.
