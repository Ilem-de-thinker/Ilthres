<?php

namespace App\Domains\Marketing\Livewire;

use App\Domains\Marketing\Actions\CreateLeadForm;
use App\Domains\Marketing\Actions\UpdateLeadForm;
use App\Models\Business;
use App\Models\LeadForm;
use Livewire\Component;

class LeadFormBuilder extends Component
{
    public ?Business $business = null;
    public ?LeadForm $editingForm = null;
    public ?int $editId = null;

    public string $name = '';
    public string $description = '';
    public string $template = 'custom';
    public bool $isActive = true;

    public array $fields = [];

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'template' => ['required', 'in:contact_us,get_a_quote,book_a_call,custom'],
            'isActive' => ['boolean'],
            'fields' => ['required', 'array', 'min:1'],
            'fields.*.label' => ['required', 'string', 'max:255'],
            'fields.*.type' => ['required', 'in:text,textarea,email,phone,select,checkbox,radio,file,date'],
        ];
    }

    protected $messages = [
        'name.required' => 'Enter a form name.',
        'fields.required' => 'Add at least one field.',
        'fields.*.label.required' => 'Each field needs a label.',
    ];

    public function mount(): void
    {
        $user = auth()->user();
        $businesses = $user->businesses;
        if ($businesses->isEmpty()) { return; }
        $defaultId = session('current_business_id', $businesses->first()->id);
        $this->business = $businesses->find($defaultId) ?? $businesses->first();
    }

    public function addField(): void
    {
        $this->fields[] = ['label' => '', 'type' => 'text', 'required' => false, 'options' => []];
    }

    public function removeField(int $index): void
    {
        unset($this->fields[$index]);
        $this->fields = array_values($this->fields);
    }

    public function edit(LeadForm $form): void
    {
        $this->editId = $form->id;
        $this->editingForm = $form;
        $this->name = $form->name;
        $this->description = $form->description ?? '';
        $this->template = $form->template ?? 'custom';
        $this->isActive = $form->is_active;

        $this->fields = $form->fields->map(fn ($f) => [
            'label' => $f->label,
            'type' => $f->type,
            'required' => $f->required,
            'options' => $f->options ?? [],
        ])->toArray();
    }

    public function resetForm(): void
    {
        $this->editId = null;
        $this->editingForm = null;
        $this->name = '';
        $this->description = '';
        $this->template = 'custom';
        $this->isActive = true;
        $this->fields = [];
    }

    public function save(CreateLeadForm $createAction, UpdateLeadForm $updateAction): void
    {
        $this->validate();
        if (! $this->business) { return; }

        $data = [
            'business_id' => $this->business->id,
            'name' => $this->name,
            'slug' => \Illuminate\Support\Str::slug($this->name),
            'description' => $this->description ?: null,
            'template' => $this->template,
            'is_active' => $this->isActive,
        ];

        if ($this->editId && $this->editingForm) {
            $form = $updateAction($this->editingForm, $data);
            $form->fields()->delete();
        } else {
            $form = $createAction($data);
        }

        foreach ($this->fields as $i => $field) {
            LeadFormField::create([
                'lead_form_id' => $form->id,
                'label' => $field['label'],
                'type' => $field['type'],
                'required' => $field['required'] ?? false,
                'options' => ! empty($field['options']) ? $field['options'] : null,
                'sort_order' => $i,
            ]);
        }

        $this->resetForm();
        $this->dispatch('form-saved');
    }

    public function render()
    {
        if (! $this->business) {
            return view('livewire.marketing.lead-form-builder', ['hasBusiness' => false]);
        }
        $existingForms = LeadForm::where('business_id', $this->business->id)->get();
        return view('livewire.marketing.lead-form-builder', [
            'hasBusiness' => true,
            'existingForms' => $existingForms,
        ]);
    }
}
