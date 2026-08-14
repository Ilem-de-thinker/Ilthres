# Stitch OmniBusiness Theme Architecture

## Role

You are an expert Laravel 12, Blade, Tailwind CSS, Alpine.js, and UI architecture engineer.

You are integrating the **Stitch OmniBusiness Template System** into this Laravel project.

This project is **theme-based**, similar to WordPress.

---

# Fundamental Rule

The folder

```text
stitch_omnibusiness_template_system/
```

contains the complete design system.

Each folder inside it represents an **independent theme**.

Every theme is its own UI system.

Treat every theme as a separate product.

---

# Theme Isolation (Highest Priority)

Themes are completely isolated.

A theme must NEVER:

* Reuse another theme's layouts
* Import another theme's Blade components
* Share CSS
* Share JavaScript
* Share HTML structure
* Share partials
* Share icons
* Share navigation
* Share cards
* Share forms
* Share buttons
* Share headers
* Share footers

Every theme owns its own implementation.

Even if two themes contain similar components, create them separately.

Do not deduplicate designs across themes.

Design isolation is more important than reducing duplicate code.

---

# Theme Directory Structure

Each theme must have its own complete directory.

Example

```text
resources/views/themes/

    heritage_luxury/
        layouts/
        components/
        partials/
        pages/
        store/
        services/
        blog/
        auth/
        account/

    neo_organic/
        layouts/
        components/
        partials/
        pages/
        store/
        services/
        blog/
        auth/
        account/

    phosphor_grid_1/
        layouts/
        components/
        partials/
        pages/
        store/
        services/
        blog/
        auth/
        account/

    serene_ivory/
        layouts/
        components/
        partials/
        pages/
        store/
        services/
        blog/
        auth/
        account/
```

Every theme must be self-contained.

---

# Theme Components

Every theme creates its own components.

Example

```text
themes/

    heritage_luxury/
        components/
            button.blade.php
            card.blade.php
            navbar.blade.php
            footer.blade.php
            sidebar.blade.php
            modal.blade.php
            alert.blade.php
            breadcrumb.blade.php
            input.blade.php
            select.blade.php
            textarea.blade.php
            pagination.blade.php
            hero.blade.php
            product-card.blade.php
            service-card.blade.php

    neo_organic/
        components/
            button.blade.php
            card.blade.php
            navbar.blade.php
            footer.blade.php
            ...
```

These components are independent.

Never import a component from another theme.

---

# Layouts

Every theme owns its layouts.

Example

```text
themes/

    heritage_luxury/
        layouts/
            app.blade.php
            store.blade.php
            service.blade.php
            auth.blade.php

    neo_organic/
        layouts/
            app.blade.php
            store.blade.php
            service.blade.php
            auth.blade.php
```

Layouts are never shared.

---

# Assets

Each theme has its own assets.

Example

```text
resources/

    themes/

        heritage_luxury/
            css/
            js/
            images/
            icons/
            fonts/

        neo_organic/
            css/
            js/
            images/
            icons/
            fonts/
```

Never import another theme's assets.

---

# Design Fidelity

The folder inside `stitch_omnibusiness_template_system` is the source of truth.

Preserve exactly:

* Layout
* Typography
* White space
* Colors
* Grid
* Cards
* Buttons
* Forms
* Header
* Footer
* Navigation
* Animations
* Icons
* Shadows
* Borders
* Responsive behavior

Never redesign.

Never modernize.

Never simplify.

Never combine themes.

---

# Theme Mapping

When building a page, only use templates from the active theme.

Example

If the active theme is

```text
neo_organic
```

Then all pages must come from

```text
neo_organic
store_home_neo_organic
store_products_neo_organic
store_product_detail_neo_organic
service_detail_neo_organic
```

Never mix

```text
neo_organic
```

with

```text
serene_ivory
```

or

```text
phosphor_grid
```

If the active theme is

```text
heritage_luxury
```

Use

```text
heritage_luxury
service_home_heritage_luxury
```

only.

The selected theme controls the appearance of the entire website.

---

# Mobile-First Development

Every theme must be built mobile-first.

Develop in this order

* Mobile
* Tablet
* Laptop
* Desktop
* Large Desktop

Never design desktop first.

---

# Blade Standards

Use Blade properly inside each theme.

Examples

* Theme-specific layouts
* Theme-specific components
* Theme-specific partials
* Theme-specific slots
* Theme-specific includes

Do not reference Blade files from another theme.

---

# If a Page Does Not Exist

If a required page is missing:

1. Stay inside the active theme.
2. Find the closest page in that same theme.
3. Extend its design language.
4. Reuse only that theme's components.
5. Preserve the same spacing, typography, and visual hierarchy.

Never borrow from another theme.

---

# Final Validation Checklist

Before completing any task, confirm:

✓ The page belongs only to the active theme.

✓ No Blade component from another theme is referenced.

✓ No shared layouts are used.

✓ No shared CSS or JavaScript is used.

✓ The page faithfully reproduces the Stitch template.

✓ Mobile-first implementation.

✓ Fully responsive.

✓ Tailwind CSS only.

✓ Accessible.

✓ Laravel Blade best practices.

If there is ever a conflict between code reuse and theme isolation, **theme isolation always wins**. Each theme must remain a complete, independent implementation, just like a standalone WordPress theme.