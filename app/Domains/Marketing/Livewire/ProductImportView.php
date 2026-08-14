<?php

namespace App\Domains\Marketing\Livewire;

use App\Models\Business;
use App\Models\ProductImport;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProductImportView extends Component
{
    use WithFileUploads;

    public ?Business $business = null;

    public $csvFile = null;

    protected function rules(): array
    {
        return [
            'csvFile' => ['required', 'file', 'mimes:csv,txt', 'max:10240'],
        ];
    }

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

    public function import(): void
    {
        $this->validate();
        if (! $this->business) {
            return;
        }

        $path = $this->csvFile->store('product-imports');
        $filename = $this->csvFile->getClientOriginalName();

        ProductImport::create([
            'business_id' => $this->business->id,
            'filename' => $filename,
            'file_path' => $path,
            'total_rows' => 0,
            'processed_rows' => 0,
            'skipped_rows' => 0,
            'error_count' => 0,
            'status' => 'pending',
        ]);

        $this->csvFile = null;
        $this->dispatch('import-started');
    }

    public function render()
    {
        if (! $this->business) {
            return view('livewire.marketing.product-import', ['hasBusiness' => false]);
        }

        $imports = ProductImport::where('business_id', $this->business->id)
            ->latest()
            ->get();

        return view('livewire.marketing.product-import', [
            'hasBusiness' => true,
            'imports' => $imports,
        ]);
    }
}
