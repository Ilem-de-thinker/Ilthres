<?php

namespace App\Domains\Media\Livewire;

use App\Domains\Media\Actions\UploadMedia as UploadMediaAction;
use App\Models\Business;
use Livewire\Component;
use Livewire\WithFileUploads;

/**
 * Inline media upload form with business selector.
 *
 * Handles file selection, preview, plan-limit validation,
 * and triggers the UploadMedia action for the chosen business.
 */
class UploadMedia extends Component
{
    use WithFileUploads;

    public ?int $selectedBusinessId = null;
    public ?Business $business = null;
    public $file = null;
    public ?string $collection = null;
    public ?string $uploadError = null;

    protected function rules(): array
    {
        return [
            'file' => ['required', 'file', 'max:10240'],
            'collection' => ['nullable', 'string', 'max:50'],
        ];
    }

    protected $messages = [
        'file.required' => 'Select a file to upload.',
        'file.max' => 'File must be under 10MB.',
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

    public function updatedSelectedBusinessId($value): void
    {
        if (! $value) {
            $this->business = null;
            $this->selectedBusinessId = null;
            $this->file = null;
            $this->uploadError = null;
            return;
        }

        $this->loadBusiness((int) $value);
        $this->uploadError = null;
    }

    public function handleUpload(UploadMediaAction $uploadMedia): void
    {
        $this->validate();

        try {
            $uploadMedia($this->business, $this->file, $this->collection ?: null);

            $this->file = null;
            $this->collection = null;
            $this->uploadError = null;

            $this->dispatch('media-uploaded');

        } catch (\RuntimeException $e) {
            $this->uploadError = $e->getMessage();
        }
    }

    public function clearFile(): void
    {
        $this->file = null;
    }

    public function render()
    {
        return view('livewire.media.upload-media', [
            'businesses' => auth()->user()->businesses,
        ]);
    }
}
