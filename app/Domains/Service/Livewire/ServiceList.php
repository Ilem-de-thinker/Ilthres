<?php

namespace App\Domains\Service\Livewire;

use App\Domains\Service\Actions\DeleteService;
use App\Models\Business;
use App\Models\Service;
use Livewire\Component;
use Livewire\WithPagination;

class ServiceList extends Component
{
    use WithPagination;

    public ?int $selectedBusinessId = null;

    public ?Business $business = null;

    public string $search = '';

    public ?string $statusFilter = null;

    public ?int $deleteId = null;

    public bool $showDeleteConfirm = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
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

            return;
        }

        $this->loadBusiness((int) $value);
        $this->resetPage();
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

    public function delete(DeleteService $deleteService): void
    {
        $service = Service::findOrFail($this->deleteId);
        $this->authorize('delete', $service);

        $deleteService($service);

        $this->cancelDelete();
    }

    public function render()
    {
        $businesses = auth()->user()->businesses;

        $services = null;
        if ($this->business) {
            $query = Service::where('business_id', $this->business->id);

            if ($this->statusFilter === 'active') {
                $query->where('is_active', true);
            } elseif ($this->statusFilter === 'inactive') {
                $query->where('is_active', false);
            }

            if ($this->search) {
                $query->where(function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                        ->orWhere('description', 'like', "%{$this->search}%");
                });
            }

            $services = $query->orderBy('sort_order')->orderBy('created_at', 'desc')->paginate(24);
        }

        return view('livewire.service.service-list', [
            'businesses' => $businesses,
            'services' => $services,
        ]);
    }
}
