<?php

namespace App\Domains\Administration\Livewire;

use App\Domains\Business\Actions\ApproveVerification;
use App\Domains\Business\Actions\RejectVerification;
use App\Models\BusinessVerification;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class VerificationManager extends Component
{
    use WithPagination;

    public string $filterStatus = '';

    public ?int $reviewingId = null;

    public string $adminNotes = '';

    public string $rejectionReason = '';

    public function selectForReview(int $id): void
    {
        $verification = BusinessVerification::findOrFail($id);
        $verification->update(['status' => 'under_review']);

        $this->reviewingId = $id;
        $this->adminNotes = '';
        $this->rejectionReason = '';
    }

    public function approve(): void
    {
        $verification = BusinessVerification::findOrFail($this->reviewingId);

        app(ApproveVerification::class)(
            $verification,
            auth()->user(),
            $this->adminNotes ?: null,
        );

        $this->reviewingId = null;
        $this->adminNotes = '';
        session()->flash('success', 'Verification approved.');
    }

    public function reject(): void
    {
        $this->validate(['rejectionReason' => ['required', 'string', 'min:10']]);

        $verification = BusinessVerification::findOrFail($this->reviewingId);

        app(RejectVerification::class)(
            $verification,
            auth()->user(),
            $this->rejectionReason,
        );

        $this->reviewingId = null;
        $this->rejectionReason = '';

        session()->flash('success', 'Verification rejected.');
    }

    public function render()
    {
        $verifications = BusinessVerification::query()
            ->with(['business.user', 'reviewer'])
            ->when($this->filterStatus, fn ($q) => $q->where('status', $this->filterStatus))
            ->latest()
            ->paginate(20);

        return view('livewire.administration.verification-manager', [
            'verifications' => $verifications,
        ]);
    }

    public function getDocument(): void {}
}
