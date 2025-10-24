<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\DatabaseEntry;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\On; 

class DatabaseEntriesCreate extends Component
{
    public $entries;
    public $fields;
    public $showModal = false;
    public $entryType = 'client';
    public $firstname;
    public $lastname;
    public $company_name;
    public $email;
    public $email_2;
    public $phone;
    public $phone_2;
    public $country;
    public $city;
    public $vat_no;
    public $zip_code;
    public $street;
    public $street_no;
    public $status;
    public $database_type;

    protected $rules = [
        'firstname' => 'nullable|string|max:250',
        'lastname' => 'nullable|string|max:250',
        'database_type' => 'nullable|string|in:client,merchant,consignee,carrier,agent,address details',
        'company_name' => 'required|string|max:250',
        'email' => 'required|email|max:250',
        'email_2' => 'nullable|email|max:250',
        'phone' => 'required|numeric|regex:/^[0-9]{1,16}$/',
        'phone_2' => 'nullable|numeric|regex:/^[0-9]{1,16}$/',
        'country' => 'nullable|string|max:250',
        'city' => 'nullable|string|max:250',
        'vat_no' => 'nullable|string|max:50',
        'zip_code' => 'nullable|numeric|regex:/^[0-9]{1,10}$/',
        'street' => 'nullable|string|max:250',
        'street_no' => 'nullable|string|max:250',
        'status' => 'nullable|string|max:25',
    ];   
    

    public function mount()
    {
        $this->fields = $this->getFieldsByType($this->entryType);
        $this->entries = DatabaseEntry::where('database_type', $this->entryType)->get();
    }

    public function render()
    {
        return view('livewire.database_entries.create');
    }

    public function saveEntry()
    {
        $this->validate();

        $lastEntry = DatabaseEntry::latest()->first();
        $entry_group = $lastEntry ? $lastEntry->entry_group + 1 : 1;

        $data = [
            'firstname' => $this->firstname ?? '',
            'lastname' => $this->lastname ?? '',
            'company_name' => $this->company_name ?? '',
            'email' => $this->email ?? '',
            'email_2' => $this->email_2 ?? '',
            'phone' => $this->phone ?? '',
            'phone_2' => $this->phone_2 ?? '',
            'country' => $this->country ?? '',
            'city' => $this->city ?? '',
            'vat_no' => $this->vat_no ?? '',
            'zip_code' => $this->zip_code ?? '',
            'street' => $this->street ?? '',
            'street_no' => $this->street_no ?? '',
            'status' => $this->status ?? '',
            'database_type' => $this->entryType,
            'entry_group' => $entry_group,
        ];

        $entry = DatabaseEntry::create($data);

        $this->dispatch('refresh-entries');
        $this->dispatch('databaseEntryCreated', ['type' => $entry->database_type, 'name' => $entry->firstname. ' ' . $entry->lastname, 'id' => $entry->id]);
        $this->resetFields();
        session()->flash('success_'.$this->entryType, ucfirst($this->entryType) . ' added successfully!');

        // Close modal
        $this->showModal = false;
    }

    private function resetFields()
    {
        foreach ($this->fields as $field) {
            $this->{$field} = '';
        }
    }

    public function openModal()
    {
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    private function getFieldsByType($database_type)
    {
        // Define fields for each database type
        $fields = [
            'client' => ['firstname', 'lastname', 'company_name', 'email', 'email_2', 'phone', 'phone_2', 'country', 'city', 'vat_no', 'zip_code', 'street', 'street_no', 'status'],
            'merchant' => ['firstname', 'lastname', 'company_name', 'email', 'email_2', 'phone', 'phone_2', 'country', 'city', 'vat_no', 'zip_code', 'street', 'street_no', 'status'],
            'consignee' => ['firstname', 'lastname', 'company_name', 'email', 'email_2', 'phone', 'phone_2', 'country', 'city', 'vat_no', 'zip_code', 'street', 'street_no', 'status'],
            // Add more types as needed
        ];
        return $fields[$database_type] ?? [];
    }
}
