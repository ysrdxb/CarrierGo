<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\DatabaseEntry;
use App\Models\Destination;
use App\Models\FreightType;
use Livewire\WithPagination;
use Illuminate\Http\Request;

class DatabaseEntriesList extends Component
{
    use WithPagination;

    public $entries;
    public $selectedEntry = null;
    public $search = null;
    public $fields;
    public $showModal = false;
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
    public $entryTypes = [];
    public $errorMessages = [];
    public $editMode = false;
    public $id = null;
    public $entry_name;
    public $entry_group = [];
    public $entryType;
    public $current_entry;

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
        'entryTypes' => 'required',
    ];

    protected $listeners = ['updateSelectedEntry'];

    public function mount()
    {
        $this->fields = $this->getFieldsByType($this->entryType);
    }


    public function render()
    {
        $query = DatabaseEntry::query()->when($this->selectedEntry, function ($query) {
            return $query->where('database_type', $this->selectedEntry);
        });

        if ($this->search) {
            $query->where(function ($query) {
                $query->where('firstname', 'like', '%' . $this->search . '%')
					->orWhere('id', 'like', '%' . $this->search . '%')
					->orWhere('entry_group', 'like', '%' . $this->search . '%')
                    ->orWhere('lastname', 'like', '%' . $this->search . '%')
                    ->orWhere('company_name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhere('phone', 'like', '%' . $this->search . '%')
                    ->orWhere('city', 'like', '%' . $this->search . '%')
                    ->orWhere('zip_code', 'like', '%' . $this->search . '%')
                    ->orWhere('street', 'like', '%' . $this->search . '%');
            });
        }

        $data = $query->paginate(12);

        return view('livewire.database_entries.index', [
            'data' => $data
        ]);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function edit($id)
    {
        $entry = DatabaseEntry::findOrFail($id);
   
        $this->firstname = $entry->firstname;
        $this->lastname = $entry->lastname;
        $this->company_name = $entry->company_name;
        $this->email = $entry->email;
        $this->email_2 = $entry->email_2;
        $this->phone = $entry->phone;
        $this->phone_2 = $entry->phone_2;
        $this->country = $entry->country;
        $this->city = $entry->city;
        $this->vat_no = $entry->vat_no;
        $this->zip_code = $entry->zip_code;
        $this->street = $entry->street;
        $this->street_no = $entry->street_no;
        $this->status = $entry->status;
        $this->database_type = $entry->database_type;
        $this->id = $entry->id;
        $this->showModal = true;
        $this->editMode = true;
        $this->entryType = $entry->database_type;
        $this->current_entry = $entry->database_type;
        $this->entryTypes = explode(',', $entry->database_type);

    }

    public function create()
    {
        $this->id = false;
        $this->editMode = true;
        $this->resetFields();
    }

    public function create_destination_freight_type()
    {
        return true;
    }

    public function updateSelectedEntry($value = null)
    {
        $this->selectedEntry = $value;
        $this->entries = DatabaseEntry::where('database_type', $this->selectedEntry)->get();
    }

    public function createEntry()
    {
        $this->editMode = false;
        $this->id = null;
    }

    public function selectedEntries($value)
    {
        $this->database_type = $value;
    }


    public function saveEntry()
    {
        $this->validate();
        if($this->id) {          

            $entry = DatabaseEntry::findOrFail($this->id);
            $lastEntry = DatabaseEntry::latest()->first();
            
            $entry_group = $lastEntry ? $lastEntry->entry_group + 1 : 1;              
            $entry_group = $entry->entry_group > 0 ? $entry->entry_group :  $entry_group;
            if($entry->entry_group > 0 && !empty($entry->entry_group) ) {
                $entries = DatabaseEntry::where('entry_group', $entry->entry_group)->get();
                if($entries->isNotEmpty()) {
                    foreach($entries as $row) {
                        $entry = DatabaseEntry::find($row->id);
                        $entry->firstname = $this->firstname;
                        $entry->lastname = $this->lastname;
                        $entry->company_name = $this->company_name;
                        $entry->email = $this->email;
                        $entry->email_2 = $this->email_2;
                        $entry->phone = $this->phone;
                        $entry->phone_2 = $this->phone_2;
                        $entry->country = $this->country;
                        $entry->city = $this->city;
                        $entry->vat_no = $this->vat_no;
                        $entry->zip_code = $this->zip_code;
                        $entry->street = $this->street;
                        $entry->street_no = $this->street_no;
                        $entry->status = 'updated';
                        $entry->database_type = $row->database_type;
                        $entry->entry_group = !empty($entry->entry_group) && $entry->entry_group > 0 ? $entry->entry_group :  $entry_group;
                        $entry->save();
                    }
                }
            } else {
                $entry->firstname = $this->firstname;
                $entry->lastname = $this->lastname;
                $entry->company_name = $this->company_name;
                $entry->email = $this->email;
                $entry->email_2 = $this->email_2;
                $entry->phone = $this->phone;
                $entry->phone_2 = $this->phone_2;
                $entry->country = $this->country;
                $entry->city = $this->city;
                $entry->vat_no = $this->vat_no;
                $entry->zip_code = $this->zip_code;
                $entry->street = $this->street;
                $entry->street_no = $this->street_no;
                $entry->status = 'updated';
                $entry->database_type = $this->database_type;
                $entry->entry_group = !empty($entry->entry_group) && $entry->entry_group > 0 ? $entry->entry_group :  $entry_group;
                $entry->save();                
            }

        } else {
            $lastEntry = DatabaseEntry::latest()->first();
            $entry_group = $lastEntry ? $lastEntry->entry_group + 1 : 1;               
            foreach ($this->entryTypes as $entryType) {
                $entry = new DatabaseEntry();

                $entry->firstname = $this->firstname;
                $entry->lastname = $this->lastname;
                $entry->company_name = $this->company_name;
                $entry->email = $this->email;
                $entry->email_2 = $this->email_2;
                $entry->phone = $this->phone;
                $entry->phone_2 = $this->phone_2;
                $entry->country = $this->country;
                $entry->city = $this->city;
                $entry->vat_no = $this->vat_no;
                $entry->zip_code = $this->zip_code;
                $entry->street = $this->street;
                $entry->street_no = $this->street_no;
                $entry->status = 'test';
                $entry->database_type = $entryType;
                $entry->entry_group = $entry_group;
                $entry->save();
            }
        }


        session()->flash('success_client', 'Entry saved successfully.');

        $this->reset();
    }


    public function saveFreightDestination()
    {
        $this->validate([
            'entry_name' => 'required|string|max:250',
        ]);

        Destination::create(['name' => $this->entry_name]);
        session()->flash('success_freight_destination', 'Destination saved successfully!');
        $this->resetFields();
        $this->showModal = false;
        $this->entry_name = null;
        $this->id = null;
    }

    private function resetFields()
    {
        $this->firstname = '';
        $this->lastname = '';
        $this->company_name = '';
        $this->email = '';
        $this->email_2 = '';
        $this->phone = '';
        $this->phone_2 = '';
        $this->country = '';
        $this->city = '';
        $this->vat_no = '';
        $this->zip_code = '';
        $this->street = '';
        $this->street_no = '';
        $this->status = '';
        $this->entryTypes = [];
    }

    public function openModal()
    {
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    private function getFieldsByType($databaseType)
    {
        $fields = [
            'client' => ['firstname', 'lastname', 'company_name', 'email', 'email_2', 'phone', 'phone_2', 'country', 'city', 'vat_no', 'zip_code', 'street', 'street_no', 'status'],
            'merchant' => ['firstname', 'lastname', 'company_name', 'email', 'email_2', 'phone', 'phone_2', 'country', 'city', 'vat_no', 'zip_code', 'street', 'street_no', 'status'],
            'consignee' => ['firstname', 'lastname', 'company_name', 'email', 'email_2', 'phone', 'phone_2', 'country', 'city', 'vat_no', 'zip_code', 'street', 'street_no', 'status'],
        ];

        return $fields[$databaseType] ?? [];
    }

    public function delete($id)
    {
        $entry = DatabaseEntry::findOrFail($id);
        $entry->delete();
        session()->flash('success', 'Entry deleted successfully!');
    }
}
