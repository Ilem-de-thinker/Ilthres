<?php

namespace App\Domains\Website\Livewire;

use App\Domains\Website\Actions\CreatePage;
use App\Domains\Website\Actions\ReorderPages;
use App\Domains\Website\Actions\UpdatePage;
use App\Models\Business;
use App\Models\Website;
use App\Models\WebsitePage;
use Illuminate\Support\Str;
use Livewire\Component;

class PageManager extends Component
{
    public ?int $selectedBusinessId = null;

    public ?Business $business = null;

    public ?Website $website = null;

    public string $newPageTitle = '';

    public string $newPageSlug = '';

    public ?int $editingPageId = null;

    public string $editTitle = '';

    public string $editSlug = '';

    public ?string $successMessage = null;

    public ?string $errorMessage = null;

    public function mount(): void
    {
        $businesses = auth()->user()->businesses;
        if ($businesses->isEmpty()) {
            return;
        }
        $defaultId = session('current_business_id', $businesses->first()->id);
        $this->loadBusiness($defaultId);
    }

    public function loadBusiness(int $businessId): void
    {
        $business = auth()->user()->businesses()->findOrFail($businessId);
        $this->authorize('update', $business);

        $this->business = $business;
        $this->selectedBusinessId = $business->id;
        $this->website = $business->website;
        $this->editingPageId = null;
        $this->successMessage = null;
        $this->errorMessage = null;
    }

    public function updatedSelectedBusinessId(int $value): void
    {
        $this->loadBusiness($value);
    }

    public function addPage(): void
    {
        $this->validate([
            'newPageTitle' => ['required', 'string', 'max:255'],
            'newPageSlug' => ['required', 'string', 'max:100', 'regex:/^[a-z0-9-]+$/'],
        ]);

        if (! $this->website) {
            $this->errorMessage = 'No website found.';

            return;
        }

        try {
            app(CreatePage::class)($this->website, [
                'title' => $this->newPageTitle,
                'slug' => $this->newPageSlug,
            ]);

            $this->newPageTitle = '';
            $this->newPageSlug = '';
            $this->website->load('pages');
            $this->successMessage = 'Page created.';
        } catch (\Throwable $e) {
            $this->errorMessage = 'Failed to create page.';
            report($e);
        }
    }

    public function startEditing(WebsitePage $page): void
    {
        $this->editingPageId = $page->id;
        $this->editTitle = $page->title;
        $this->editSlug = $page->slug;
    }

    public function cancelEditing(): void
    {
        $this->editingPageId = null;
    }

    public function savePage(WebsitePage $page): void
    {
        $this->validate([
            'editTitle' => ['required', 'string', 'max:255'],
            'editSlug' => ['required', 'string', 'max:100', 'regex:/^[a-z0-9-]+$/'],
        ]);

        try {
            app(UpdatePage::class)($page, [
                'title' => $this->editTitle,
                'slug' => $this->editSlug,
            ]);

            $this->editingPageId = null;
            $this->website->load('pages');
            $this->successMessage = 'Page updated.';
        } catch (\Throwable $e) {
            $this->errorMessage = 'Failed to update page.';
            report($e);
        }
    }

    public function togglePage(WebsitePage $page): void
    {
        app(UpdatePage::class)($page, ['is_active' => ! $page->is_active]);
        $this->website->load('pages');
    }

    public function deletePage(WebsitePage $page): void
    {
        try {
            $page->delete();
            $this->website->load('pages');
            $this->successMessage = 'Page deleted.';
        } catch (\Throwable $e) {
            $this->errorMessage = 'Failed to delete page.';
            report($e);
        }
    }

    public function movePageUp(WebsitePage $page): void
    {
        $pages = $this->website->pages()->orderBy('sort_order')->get();
        $ids = $pages->pluck('id')->values();
        $index = $ids->search($page->id);
        if ($index > 0) {
            $arr = $ids->toArray();
            [$arr[$index], $arr[$index - 1]] = [$arr[$index - 1], $arr[$index]];
            app(ReorderPages::class)($this->website, $arr);
            $this->website->load('pages');
        }
    }

    public function movePageDown(WebsitePage $page): void
    {
        $pages = $this->website->pages()->orderBy('sort_order')->get();
        $ids = $pages->pluck('id')->values();
        $index = $ids->search($page->id);
        if ($index < $ids->count() - 1) {
            $arr = $ids->toArray();
            [$arr[$index], $arr[$index + 1]] = [$arr[$index + 1], $arr[$index]];
            app(ReorderPages::class)($this->website, $arr);
            $this->website->load('pages');
        }
    }

    public function updatedNewPageSlug(string $value): void
    {
        if (empty($this->newPageSlug) && ! empty($this->newPageTitle)) {
            $this->newPageSlug = Str::slug($this->newPageTitle);
        }
    }

    public function render()
    {
        return view('livewire.website.page-manager', [
            'businesses' => auth()->user()->businesses,
            'pages' => $this->website ? $this->website->pages()->orderBy('sort_order')->get() : collect(),
        ]);
    }
}
