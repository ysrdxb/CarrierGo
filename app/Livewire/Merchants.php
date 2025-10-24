<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\DatabaseEntry;

class Merchants extends Component
{
    public $merchants;
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
    public $selectedMerchantId;
    public $showModal = false;
    public $database_type = 'merchant';

    protected $rules = [
        'firstname' => 'required|string',
        'lastname' => 'required|string',
        'database_type' => 'required|string|in:client,consignee,merchant,agent,carrier,shipping',
        'company_name' => 'required|string',
        'email' => 'required|email',
        'email_2' => 'nullable|email',
        'phone' => 'required|string',
        'phone_2' => 'nullable|string',
        'country' => 'nullable|string',
        'city' => 'nullable|string',
        'vat_no' => 'nullable|string',
        'zip_code' => 'nullable|numeric',
        'street' => 'nullable|string',
        'street_no' => 'nullable|string',
        'status' => 'nullable|string',
    ];
    
    public function mount()
    {
        $this->merchants = DatabaseEntry::where('database_type', $this->database_type)
            ->get();
    }

    public function render()
    {
        return view('livewire.merchants.index');
    }

    public function saveMerchant()
    {
        $this->validate();

        DatabaseEntry::create([
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'database_type' => $this->database_type,
            'company_name' => $this->company_name,
            'email' => $this->email,
            'email_2' => $this->email_2,
            'phone' => $this->phone,
            'phone_2' => $this->phone_2,
            'country' => $this->country,
            'city' => $this->city,
            'vat_no' => $this->vat_no,
            'zip_code' => $this->zip_code,
            'street' => $this->street,
            'street_no' => $this->street_no,
            'status' => $this->status,
        ]);        

        $this->resetFields();
        $this->merchants = DatabaseEntry::where('database_type', $this->database_type)
            ->get();

        session()->flash('success', 'Merchant added successfully!');

        // Close modal
        $this->showModal = false;
    }

    private function resetFields()
    {
        $this->firstname = '';
        $this->lastname = '';
        $this->database_type = '';
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
        $this->selectedMerchantId = null;
    }
    

    public function openModal()
    {
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }
}

