<?php

namespace App\Domains\Business\Livewire;

use App\Domains\Business\Actions\SubmitVerification;
use App\Models\Business;
use App\Models\BusinessVerification;
use Livewire\Component;
use Livewire\WithFileUploads;

class KycSettings extends Component
{
    use WithFileUploads;

    public ?Business $business = null;

    public $governmentId = null;

    public $businessRegistration = null;

    public $utilityBill = null;

    public $taxId = null;

    public ?string $successMessage = null;

    public ?string $errorMessage = null;

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

    public function submit(SubmitVerification $submitVerification): void
    {
        $this->validate();

        try {
            $files = array_filter([
                'government_id' => $this->governmentId,
                'business_registration' => $this->businessRegistration,
                'utility_bill' => $this->utilityBill,
                'tax_id' => $this->taxId,
            ]);

            $submitVerification($this->business, $files);

            $this->governmentId = null;
            $this->businessRegistration = null;
            $this->utilityBill = null;
            $this->taxId = null;

            session()->flash('success', 'Verification documents submitted successfully.');
        } catch (\Throwable $e) {
            session()->flash('error', 'Failed to submit verification documents.');
            report($e);
        }
    }

    protected function rules(): array
    {
        return [
            'governmentId' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:10240'],
            'businessRegistration' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:10240'],
            'utilityBill' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:10240'],
            'taxId' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:10240'],
        ];
    }

    public function render()
    {
        $verification = $this->business
            ? BusinessVerification::where('business_id', $this->business->id)->first()
            : null;

        return view('livewire.business.kyc-settings', [
            'verification' => $verification,
        ]);
    }
}
