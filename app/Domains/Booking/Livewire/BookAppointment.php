<?php

namespace App\Domains\Booking\Livewire;

use App\Models\Booking;
use App\Models\BookingSlot;
use App\Models\Business;
use App\Models\Service;
use App\Models\ServiceAvailability;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class BookAppointment extends Component
{
    public Business $business;

    public Service $service;

    public $selectedDate = null;

    public $selectedSlot = null;

    public $availableSlots = [];

    public $customerName = '';

    public $customerPhone = '';

    public $customerEmail = '';

    public $notes = '';

    public $step = 1; // 1: select date, 2: select time, 3: fill info, 4: confirm

    protected $rules = [
        'selectedDate' => 'required|date|after_or_equal:today',
        'selectedSlot' => 'required',
        'customerName' => 'required|string|max:255',
        'customerPhone' => 'required|string|max:20',
        'customerEmail' => 'nullable|email|max:255',
        'notes' => 'nullable|string|max:1000',
    ];

    protected $messages = [
        'selectedDate.required' => 'Please select a date.',
        'selectedDate.after_or_equal' => 'Please select a future date.',
        'selectedSlot.required' => 'Please select a time slot.',
        'customerName.required' => 'Please enter your name.',
        'customerPhone.required' => 'Please enter your phone number.',
        'customerEmail.email' => 'Please enter a valid email address.',
    ];

    public function mount(Business $business, Service $service): void
    {
        $this->business = $business;
        $this->service = $service;

        // Pre-fill from session if logged in
        if (auth()->check()) {
            $user = auth()->user();
            $this->customerName = $user->name;
            $this->customerPhone = $user->phone ?? '';
            $this->customerEmail = $user->email;
        }
    }

    public function updatedSelectedDate(): void
    {
        $this->selectedSlot = null;
        $this->loadAvailableSlots();
    }

    public function loadAvailableSlots(): void
    {
        if (! $this->selectedDate) {
            $this->availableSlots = [];

            return;
        }

        $date = Carbon::parse($this->selectedDate);
        $dayOfWeek = strtolower($date->format('D')); // mon, tue, etc.

        // Get availability for this day
        $availability = ServiceAvailability::where('business_id', $this->business->id)
            ->where('service_id', $this->service->id)
            ->where('day_of_week', $dayOfWeek)
            ->where('is_active', true)
            ->first();

        if (! $availability) {
            $this->availableSlots = [];

            return;
        }

        // Get available slots
        $this->availableSlots = BookingSlot::where('service_availability_id', $availability->id)
            ->where('date', $this->selectedDate)
            ->where('is_available', true)
            ->orderBy('start_time')
            ->get()
            ->filter(function ($slot) {
                return ! $slot->isFullyBooked();
            })
            ->values()
            ->toArray();
    }

    public function selectSlot($slotId): void
    {
        $slot = BookingSlot::find($slotId);
        if ($slot && $slot->is_available && ! $slot->isFullyBooked()) {
            $this->selectedSlot = $slotId;
            $this->step = 3;
        }
    }

    public function goToStep($step): void
    {
        $this->step = $step;
    }

    public function submit(): void
    {
        $this->validate();

        $slot = BookingSlot::find($this->selectedSlot);
        if (! $slot || ! $slot->is_available || $slot->isFullyBooked()) {
            session()->flash('error', 'This time slot is no longer available.');

            return;
        }

        // Try to book the slot
        if (! $slot->book()) {
            session()->flash('error', 'This time slot is no longer available.');

            return;
        }

        // Create the booking
        $booking = Booking::create([
            'business_id' => $this->business->id,
            'service_id' => $this->service->id,
            'user_id' => auth()->id(),
            'customer_name' => $this->customerName,
            'customer_phone' => $this->customerPhone,
            'customer_email' => $this->customerEmail,
            'booking_date' => $this->selectedDate,
            'booking_time' => $slot->start_time,
            'duration_minutes' => $this->service->duration_minutes ?? 60,
            'status' => Booking::STATUS_PENDING,
            'payment_required' => $this->service->requires_payment ?? false,
            'payment_status' => $this->service->requires_payment ? Booking::PAYMENT_UNPAID : Booking::PAYMENT_UNPAID,
            'amount' => $this->service->price ?? 0,
            'currency' => $this->service->currency ?? 'NGN',
            'notes' => $this->notes,
        ]);

        // Handle payment if required
        if ($this->service->requires_payment && $this->service->price > 0) {
            $this->redirectRoute('checkout.service', [
                'business' => $this->business->slug,
                'booking' => $booking->id,
            ]);

            return;
        }

        session()->flash('success', 'Your booking has been submitted! The business will confirm your appointment shortly.');
        $this->step = 4;
    }

    public function render()
    {
        return view('livewire.booking.book-appointment');
    }
}
