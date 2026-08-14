<?php

namespace App\Domains\Booking\Livewire;

use App\Models\Booking;
use App\Models\BookingSlot;
use App\Models\Business;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class BookingManager extends Component
{
    use WithPagination;

    public Business $business;

    public $statusFilter = 'all';

    public $dateFilter = '';

    public $searchQuery = '';

    public $selectedBooking = null;

    public $cancelReason = '';

    protected $rules = [
        'cancelReason' => 'required|string|max:500',
    ];

    public function mount(): void
    {
        $this->business = Auth::user()->business;
    }

    public function getBookingsProperty()
    {
        $query = Booking::where('business_id', $this->business->id)
            ->with(['service', 'user']);

        // Status filter
        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        // Date filter
        if ($this->dateFilter) {
            $query->where('booking_date', $this->dateFilter);
        }

        // Search filter
        if ($this->searchQuery) {
            $query->where(function ($q) {
                $q->where('customer_name', 'like', "%{$this->searchQuery}%")
                    ->orWhere('customer_phone', 'like', "%{$this->searchQuery}%")
                    ->orWhere('customer_email', 'like', "%{$this->searchQuery}%");
            });
        }

        return $query->orderBy('booking_date', 'desc')
            ->orderBy('booking_time', 'desc')
            ->paginate(10);
    }

    public function viewBooking($bookingId): void
    {
        $this->selectedBooking = Booking::with(['service', 'user'])->find($bookingId);
    }

    public function updateStatus($bookingId, $status): void
    {
        $booking = Booking::find($bookingId);
        if (! $booking || $booking->business_id !== $this->business->id) {
            return;
        }

        $validTransitions = [
            Booking::STATUS_PENDING => [Booking::STATUS_CONFIRMED, Booking::STATUS_CANCELLED],
            Booking::STATUS_CONFIRMED => [Booking::STATUS_IN_PROGRESS, Booking::STATUS_CANCELLED],
            Booking::STATUS_IN_PROGRESS => [Booking::STATUS_COMPLETED],
        ];

        $allowed = $validTransitions[$booking->status] ?? [];
        if (! in_array($status, $allowed)) {
            session()->flash('error', 'Invalid status transition.');

            return;
        }

        $booking->update(['status' => $status]);

        // If cancelled, release the slot
        if ($status === Booking::STATUS_CANCELLED) {
            $slot = BookingSlot::where('date', $booking->booking_date)
                ->where('start_time', $booking->booking_time)
                ->first();
            if ($slot) {
                $slot->cancel();
            }
        }

        session()->flash('success', "Booking status updated to {$status}.");
    }

    public function cancelBooking($bookingId): void
    {
        $this->validate();

        $booking = Booking::find($bookingId);
        if (! $booking || $booking->business_id !== $this->business->id) {
            return;
        }

        if (! $booking->canBeCancelled()) {
            session()->flash('error', 'This booking cannot be cancelled.');

            return;
        }

        $booking->update([
            'status' => Booking::STATUS_CANCELLED,
            'notes' => $booking->notes ? $booking->notes."\n\nCancellation reason: {$this->cancelReason}" : "Cancellation reason: {$this->cancelReason}",
        ]);

        // Release the slot
        $slot = BookingSlot::where('date', $booking->booking_date)
            ->where('start_time', $booking->booking_time)
            ->first();
        if ($slot) {
            $slot->cancel();
        }

        $this->cancelReason = '';
        $this->selectedBooking = null;
        session()->flash('success', 'Booking has been cancelled.');
    }

    public function render()
    {
        return view('livewire.booking.booking-manager');
    }
}
