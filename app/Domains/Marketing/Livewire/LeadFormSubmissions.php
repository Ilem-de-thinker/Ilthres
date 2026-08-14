<?php

namespace App\Domains\Marketing\Livewire;

use App\Domains\Marketing\Actions\UpdateLeadStatus;
use App\Models\Business;
use App\Models\LeadForm;
use App\Models\LeadSubmission;
use Livewire\Component;
use Livewire\WithPagination;

class LeadFormSubmissions extends Component
{
    use WithPagination;

    public ?Business $business = null;

    public ?int $formFilter = null;

    public string $statusFilter = '';

    public ?int $detailId = null;

    public function mount(): void
    {
        $user = auth()->user();
        $businesses = $user->businesses;
        if ($businesses->isEmpty()) {
            return;
        }
        $defaultId = session('current_business_id', $businesses->first()->id);
        $this->business = $businesses->find($defaultId) ?? $businesses->first();
    }

    public function viewDetail(int $id): void
    {
        $this->detailId = $id;
    }

    public function closeDetail(): void
    {
        $this->detailId = null;
    }

    public function updateStatus(int $id, string $status, UpdateLeadStatus $action): void
    {
        $submission = LeadSubmission::findOrFail($id);
        $action($submission, $status);
    }

    public function render()
    {
        if (! $this->business) {
            return view('livewire.marketing.lead-form-submissions', ['hasBusiness' => false]);
        }

        $forms = LeadForm::where('business_id', $this->business->id)->get();

        $query = LeadSubmission::where('business_id', $this->business->id)->with('form');

        if ($this->formFilter) {
            $query->where('lead_form_id', $this->formFilter);
        }
        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        $submissions = $query->latest()->paginate(15);

        $detail = null;
        if ($this->detailId) {
            $detail = LeadSubmission::with('form')->find($this->detailId);
        }

        return view('livewire.marketing.lead-form-submissions', [
            'hasBusiness' => true,
            'forms' => $forms,
            'submissions' => $submissions,
            'detail' => $detail,
        ]);
    }
}
