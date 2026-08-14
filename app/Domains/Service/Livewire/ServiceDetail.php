<?php

namespace App\Domains\Service\Livewire;

use App\Domains\Service\Actions\DeleteService;
use App\Models\Service;
use Livewire\Component;

class ServiceDetail extends Component
{
    public ?int $serviceId = null;

    public ?Service $service = null;

    public ?int $deleteId = null;

    public bool $showDeleteConfirm = false;

    public ?string $selectedImage = null;

    public function mount(int $serviceId): void
    {
        $this->serviceId = $serviceId;
        $this->service = Service::with('business')->findOrFail($serviceId);
        $this->authorize('view', $this->service);
        $this->selectedImage = $this->service->images[0] ?? null;
    }

    public function selectImage(string $path): void
    {
        $this->selectedImage = $path;
    }

    public function toggleActive(): void
    {
        $this->authorize('update', $this->service);

        $service = Service::findOrFail($this->serviceId);
        $service->update(['is_active' => ! $service->is_active]);
        $this->service = $service->fresh();
    }

    public function confirmDelete(): void
    {
        $this->deleteId = $this->serviceId;
        $this->showDeleteConfirm = true;
    }

    public function cancelDelete(): void
    {
        $this->deleteId = null;
        $this->showDeleteConfirm = false;
    }

    public function delete(DeleteService $deleteService): void
    {
        $service = Service::findOrFail($this->deleteId);
        $this->authorize('delete', $service);

        $deleteService($service);

        $this->redirect(route('dashboard.services'), navigate: true);
    }

    public function render()
    {
        return view('livewire.service.service-detail');
    }
}
