# 02 — Website Pages & Sections

> Structured content blocks. Merchants control what renders where on their site.

## What Already Exists

Themes render hardcoded sections (hero, about, services, products, contact). The content comes directly from the business model fields.

## What This Phase Adds

Pages and sections give merchants **control** — they can reorder, hide, rename, or customize each section without touching code.

### 1. Website Pages

```sql
CREATE TABLE website_pages (
    id              BIGSERIAL PRIMARY KEY,
    website_id      BIGINT REFERENCES websites(id) ON DELETE CASCADE,
    slug            VARCHAR(100) NOT NULL,     -- "home", "about", "services", "contact"
    title           VARCHAR(255) NOT NULL,
    meta_description TEXT,
    is_visible      BOOLEAN DEFAULT TRUE,
    sort_order      INTEGER DEFAULT 0,
    UNIQUE(website_id, slug)
);
```

### 2. Website Sections

```sql
CREATE TABLE website_sections (
    id              BIGSERIAL PRIMARY KEY,
    page_id         BIGINT REFERENCES website_pages(id) ON DELETE CASCADE,
    type            VARCHAR(50) NOT NULL,       -- hero, about, services, products, gallery,
                                                -- testimonials, contact, map, faq, cta
    content         JSONB NOT NULL,             -- structured data per type
    sort_order      INTEGER DEFAULT 0,
    is_visible      BOOLEAN DEFAULT TRUE,
    created_at      TIMESTAMP DEFAULT NOW(),
    updated_at      TIMESTAMP DEFAULT NOW()
);
```

**Section content examples:**

```json
// hero
{
    "heading": "Welcome to John's Bakery",
    "subheading": "Fresh bread daily since 1995",
    "background_image": "...",
    "cta_text": "Order Now",
    "cta_link": "#order"
}

// about
{
    "heading": "About Us",
    "body": "We are a family-owned bakery...",
    "image": "..."
}

// services
{
    "heading": "Our Services",
    "show_pricing": true,
    "layout": "grid"
}

// faq
{
    "heading": "Frequently Asked Questions",
    "items": [
        {"question": "Do you deliver?", "answer": "Yes, within 5km..."},
        {"question": "What are your hours?", "answer": "Mon-Sat 7am-8pm"}
    ]
}

// contact
{
    "heading": "Get In Touch",
    "phone": "+234...",
    "email": "...",
    "whatsapp": "+234...",
    "show_map": true,
    "address": "123 Main St, Lagos"
}
```

**Files to create:**
- `database/migrations/xxxx_create_website_pages_table.php`
- `database/migrations/xxxx_create_website_sections_table.php`
- `app/Models/WebsitePage.php` — belongsTo Website, hasMany Sections
- `app/Models/WebsiteSection.php` — belongsTo WebsitePage
- `app/Domains/Website/Actions/CreatePage.php`
- `app/Domains/Website/Actions/UpdatePage.php`
- `app/Domains/Website/Actions/ReorderPages.php`
- `app/Domains/Website/Actions/CreateSection.php`
- `app/Domains/Website/Actions/UpdateSection.php`
- `app/Domains/Website/Actions/ReorderSections.php`
- `app/Domains/Website/Events/PageCreated.php`
- `app/Domains/Website/Events/SectionUpdated.php`

### 3. Auto-Create Default Pages

In `InitializeBusinessSettings` listener, after creating website:

```php
// Create default pages
$homePage = $website->pages()->create(['slug' => 'home', 'title' => 'Home', 'sort_order' => 1]);
$aboutPage = $website->pages()->create(['slug' => 'about', 'title' => 'About', 'sort_order' => 2]);
$contactPage = $website->pages()->create(['slug' => 'contact', 'title' => 'Contact', 'sort_order' => 3]);

// Create default sections from business data
$homePage->sections()->create([
    'type' => 'hero',
    'content' => [
        'heading' => $business->name,
        'subheading' => $business->description ?? '',
        'background_image' => $business->cover,
        'cta_text' => 'Contact Us',
        'cta_link' => '#contact',
    ],
    'sort_order' => 1,
]);

$homePage->sections()->create([
    'type' => 'about',
    'content' => [
        'heading' => 'About ' . $business->name,
        'body' => $business->about ?? $business->description ?? '',
        'image' => $business->logo,
    ],
    'sort_order' => 2,
]);

// Services/Products section (dynamic — loads from business data)
$homePage->sections()->create([
    'type' => $business->isProductStore() ? 'products' : 'services',
    'content' => ['heading' => $business->isProductStore() ? 'Our Products' : 'Our Services', 'show_pricing' => true],
    'sort_order' => 3,
]);

$homePage->sections()->create([
    'type' => 'contact',
    'content' => [
        'heading' => 'Get In Touch',
        'phone' => $business->phone,
        'email' => $business->email,
        'whatsapp' => $business->whatsapp,
        'show_map' => true,
    ],
    'sort_order' => 4,
]);
```

### 4. Theme Integration

Themes continue to work as-is. Pages/sections are an **optional data layer**.

Themes that want to use sections can read from `$sections`:
```php
{{-- In theme layout --}}
@if(isset($sections) && $sections->count())
    @foreach($sections->where('is_visible', true)->sortBy('sort_order') as $section)
        @include("themes::{$slug}.components.{$section->type}", ['data' => $section->content])
    @endforeach
@else
    {{-- Fallback: render from business data directly (existing behavior) --}}
@endif
```

Themes that don't use sections continue rendering from business data unchanged.

---

## Checklist

- [ ] Migration: `website_pages` table
- [ ] Migration: `website_sections` table
- [ ] Model: `WebsitePage`
- [ ] Model: `WebsiteSection`
- [ ] Actions: CreatePage, UpdatePage, ReorderPages, CreateSection, UpdateSection, ReorderSections
- [ ] Events: PageCreated, SectionUpdated
- [ ] Update `InitializeBusinessSettings` to create default pages + sections
- [ ] Update theme blade files to optionally render from `$sections`
- [ ] Livewire: `PageManager` (list, create, edit, reorder)
- [ ] Livewire: `SectionEditor` (add/edit/reorder sections per page)
- [ ] Views: `page-manager.blade.php`, `section-editor.blade.php`
- [ ] Routes: `/dashboard/website/pages`, `/dashboard/website/pages/{page}/edit`
