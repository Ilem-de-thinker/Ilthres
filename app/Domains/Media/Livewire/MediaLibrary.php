<?php

namespace App\Domains\Media\Livewire;

use App\Domains\Media\Actions\DeleteMedia;
use App\Models\Business;
use App\Models\Media;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Media library grid — lists all media for the selected business.
 *
 * Includes a business selector at the top. Supports collection
 * filtering, search, pagination, and delete with confirmation.
 */
class MediaLibrary extends Component
{
    use WithPagination;

    public ?int $selectedBusinessId = null;
    public ?Business $business = null;

    public ?string $collectionFilter = null;
    public string $search = '';
    public ?int $deleteId = null;
    public bool $showDeleteConfirm = false;
    public ?int $expandedId = null;
    public ?Media $expandedMedia = null;
    public bool $showExpanded = false;

    protected $queryString = [
        'collectionFilter' => ['except' => ''],
        'search' => ['except' => ''],
    ];

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
        $this->business = auth()->user()->businesses()->findOrFail($businessId);
        $this->selectedBusinessId = $this->business->id;
    }

    public function updatedSelectedBusinessId(int $value): void
    {
        $this->loadBusiness($value);
        $this->resetPage();
    }

    public function getCollectionsProperty()
    {
        if (! $this->business) {
            return collect();
        }

        return Media::where('business_id', $this->business->id)
            ->whereNotNull('collection')
            ->select('collection')
            ->distinct()
            ->pluck('collection');
    }

    public function confirmDelete(int $id): void
    {
        $this->deleteId = $id;
        $this->showDeleteConfirm = true;
    }

    public function cancelDelete(): void
    {
        $this->deleteId = null;
        $this->showDeleteConfirm = false;
    }

    #[On('media-uploaded')]
    public function refreshAfterUpload(): void
    {
        // Grid will re-render automatically.
    }

    public function expandMedia(int $id): void
    {
        $media = Media::where('business_id', $this->business->id)->findOrFail($id);
        $this->authorize('view', $media);

        $this->expandedId = $id;
        $this->expandedMedia = $media;
        $this->showExpanded = true;
    }

    public function closeExpanded(): void
    {
        $this->expandedId = null;
        $this->expandedMedia = null;
        $this->showExpanded = false;
    }

    public function delete(DeleteMedia $deleteMedia): void
    {
        $media = Media::findOrFail($this->deleteId);
        $this->authorize('delete', $media);

        $deleteMedia($media);

        $this->cancelDelete();
        $this->dispatch('media-deleted');
    }

    public function render()
    {
        $businesses = auth()->user()->businesses;
        $planSlug = $this->business?->subscription?->plan?->slug ?? 'free';
        $maxMedia = config("plans.{$planSlug}.max_media", 0);
        $usedMedia = $this->business ? Media::where('business_id', $this->business->id)->count() : 0;

        $mediaItems = null;
        if ($this->business) {
            $query = Media::where('business_id', $this->business->id);

            if ($this->collectionFilter) {
                $query->where('collection', $this->collectionFilter);
            }

            if ($this->search) {
                $query->where(function ($q) {
                    $q->where('original_name', 'like', "%{$this->search}%")
                      ->orWhere('collection', 'like', "%{$this->search}%");
                });
            }

            $mediaItems = $query->orderBy('created_at', 'desc')->paginate(24);
        }

        return view('livewire.media.media-library', [
            'businesses' => $businesses,
            'mediaItems' => $mediaItems,
            'maxMedia' => $maxMedia,
            'usedMedia' => $usedMedia,
            'collections' => $this->collections,
        ]);
    }
}
