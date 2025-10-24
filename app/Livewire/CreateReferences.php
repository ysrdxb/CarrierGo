<?php

namespace App\Livewire;

use App\Models\Reference;
use App\Models\DatabaseEntry;
use App\Models\FreightType;
use App\Models\Freight;
use App\Models\ReferencesEditHistory;
use App\Models\Destination;
use App\Services\ReferenceNumberService;
use App\Models\ReferenceAdditionalFee;
use App\Models\Setting;

use Livewire\Component;
use Livewire\Attributes\On; 
use Livewire\Attributes\Lazy;
use Auth;
use Illuminate\Http\Request;

class CreateReferences extends Component
{
    public $clientId, $client_contact_person, $client_company_name, 
    $client_email, $client_email_2, $client_phone, $client_phone_2, 
    $client_country, $client_city, $client_vat_no, $client_zip_code, 
    $client_street, $client_street_no, $clients, $consignee_contact_person, 
    $consignee_company_name, $consignee_email, $consignee_email_2, 
    $consignee_phone, $consignee_phone_2, $consignee_country, $consignee_city, 
    $consignee_vat_no, $consignee_zip_code, $consignee_street, 
    $consignee_street_no, $consigneeId, $consignees, $merchantId, $carriers, $destinations, 
    $merchant_contact_person, $merchant_company_name, $merchant_email, $agents,
    $merchant_email_2, $merchant_phone, $merchant_phone_2, $merchant_country, 
    $merchant_city, $merchant_vat_no, $merchant_zip_code, 
    $merchant_street, $merchant_street_no, $merchants, $showClientModal, 
    $showConsigneeModal, $showMerchantModal, $showMerchant, $freight_types,
    $price, $agent_id, $carrier_id, $carrier_fees, $agent_fees, $extra_fees, 
    $extra_fees_eur, $freight_type, $client, $merchant, $consignee, $freight_type_id, 
    $freights_tab = 'vehicle', $destination_id, $vehicle_model, $vehicle_type, $vehicle_fin, $description;    
    public $nextReferenceNumber, $profit;
    protected $referenceService;
    public $freights = [];
    public $vehicleFreights = [];
    public $otherGoodsFreights = [];    
    public $additional_fees = [];
    public $carrier_fees_numeric;
    public $company;

    protected $rules = [
        'price' => 'required|numeric|regex:/^[0-9]{1,16}$/',
        'agent_id' => 'required|numeric|exists:database_entries,id',
        'carrier_id' => 'required|numeric|exists:database_entries,id',
        'carrier_fees' => 'required|string|max:50',
        'destination_id' => 'required|numeric',
        'freight_type_id' => 'required|numeric|exists:freight_types,id',
        'clientId' => 'required|numeric|exists:database_entries,id',
        'merchantId' => 'nullable|numeric|exists:database_entries,id',
        'consigneeId' => 'required|numeric|exists:database_entries,id',      
        'additional_fees.*.name' => 'required|string|max:150',
        'additional_fees.*.amount' => 'required|numeric|regex:/^[0-9]{1,16}$/',     
        'vehicleFreights.*.vehicle_model' => 'required_if:freights_tab,vehicle|string|max:250',
        'vehicleFreights.*.vehicle_type' => 'required_if:freights_tab,vehicle|string|max:250',
        'vehicleFreights.*.vehicle_fin' => 'required_if:freights_tab,vehicle|string|max:250',    
    ];

    public function messages()
    {
        return [
            'additional_fees.*.name.required' => 'Other fee title is required.',
            'additional_fees.*.amount.required' => 'Other fees amount is required.',
            'additional_fees.*.amount.numeric' => 'Other fees amount must be numeric.',
            'vehicleFreights.*.vehicle_model.required_if' => 'The vehicle model is required.',
            'vehicleFreights.*.vehicle_type.required_if' => 'The vehicle type is required.',
            'vehicleFreights.*.vehicle_fin.required_if' => 'The FIN number is required.',      
            'carrier_fees' => 'Enter correct value.',        
        ];
    }     
   
    public function mount(ReferenceNumberService $referenceService)
    {  
        $this->company = Setting::first();
        $this->referenceService = $referenceService;
        $this->nextReferenceNumber = $this->referenceService->getNextReferenceNumber();

        if (!$this->nextReferenceNumber) {
            session()->flash('error', 'You do not have any assigned reference numbers yet.');
        }

        $this->clients = DatabaseEntry::where('database_type', 'client')
        ->orderBy('firstname', 'asc')
        ->get();
        $this->agents = self::getEntry('agent');
        $this->consignees = DatabaseEntry::where('database_type', 'consignee')
        ->orderBy('firstname', 'asc')
        ->get();
        $this->carriers = self::getEntry('carrier');
        $this->merchants = DatabaseEntry::where('database_type', 'merchant')
        ->orderBy('firstname', 'asc')
        ->get();

        $this->freight_types = FreightType::orderBy('name', 'asc')->get();
        $this->destinations = Destination::orderBy('name', 'asc')->get();

    }

    #[on('refresh-entries')] 
    public function refresh_entries()
    {
    }
    
    public function render()
    {
        //$this->dispatch('afterDomUpdate');

        return view('livewire.references.create', [
            'freight_types' => $this->freight_types,
            'company' => $this->company,
        ]);
        
     
        $references = Reference::all();
        return view('livewire.references.index', [
            'references' => $references,
        ]);
       
    }

    public function updatedCarrierFees($value)
    {
        $cleanedValue = preg_replace('/[^0-9.,]/', '', $value);

        $numericValue = str_replace(',', '', $cleanedValue);

        if (!is_numeric($numericValue)) {
            $this->carrier_fees = 0;
            $this->carrier_fees_numeric = 0;
            $this->validateOnly('carrier_fees');
            return;
        }

        $formattedValue = number_format($numericValue, 2);

        $this->carrier_fees = $formattedValue;
        $this->carrier_fees_numeric = $numericValue;

        $this->validateOnly('carrier_fees');
    }

    protected function calculateProfit()
    {
        $this->profit = 0;

        if (is_numeric($this->price)) {
            $this->profit = $this->price;
        }

        if (is_numeric($this->carrier_fees_numeric)) {
            $this->profit -= $this->carrier_fees_numeric;
        }

        if (is_numeric($this->agent_fees)) {
            $this->profit -= $this->agent_fees;
        }

        if (is_numeric($this->extra_fees_eur)) {
            $this->profit -= $this->extra_fees_eur;
        }

        foreach ($this->additional_fees as $fee) {
            if (is_numeric($fee['amount'])) {
                $this->profit -= $fee['amount'];
            }
        }
    }
    

    public function updatedFreightTypeId()
    {
        $freightType = FreightType::findOrFail($this->freight_type_id);
        foreach ($this->freights as $index => $freight) {
            $this->vehicle_type[$index] = $freightType->name;
        }        
        
    }

    public function addVehicleFreight()
    {
        $this->vehicleFreights[] = [
            'vehicle_model' => '',
            'vehicle_type' => '',
            'vehicle_fin' => '',
        ];
    
        $this->dispatch('refresh');
    }
    
    public function addOtherGoodsFreight()
    {
        $this->otherGoodsFreights[] = [
            'description' => '',
        ];
    
        $this->dispatch('refresh');
    }

    public function addFee()
    {
        $this->additional_fees[] = ['name' => '', 'amount' => ''];
    }
    
    public function removeFee($index)
    {
        array_splice($this->additional_fees, $index, 1);
    }    
    
    public function removeVehicleFreight($index)
    {
        unset($this->vehicleFreights[$index]);
    }
    
    public function removeOtherGoodsFreight($index)
    {
        unset($this->otherGoodsFreights[$index]);
    } 

    public function updated($propertyName)
    {
        if (!empty($propertyName)) {
            $this->calculateProfit();
        }
    }

    public function changeTab($tab)
    {
        $this->freights_tab = $tab;
    }

    protected function getEntry($type) {
        $data = DatabaseEntry::where('database_type', $type)
                             ->orderBy('company_name', 'asc')
                             ->get();
        return $data;
    }
    
    public function create(ReferenceNumberService $referenceNumberService)
    {
        $this->validate();

        $nextReferenceNumber = $referenceNumberService->getNextReferenceNumber();
        $referenceNumberService->saveNextReferenceNumber();
    
        // Merge vehicleFreights and otherGoodsFreights arrays and add the type key
        $this->freights = array_merge(
            array_map(fn ($item) => ['type' => 'vehicle'] + $item, $this->vehicleFreights),
            array_map(fn ($item) => ['type' => 'other_goods'] + $item, $this->otherGoodsFreights)
        );
    
        $reference = Reference::create([
            'reference_no' => $nextReferenceNumber,
            'last_edited_at' => now(),
            'created_by' => Auth::user()->id,
            'status' => Reference::STATUS_NEW,
            'client_id' => $this->clientId,
            'consignee_id' => $this->consigneeId,
            'merchant_id' => $this->merchantId,
            'agent_id' => $this->agent_id,
            'carrier_id' => $this->carrier_id,
            'carrier_fees' => $this->carrier_fees,
            'agent_fees' => $this->agent_fees,
            'extra_fees' => $this->extra_fees,
            'price' => $this->price,
            'extra_fees_eur' => $this->extra_fees_eur,
            'payment' => '',
        ]);


        ReferencesEditHistory::create([
            'reference_id' => $reference->id,
            'editor_id' => Auth::user()->id,
            'details' => 'Created',
        ]);
             

        foreach ($this->additional_fees as $fee) {
            $reference->additionalFees()->create($fee);
        }        
        
        if(!empty($this->freights)) {
            foreach ($this->freights as $freight) {
                Freight::create([
                    'type' => $freight['type'],
                    'freight_type_id' => $this->freight_type_id,
                    'vehicle_model' => $freight['vehicle_model'] ?? null,
                    'vehicle_type' => $freight['vehicle_type'] ?? null,
                    'vehicle_fin' => $freight['vehicle_fin'] ?? null,
                    'description' => $freight['description'] ?? null,
                    'destination_id' => $this->destination_id,
                    'reference_id' => $reference->id,
                ]);
            }
        } else {
            Freight::create([
                'type' => $this->freight_type ?? 'vehicle',
                'freight_type_id' => $this->freight_type_id,
                'vehicle_model' => null,
                'vehicle_type' => null,
                'vehicle_fin' => null,
                'description' => null,
                'destination_id' => $this->destination_id,
                'reference_id' => $reference->id,
            ]);            
        }
    
        // Reset fields if needed
        self::resetFields();
    
        session()->flash('success', 'Reference ' .$nextReferenceNumber.' created successfully!');
        return redirect()->route('references.list');

    }
    
    
    public function resetFields()
    {
        $this->reference_no = null;
        $this->last_edited_at = null;
        $this->client = null;
        $this->consignee = null;
        $this->merchant = null;
        $this->agent_id = null;
        $this->carrier_id = null;
        $this->carrier_fees = null;
        $this->agent_fees = null;
        $this->extra_fees = null;
        $this->price = null;
        $this->extra_fees_eur = null;

        self::showClients(null);
        self::showConsignees(null);
        self::showMerchants(null);

    }
    

    public function updatedAgentId($value)
    {
        if ($value == 'add_new_agent') {
            $newAgentId = count($this->agents) + 1;
            $newAgentName = 'Agent ' . $newAgentId;
            $newAgent = ['id' => $newAgentId, 'name' => $newAgentName];
            $this->agents[] = $newAgent;
            $this->agent_id = $newAgentId;
        }
    }  
     
    
    #[On('showClients')]
    public function showClients($value)
    {
        if (is_numeric($value)) {
            
            $client = DatabaseEntry::where('database_type', 'client')
                ->find($value);
            
            if ($client) {
                $this->clientId = $client->id;
                $this->client_contact_person = $client->firstname . ' ' . $client->lastname;
                $this->client_lastname = $client->lastname;
                $this->client_company_name = $client->company_name;
                $this->client_email = $client->email;
                $this->client_email_2 = $client->email_2;
                $this->client_phone = $client->phone;
                $this->client_phone_2 = $client->phone_2;
                $this->client_country = $client->country;
                $this->client_city = $client->city;
                $this->client_vat_no = $client->vat_no;
                $this->client_zip_code = $client->zip_code;
                $this->client_street = $client->street . ' ' . $client->street_no;                
            }
        } else {
            $client = DatabaseEntry::make();
            $this->clientId = $client->id;
            $this->client_contact_person = $client->firstname . ' ' . $client->lastname;
            $this->client_company_name = $client->company_name;
            $this->client_email = $client->email;
            $this->client_email_2 = $client->email_2;
            $this->client_phone = $client->phone;
            $this->client_phone_2 = $client->phone_2;
            $this->client_country = $client->country;
            $this->client_city = $client->city;
            $this->client_vat_no = $client->vat_no;
            $this->client_zip_code = $client->zip_code;
            $this->client_street = $client->street . ' ' . $client->street_no;
        }

        //$this->dispatch('afterDomUpdate');
    }    

    #[On('showConsignees')]
    public function showConsignees($value)
    {
        $this->dispatch('refresh-entriess');
        if (is_numeric($value)) {
            $consignee = DatabaseEntry::where('database_type', 'consignee')
                ->find($value);
            if ($consignee) {
                $this->consigneeId = $consignee->id;
                $this->consignee_contact_person = $consignee->firstname . ' ' . $consignee->lastname;
                $this->consignee_company_name = $consignee->company_name;
                $this->consignee_email = $consignee->email;
                $this->consignee_email_2 = $consignee->email_2;
                $this->consignee_phone = $consignee->phone;
                $this->consignee_phone_2 = $consignee->phone_2;
                $this->consignee_country = $consignee->country;
                $this->consignee_city = $consignee->city;
                $this->consignee_vat_no = $consignee->vat_no;
                $this->consignee_zip_code = $consignee->zip_code;
                $this->consignee_street = $consignee->street . ' ' . $consignee->street_no;

            }
        } else {
            $consignee = DatabaseEntry::make();
            $this->consigneeId = $consignee->id;
            $this->consignee_contact_person = $consignee->firstname . ' ' . $consignee->lastname;
            $this->consignee_company_name = $consignee->company_name;
            $this->consignee_email = $consignee->email;
            $this->consignee_email_2 = $consignee->email_2;
            $this->consignee_phone = $consignee->phone;
            $this->consignee_phone_2 = $consignee->phone_2;
            $this->consignee_country = $consignee->country;
            $this->consignee_city = $consignee->city;
            $this->consignee_vat_no = $consignee->vat_no;
            $this->consignee_zip_code = $consignee->zip_code;
            $this->consignee_street = $consignee->street . ' ' . $consignee->street_no;
                    
        }
    }  
    
    #[On('showMerchants')]
    public function showMerchants($value)
    {
        $this->dispatch('refresh-entries');
        if (is_numeric($value)) {
            
            $merchant = DatabaseEntry::where('database_type', 'merchant')
                ->find($value);
            
            if ($merchant) {
                $this->merchantId = $merchant->id;
                $this->merchant_contact_person = $merchant->firstname . ' ' . $merchant->lastname;
                $this->merchant_lastname = $merchant->lastname;
                $this->merchant_company_name = $merchant->company_name;
                $this->merchant_email = $merchant->email;
                $this->merchant_email_2 = $merchant->email_2;
                $this->merchant_phone = $merchant->phone;
                $this->merchant_phone_2 = $merchant->phone_2;
                $this->merchant_country = $merchant->country;
                $this->merchant_city = $merchant->city;
                $this->merchant_vat_no = $merchant->vat_no;
                $this->merchant_zip_code = $merchant->zip_code;
                $this->merchant_street = $merchant->street . ' ' . $merchant->street_no;     
            }
        } else {
            $merchant = DatabaseEntry::make();
            $this->merchantId = $merchant->id;
            $this->merchant_contact_person = $merchant->firstname . ' ' . $merchant->lastname;
            $this->merchant_company_name = $merchant->company_name;
            $this->merchant_email = $merchant->email;
            $this->merchant_email_2 = $merchant->email_2;
            $this->merchant_phone = $merchant->phone;
            $this->merchant_phone_2 = $merchant->phone_2;
            $this->merchant_country = $merchant->country;
            $this->merchant_city = $merchant->city;
            $this->merchant_vat_no = $merchant->vat_no;
            $this->merchant_zip_code = $merchant->zip_code;
            $this->merchant_street = $merchant->street . ' ' . $merchant->street_no;
        }
    }  
  
}
