# Stitch OmniBusiness Theme Architecture

## Legacy Themes Structure

This project follows a **multi-theme architecture** where themes are **integrated into the existing themes directory structure**:

```text
resources/themes/
├── modern/              # Theme-based product & service pages
├── default/            # Default theme fallback
└── bold/               # Bold theme alternative
```

## Theme Philosophy

### **Theme Isolation**

Each theme directory contains **complete page implementations**:

- **Modern Theme**: Modern, gradient-enhanced product showcase
- **Default Theme**: Clean, structured, minimal product showcase  
- **Bold Theme**: Bold, distinctive interface

Each theme implements its own components and styling independently.

### **Mobile-First Development**

All themes are built **mobile-first**:

1. **Mobile** devices → Table settings and responsive behavior
2. **Tablet** enhancement → Improved layout and controls
3. **Desktop** optimization → Full-featured interface
4. **Large Desktop** refinement → Advanced layouts and capabilities

---

## Theme Implementation Details

### **1. Modern Theme**

**Characteristics:** Premium product grid with soft shadows, refined details

```css
/* Modern theme CSS variables */
:root {
    --color-primary: #8B5CF6;
    --color-secondary: #06B6D4;
    --color-background: #F9FAFB;
    --color-text: #1F2937;
    --color-accent: #F472B6;
    --font-heading: 'Plus Jakarta Sans', system-ui, sans-serif;
    --radius: 0.75rem;
}
```

**Visual Identity:**
- Elegant gradient styling
- Soft shadows and elevation
- Professional spacing (2rem gaps)
- Refined cards with hover effects

**Component Architecture:**
- Theme-specific categories
- Modern filter pills
- Responsive grid layouts
- Interactive hover states

### **2. Default Theme**

**Characteristics:** Clean, structured, minimal product showcase

```css
/* Default theme CSS variables */
:root {
    --color-primary: #4F46E5;
    --color-secondary: #10B981;
    --color-background: #FFFFFF;
    --color-text: #111827;
    --color-accent: #F59E0B;
    --font-heading: 'IBM Plex Sans', system-ui, sans-serif;
    --radius: 0.75rem;
}
```

**Visual Identity:**
- Minimalist approach
- Strict grid systems
- Classic typography
- Clean interactions

**Component Architecture:**
- Structured category filters
- Professional product display
- Clean visual hierarchy

### **3. Bold Theme**

**Characteristics:** Strong, distinctive interface

```css
/* Bold theme CSS variables */
:root {
    --color-primary: #0F172A;
    --color-secondary: #F59E0B;
    --color-background: #FFFFFF;
    --color-text: #0F172A;
    --color-accent: #EF4444;
    --font-heading: 'Inter', system-ui, sans-serif;
    --radius: 0.25rem;
}
```

**Visual Identity:**
- High contrast design
- Bold typography
- Sharp corners
- Strong visual hierarchy

**Component Architecture:**
- Impactful card designs
- Strong typography
- Dynamic interactions
- Premium feel

---

## Theme Development Workflow

### **1. Theme-Specific Page Implementation**

Each theme implements its own pages in the themes directory:

```bash
resources/themes/modern/store/products.blade.php      # Modern theme products
resources/themes/default/store/products.blade.php    # Default theme products
resources/themes/bold/store/products.blade.php       # Bold theme products
```

### **2. Component Reuse Across Themes**

Components can be shared within a theme but **never across themes**:

```blade
<!-- INCORRECT - Cross-theme sharing -->
@include('themes.modern.components.navbar')

<!-- CORRECT - Within theme only -->
@include('themes/modern/components/navbar')
```

### **3. Theme Configuration**

Each theme maintains its own configuration:

```php
// config/themes/modern.php
return [
    'colors' => [
        'primary' => '#8B5CF6',
        'secondary' => '#06B6D4',
        'background' => '#F9FAFB',
        'text' => '#1F2937',
        'accent' => '#F472B6',
    ],
    'typography' => [
        'heading' => 'Plus Jakarta Sans',
        'body' => 'Inter',
    ],
    'radius' => '0.75rem',
    'animations' => true,
];
```

---

## Theme-Specific Implementation Examples

### **Modern Theme Product Page**

```php
<!-- resources/themes/modern/store/products.blade.php -->
@extends('themes/modern/layout/app.blade.php')

@section('content')
    <section class="products-section">
        <!-- Modern category filter with gradient pills -->
        <div class="category-filters">
            @php $uniqueCategories = $products->pluck('category')->filter()->unique('id'); @endphp
            @if ($uniqueCategories->isNotEmpty())
                <div class="filter-pills-container">
                    <!-- Modern filter design with gradient effects -->
                </div>
            @endif
        </div>
        
        <!-- Premium products grid with soft shadows -->
        <div class="products-grid-container">
            <!-- Product cards with hover effects -->
        </div>
    </section>
@endsection
```

### **Default Theme Product Page**

```php
<!-- resources/themes/default/store/products.blade.php -->
@extends('themes/default/layout/app.blade.php')

@section('content')
    <section class="products-showcase">
        <!-- Default category filter with clean design -->
        <div class="simple-category-filters">
            <!-- Clean filtering interface -->
        </div>
        
        <!-- Structured products grid -->
        <div class="product-grid-layout">
            <!-- Professional product display -->
        </div>
    </section>
@endsection
```

### **Bold Theme Product Page**

```php
<!-- resources/themes/bold/store/products.blade.php -->
@extends('themes/bold/layout/app.blade.php')

@section('content')
    <section class="products-grid">
        <!-- Bold category filter with strong design -->
        <div class="impactful-filters">
            <!-- Strong visual hierarchy -->
        </div>
        
        <!-- High-contrast products grid -->
        <div class="bold-product-display">
            <!-- Eye-catching product showcase -->
        </div>
    </section>
@endsection
```

---

## Theme-Specific Component Examples

### **Modern Theme Button Component**

```blade
<!-- resources/themes/modern/components/button.blade.php -->
<div class="modern-button-container">
    <button class="modern-primary-btn">Primary Action</button>
    <button class="modern-secondary-btn">Secondary Action</button>
    <button class="modern-outline-btn">Outline Action</button>
</div>
```

### **Default Theme Button Component**

```blade
<!-- resources/themes/default/components/button.blade.php -->
<div class="default-button-container">
    <button class="default-primary-btn">Primary Action</button>
    <button class="default-secondary-btn">Secondary Action</button>
    <button class="default-outline-btn">Outline Action</button>
</div>
```

### **Bold Theme Button Component**

```blade
<!-- resources/themes/bold/components/button.blade.php -->
<div class="bold-button-container">
    <button class="bold-primary-btn">Primary Action</button>
    <button class="bold-secondary-btn">Secondary Action</button>
    <button class="bold-outline-btn">Outline Action</button>
</div>
```

---

## Legacy Theme Integration

These themes are **integrated into the existing project structure**:

### **Theme Routing System**

```php
// routes/web.php
Route::get('/products', [ProductController::class, 'index'])
    ->middleware(['theme']); // Theme middleware handles theme detection
```

### **Theme Detection Middleware**

```php
// app/Http/Middleware/ThemeMiddleware.php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ThemeMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Theme detection logic based on request parameters
        // or user preferences
        return $next($request);
    }
}
```

### **Current Active Theme Configuration**

```env
# .env
APP_THEME=modern
THEMES_AVAILABLE=modern,default,bold
```

```php
// config/app.php
return [
    'theme' => env('APP_THEME', 'modern'),
    'available_themes' => explode(',', env('THEMES_AVAILABLE', 'modern,default,bold')),
];
```

---

## Development Best Practices

### **1. Theme-Specific Development**

```bash
# Work within a specific theme
cd resources/themes/modern
# Edit theme-specific files
```

### **2. Theme Validation**

```bash
# Validate theme isolation
php artisan theme:validate modern

# Test theme performance
php artisan theme:test modern

# Check theme compatibility
php artisan theme:compatibility modern
```

### **3. Theme Deployment**

```bash
# Deploy theme changes
php artisan theme:deploy modern

# Backup theme
php artisan theme:backup modern

# Rollback theme
php artisan theme:rollback modern
```

---

## Theme-Specific Enhancement Opportunities

### **1. Modern Theme Enhancements**

- Add modern glassmorphism effects
- Implement smooth transitions and micro-interactions
- Create animated category filters
- Add dark mode support with theme-specific styles

### **2. Default Theme Enhancements**

- Implement strict grid systems
- Add professional spacing utilities
- Create modular component system
- Add accessibility features

### **3. Bold Theme Enhancements**

- Implement high contrast accessibility
- Add bold animation sequences
- Create impactful hover states
- Add dynamic content reveals

---

## Conclusion

The **existing themes architecture** provides a **complete theme system** where:

- Each theme is **standalone** with its own identity
- **Theme isolation** is maintained through clear directory separation
- **Consistent component structure** with theme-specific variations
- **Mobile-first development** is enforced across all themes
- **Integration** with existing Laravel application structure

This approach allows businesses to deploy different visual identities while sharing the same underlying technology stack, following the WordPress theme model with Laravel-specific patterns.

The existing themes provide the foundation for:

1. **Immediate deployment** with current functionality
2. **Feature expansion** for each theme
3. **Cross-theme collaboration** through shared backend
4. **User preference** for selecting preferred theme
5. **Performance optimization** per theme
