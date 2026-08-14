<?php

namespace App\Domains\Website\Livewire;

use App\Domains\Website\Actions\CreateSection;
use App\Domains\Website\Actions\ReorderSections;
use App\Domains\Website\Actions\UpdateSection;
use App\Models\Business;
use App\Models\WebsitePage;
use App\Models\WebsiteSection;
use Livewire\Component;

class SectionEditor extends Component
{
    public ?int $selectedBusinessId = null;

    public ?Business $business = null;

    public ?WebsitePage $page = null;

    public string $newSectionType = 'text';

    public string $newSectionTitle = '';

    public ?int $editingSectionId = null;

    public array $editData = [];

    public string $editTitle = '';

    public ?string $successMessage = null;

    public ?string $errorMessage = null;

    public array $sectionTypes = [
        'hero' => 'Hero',
        'about' => 'About',
        'products' => 'Products',
        'services' => 'Services',
        'contact' => 'Contact',
        'faq' => 'FAQ',
        'gallery' => 'Gallery',
        'testimonials' => 'Testimonials',
        'cta' => 'Call to Action',
        'text' => 'Text Block',
        'map' => 'Map',
    ];

    public function mount(int $pageId): void
    {
        $this->page = WebsitePage::with('sections')->findOrFail($pageId);
        $this->business = $this->page->website->business;
        $this->selectedBusinessId = $this->business->id;

        $this->authorize('update', $this->business);
    }

    public function addSection(): void
    {
        $this->validate([
            'newSectionType' => ['required', 'string', 'in:hero,about,products,services,contact,faq,gallery,testimonials,cta,text,map'],
            'newSectionTitle' => ['nullable', 'string', 'max:255'],
        ]);

        try {
            app(CreateSection::class)($this->page, [
                'type' => $this->newSectionType,
                'title' => $this->newSectionTitle ?: null,
                'data' => $this->getDefaultData($this->newSectionType),
            ]);

            $this->page->load('sections');
            $this->newSectionTitle = '';
            $this->successMessage = 'Section added.';
        } catch (\Throwable $e) {
            $this->errorMessage = 'Failed to add section.';
            report($e);
        }
    }

    public function startEditing(WebsiteSection $section): void
    {
        $this->editingSectionId = $section->id;
        $this->editTitle = $section->title ?? '';
        $this->editData = $section->data ?? [];
    }

    public function cancelEditing(): void
    {
        $this->editingSectionId = null;
        $this->editData = [];
    }

    public function saveSection(WebsiteSection $section): void
    {
        try {
            app(UpdateSection::class)($section, [
                'title' => $this->editTitle ?: null,
                'data' => $this->editData,
            ]);

            $this->editingSectionId = null;
            $this->page->load('sections');
            $this->successMessage = 'Section updated.';
        } catch (\Throwable $e) {
            $this->errorMessage = 'Failed to update section.';
            report($e);
        }
    }

    public function toggleSection(WebsiteSection $section): void
    {
        app(UpdateSection::class)($section, ['is_active' => ! $section->is_active]);
        $this->page->load('sections');
    }

    public function deleteSection(WebsiteSection $section): void
    {
        try {
            $section->delete();
            $this->page->load('sections');
            $this->successMessage = 'Section deleted.';
        } catch (\Throwable $e) {
            $this->errorMessage = 'Failed to delete section.';
            report($e);
        }
    }

    public function moveSectionUp(WebsiteSection $section): void
    {
        $sections = $this->page->sections()->orderBy('sort_order')->get();
        $ids = $sections->pluck('id')->values();
        $index = $ids->search($section->id);
        if ($index > 0) {
            $arr = $ids->toArray();
            [$arr[$index], $arr[$index - 1]] = [$arr[$index - 1], $arr[$index]];
            app(ReorderSections::class)($this->page, $arr);
            $this->page->load('sections');
        }
    }

    public function moveSectionDown(WebsiteSection $section): void
    {
        $sections = $this->page->sections()->orderBy('sort_order')->get();
        $ids = $sections->pluck('id')->values();
        $index = $ids->search($section->id);
        if ($index < $ids->count() - 1) {
            $arr = $ids->toArray();
            [$arr[$index], $arr[$index + 1]] = [$arr[$index + 1], $arr[$index]];
            app(ReorderSections::class)($this->page, $arr);
            $this->page->load('sections');
        }
    }

    private function getDefaultData(string $type): array
    {
        return match ($type) {
            'hero' => ['heading' => '', 'subheading' => '', 'cta_text' => '', 'cta_link' => ''],
            'about' => ['heading' => '', 'body' => '', 'image' => ''],
            'products', 'services' => ['heading' => '', 'show_pricing' => true, 'layout' => 'grid'],
            'contact' => ['heading' => '', 'phone' => '', 'email' => '', 'whatsapp' => '', 'show_map' => true],
            'faq' => ['heading' => '', 'items' => []],
            'gallery' => ['heading' => '', 'images' => []],
            'testimonials' => ['heading' => '', 'items' => []],
            'cta' => ['heading' => '', 'body' => '', 'button_text' => '', 'button_link' => ''],
            'text' => ['heading' => '', 'body' => ''],
            'map' => ['heading' => '', 'address' => '', 'lat' => null, 'lng' => null],
            default => [],
        };
    }

    public function render()
    {
        return view('livewire.website.section-editor', [
            'sections' => $this->page ? $this->page->sections()->orderBy('sort_order')->get() : collect(),
        ]);
    }
}
