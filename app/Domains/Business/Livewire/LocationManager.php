<?php

namespace App\Domains\Business\Livewire;

use App\Domains\Business\Actions\CreateLocation;
use App\Domains\Business\Actions\DeleteLocation;
use App\Domains\Business\Actions\SetPrimaryLocation;
use App\Domains\Business\Actions\UpdateLocation;
use App\Domains\Business\DTOs\LocationData;
use App\Models\Business;
use App\Models\BusinessLocation;
use Livewire\Component;

class LocationManager extends Component
{
    public ?Business $business = null;

    public bool $showForm = false;

    public ?int $editingLocationId = null;

    public string $country = 'Nigeria';

    public string $state = '';

    public string $city = '';

    public string $address = '';

    public string $postalCode = '';

    public float $latitude = 0;

    public float $longitude = 0;

    public string $phone = '';

    public string $email = '';

    public bool $isPrimary = false;

    public bool $isHeadquarters = false;

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

    public function create(): void
    {
        $this->resetForm();
        $this->showForm = true;
        $this->editingLocationId = null;
    }

    public function edit(int $id): void
    {
        $location = BusinessLocation::findOrFail($id);
        $this->authorize('update', $location);

        $this->editingLocationId = $location->id;
        $this->country = $location->country;
        $this->state = $location->state;
        $this->city = $location->city;
        $this->address = $location->address;
        $this->postalCode = $location->postal_code ?? '';
        $this->latitude = (float) $location->latitude;
        $this->longitude = (float) $location->longitude;
        $this->phone = $location->phone ?? '';
        $this->email = $location->email ?? '';
        $this->isPrimary = $location->is_primary;
        $this->isHeadquarters = $location->is_headquarters;
        $this->showForm = true;
    }

    public function save(CreateLocation $createLocation, UpdateLocation $updateLocation): void
    {
        $this->validate();

        try {
            $data = new LocationData(
                businessId: $this->business->id,
                country: $this->country,
                state: $this->state,
                city: $this->city,
                address: $this->address,
                postalCode: $this->postalCode ?: null,
                latitude: $this->latitude,
                longitude: $this->longitude,
                phone: $this->phone ?: null,
                email: $this->email ?: null,
                isPrimary: $this->isPrimary,
                isHeadquarters: $this->isHeadquarters,
            );

            if ($this->editingLocationId) {
                $location = BusinessLocation::findOrFail($this->editingLocationId);
                $this->authorize('update', $location);
                $updateLocation($location, $data);
                $this->successMessage = 'Location updated.';
            } else {
                $this->authorize('create', BusinessLocation::class);
                $createLocation($data);
                $this->successMessage = 'Location added.';
            }

            $this->showForm = false;
            $this->resetForm();
        } catch (\Throwable $e) {
            $this->errorMessage = 'Failed to save location.';
            report($e);
        }
    }

    public function setPrimary(int $id): void
    {
        $location = BusinessLocation::findOrFail($id);
        $this->authorize('update', $location);

        app(SetPrimaryLocation::class)($location);

        $this->successMessage = 'Primary location updated.';
    }

    public function delete(int $id): void
    {
        $location = BusinessLocation::findOrFail($id);
        $this->authorize('delete', $location);

        app(DeleteLocation::class)($location);

        $this->successMessage = 'Location deleted.';
    }

    public function cancel(): void
    {
        $this->showForm = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->editingLocationId = null;
        $this->country = 'Nigeria';
        $this->state = '';
        $this->city = '';
        $this->address = '';
        $this->postalCode = '';
        $this->latitude = 0;
        $this->longitude = 0;
        $this->phone = '';
        $this->email = '';
        $this->isPrimary = false;
        $this->isHeadquarters = false;
    }

    protected function rules(): array
    {
        return [
            'country' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'address' => ['required', 'string'],
            'postalCode' => ['nullable', 'string', 'max:20'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'isPrimary' => ['boolean'],
            'isHeadquarters' => ['boolean'],
        ];
    }

    public function render()
    {
        if (! $this->business) {
            return view('livewire.business.location-manager', [
                'hasBusiness' => false,
            ]);
        }

        $locations = BusinessLocation::where('business_id', $this->business->id)
            ->orderBy('is_primary', 'desc')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('livewire.business.location-manager', [
            'hasBusiness' => true,
            'locations' => $locations,
        ]);
    }
}
