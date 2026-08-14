<?php

namespace App\Domains\Administration\Livewire;

use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class CategoryManager extends Component
{
    use WithPagination;

    public string $name = '';

    public string $icon = '';

    public ?int $editingCategoryId = null;

    public bool $showForm = false;

    public ?string $flashSuccess = null;

    public ?string $flashError = null;

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:50'],
        ];
    }

    private function success(string $message): void
    {
        $this->flashSuccess = $message;
        $this->flashError = null;
    }

    private function error(string $message): void
    {
        $this->flashError = $message;
        $this->flashSuccess = null;
    }

    public function create(): void
    {
        $this->validate();

        Category::create([
            'name' => $this->name,
            'slug' => Str::slug($this->name),
            'icon' => $this->icon ?: null,
            'sort_order' => Category::max('sort_order') + 1,
        ]);

        $this->reset(['name', 'icon', 'showForm']);
        $this->success('Category created successfully.');
    }

    public function edit(Category $category): void
    {
        $this->editingCategoryId = $category->id;
        $this->name = $category->name;
        $this->icon = $category->icon ?? '';
        $this->showForm = true;
    }

    public function update(): void
    {
        $this->validate();

        $category = Category::findOrFail($this->editingCategoryId);
        $category->update([
            'name' => $this->name,
            'slug' => Str::slug($this->name),
            'icon' => $this->icon ?: null,
        ]);

        $this->reset(['name', 'icon', 'editingCategoryId', 'showForm']);
        $this->success('Category updated successfully.');
    }

    public function toggleActive(int $categoryId): void
    {
        $category = Category::findOrFail($categoryId);
        $category->update(['is_active' => ! $category->is_active]);

        $this->success("Category \"{$category->name}\" has been ".($category->is_active ? 'enabled' : 'disabled').'.');
    }

    public function delete(int $categoryId): void
    {
        $category = Category::findOrFail($categoryId);

        if ($category->businesses()->exists() || $category->products()->exists() || $category->services()->exists()) {
            $this->error("Cannot delete \"{$category->name}\" — it has businesses, products, or services attached.");

            return;
        }

        $category->delete();
        $this->success("Category \"{$category->name}\" deleted.");
    }

    public function cancel(): void
    {
        $this->reset(['name', 'icon', 'editingCategoryId', 'showForm']);
    }

    public function render()
    {
        $categories = Category::orderBy('sort_order')->paginate(20);

        return view('livewire.administration.category-manager', [
            'categories' => $categories,
        ]);
    }
}
