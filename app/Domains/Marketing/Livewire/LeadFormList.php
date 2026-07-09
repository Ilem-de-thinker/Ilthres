<?php

namespace App\Domains\Marketing\Livewire;

use App\Domains\Marketing\Actions\CreateLeadForm;
use App\Domains\Marketing\Actions\DeleteLeadForm;
use App\Models\Business;
use App\Models\LeadForm;
use App\Models\LeadFormField;
use Livewire\Component;

class LeadFormList extends Component
{
    public ?Business $business = null;
    public ?int $deleteId = null;
    public bool $showDeleteConfirm = false;

    public function mount(): void
    {
        $user = auth()->user();
        $businesses = $user->businesses;
        if ($businesses->isEmpty()) { return; }
        $defaultId = session('current_business_id', $businesses->first()->id);
        $this->business = $businesses->find($defaultId) ?? $businesses->first();
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

    public function delete(DeleteLeadForm $action): void
    {
        $form = LeadForm::findOrFail($this->deleteId);
        $action($form);
        $this->cancelDelete();
    }

    public function render()
    {
        if (! $this->business) {
            return view('livewire.marketing.lead-form-list', ['hasBusiness' => false]);
        }

        $forms = LeadForm::where('business_id', $this->business->id)
            ->withCount('submissions')
            ->withCount('fields')
            ->latest()
            ->get();

        return view('livewire.marketing.lead-form-list', [
            'hasBusiness' => true,
            'forms' => $forms,
        ]);
    }
}
