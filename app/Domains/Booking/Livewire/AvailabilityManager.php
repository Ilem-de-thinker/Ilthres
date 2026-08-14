<?php

namespace App\Domains\Booking\Livewire;

use App\Models\BookingSlot;
use App\Models\Business;
use App\Models\Service;
use App\Models\ServiceAvailability;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AvailabilityManager extends Component
{
    public Business $business;

    public Service $service;

    public $availability = [];

    public $editingDay = null;

    public $start_time = '09:00';

    public $end_time = '17:00';

    public $max_bookings_per_slot = 1;

    public $slot_duration_minutes = 60;

    protected $rules = [
        'start_time' => 'required|date_format:H:i',
        'end_time' => 'required|date_format:H:i|after:start_time',
        'max_bookings_per_slot' => 'required|integer|min:1|max:100',
        'slot_duration_minutes' => 'required|integer|min:15|max:480',
    ];

    protected $messages = [
        'start_time.required' => 'Please enter a start time.',
        'end_time.required' => 'Please enter an end time.',
        'end_time.after' => 'End time must be after start time.',
        'max_bookings_per_slot.required' => 'Please enter max bookings per slot.',
        'slot_duration_minutes.required' => 'Please enter slot duration.',
    ];

    public function mount(int $serviceId): void
    {
        $this->business = Auth::user()->business;
        $this->service = Service::findOrFail($serviceId);

        $this->loadAvailability();
    }

    public function loadAvailability(): void
    {
        $this->availability = ServiceAvailability::where('business_id', $this->business->id)
            ->where('service_id', $this->service->id)
            ->orderBy('day_of_week')
            ->get()
            ->keyBy('day_of_week')
            ->toArray();
    }

    public function editDay($day): void
    {
        $this->editingDay = $day;

        if (isset($this->availability[$day])) {
            $slot = $this->availability[$day];
            $this->start_time = $slot['start_time'];
            $this->end_time = $slot['end_time'];
            $this->max_bookings_per_slot = $slot['max_bookings_per_slot'];
            $this->slot_duration_minutes = $slot['slot_duration_minutes'];
        } else {
            $this->start_time = '09:00';
            $this->end_time = '17:00';
            $this->max_bookings_per_slot = 1;
            $this->slot_duration_minutes = 60;
        }
    }

    public function saveAvailability(): void
    {
        $this->validate();

        ServiceAvailability::updateOrCreate(
            [
                'business_id' => $this->business->id,
                'service_id' => $this->service->id,
                'day_of_week' => $this->editingDay,
            ],
            [
                'start_time' => $this->start_time,
                'end_time' => $this->end_time,
                'max_bookings_per_slot' => $this->max_bookings_per_slot,
                'slot_duration_minutes' => $this->slot_duration_minutes,
                'is_active' => true,
            ]
        );

        $this->loadAvailability();
        $this->editingDay = null;
        session()->flash('success', 'Availability updated successfully.');
    }

    public function toggleDay($day): void
    {
        if (isset($this->availability[$day])) {
            $slot = $this->availability[$day];
            ServiceAvailability::where('id', $slot['id'])->update([
                'is_active' => ! $slot['is_active'],
            ]);
            $this->loadAvailability();
        }
    }

    public function generateSlots(): void
    {
        $availabilityRecords = ServiceAvailability::where('business_id', $this->business->id)
            ->where('service_id', $this->service->id)
            ->where('is_active', true)
            ->get();

        $generatedCount = 0;

        foreach ($availabilityRecords as $availability) {
            // Generate slots for next 30 days
            for ($i = 0; $i < 30; $i++) {
                $date = now()->addDays($i);
                $dayOfWeek = strtolower($date->format('D'));

                if ($dayOfWeek !== $availability->day_of_week) {
                    continue;
                }

                // Check if slots already exist for this date
                $existingSlots = BookingSlot::where('service_availability_id', $availability->id)
                    ->where('date', $date->format('Y-m-d'))
                    ->count();

                if ($existingSlots > 0) {
                    continue;
                }

                // Generate slots
                $startTime = Carbon::parse($this->start_time);
                $endTime = Carbon::parse($this->end_time);
                $slotDuration = $this->slot_duration_minutes;

                while ($startTime->lt($endTime)) {
                    $slotEndTime = $startTime->copy()->addMinutes($slotDuration);

                    if ($slotEndTime->gt($endTime)) {
                        break;
                    }

                    BookingSlot::create([
                        'service_availability_id' => $availability->id,
                        'date' => $date->format('Y-m-d'),
                        'start_time' => $startTime->format('H:i:s'),
                        'end_time' => $slotEndTime->format('H:i:s'),
                        'current_bookings' => 0,
                        'max_bookings' => $this->max_bookings_per_slot,
                        'is_available' => true,
                    ]);

                    $startTime = $slotEndTime;
                    $generatedCount++;
                }
            }
        }

        session()->flash('success', "Generated {$generatedCount} booking slots for the next 30 days.");
    }

    public function render()
    {
        return view('livewire.booking.availability-manager');
    }
}
