<?php

namespace App\Livewire;

use App\Models\Reference;
use App\Models\DatabaseEntry;
use App\Models\FreightType;
use App\Models\Freight;
use App\Models\Destination;
use App\Models\Order;
use App\Models\ReferenceAdditionalFee;
use App\Models\ReferencesEditHistory;
use App\Models\Guarantee;
use App\Models\TransportOrder;
use App\Models\BankDetail;
use App\Models\Invoice;
use App\Models\Setting;
use App\Models\UnloadingAddress;
use Livewire\WithFileUploads;
use App\Models\Document;
use App\Models\DriverAuthorization;
use Livewire\Component;
use Livewire\Attributes\On;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class ReferencesEdit extends Component
{
    use WithFileUploads;

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
    $freights_tab = 'vehicle', $destination_id, $vehicle_model, $vehicle_type, $vehicle_fin, $description,
    $reference, $driver_name, $plate_no, $add_date, $bill_of_lading, $rate_price,$loading_company_name, $loading_street, $loading_zip_city,
    $loading_contact_name, $loading_contact_phone, $loading_latest_date,
    $bank_accounts,
    $transport_price_eur, $add_transport_date, $transport_type, $freight_payer,
    $offered_in_eur, $vat, $invoice_language, $bank_account_id, $file, $filename, $document_type,
    $transport_orders = null, $driver_authorizations = null, $orders = null, $invoices = null, $guarantees = null,
    $order_placed_by, $date_displayed, $issuer, $profit, $freights = [], $vehicleFreights = [], $otherGoodsFreights = [],
    $unloading_address_id,
    $unloading_addresses,
    $create_unloading_address = false,
    $unloading_company_name,
    $unloading_street,
    $unloading_zip_city,
    $unloading_contact_name,
    $unloading_contact_phone,
    $unloading_latest_date,
    $showDocumentModal = false, $freight_vehicle_fin,
    $selectedDocument,
    $merchant_id, $additional_fees = [];
    public $getHistories = [];
    public $vessel_name;
    public $estimated_time_shipment;
    public $estimated_time_arrival;
    public $carrier_fees_numeric;
    public $company; 
    public $invoice_id = null;

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

    #[on('refresh-entries')]
    public function mount($referenceId = null)
    {
        $this->company = Setting::first();
        $referenceId = decrypt($referenceId);
        $this->freights = null;
        // Fetch the reference data based on its ID
        $reference = Reference::findOrFail($referenceId);
        $this->reference = $reference;

        // Populate the fields with reference data
        $this->price = $reference->price;
        $this->agent_id = $reference->agent_id;
        $this->carrier_id = $reference->carrier_id;
        $this->carrier_fees = $reference->carrier_fees;
        $this->agent_fees = $reference->agent_fees;
        $this->extra_fees = $reference->extra_fees;
        $this->extra_fees_eur = $reference->extra_fees_eur;
        $this->freight_type_id = optional($reference->freights->first())->freight_type_id;
        $this->vessel_name = $reference->vessel_name;
        $this->estimated_time_shipment = $reference->estimated_time_shipment;
        $this->estimated_time_arrival = $reference->estimated_time_arrival;
        // Fetch and populate other related fields as needed
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

        // Fetch and populate client, consignee, and merchant details based on IDs
        $this->client = $reference->client_id;
        $this->consignee = $reference->consignee_id;
        $this->merchant = $reference->merchant_id;

        // Define and assign values to variables
        $clientId = $reference->client_id;
        $consigneeId = $reference->consignee_id;
        $merchantId = $reference->merchant_id;

        // Dispatch events with appropriate parameters
        $this->dispatch('showClients', $clientId);
        $this->dispatch('showConsignees', $consigneeId);
        $this->dispatch('showMerchants', $merchantId);

        $this->destination_id = optional(optional($reference->freights->first())->destination)->id;

        // driver authorizations
        $this->driver_authorizations = DriverAuthorization::where('reference_id', $referenceId)->get();
        $this->driver_name = $reference->driverAuthorization->driver_name ?? '';
        $this->plate_no = $reference->driverAuthorization->plate_no ?? '';
        $this->add_date = $reference->driverAuthorization->add_date ?? '';
        $this->freight_vehicle_fin = $reference->driverAuthorization->freight_id ?? '';

        // booking order
        $this->orders = Order::where('reference_id', $referenceId)->get();
        $this->bill_of_lading = $reference->order->bill_of_lading ?? '';
        $this->rate_price = $reference->order->rate_price ?? '';

        //transport order
        $this->transport_orders = TransportOrder::where('reference_id', $referenceId)->get();
        $transportOrders = $reference->transportOrder;
        if ($transportOrders) {
            $transportOrder = $transportOrders->first();
            $this->loading_company_name = $transportOrder->loading_company_name ?? '';
            $this->loading_street = $transportOrder->loading_street ?? '';
            $this->loading_zip_city = $transportOrder->loading_zip_city ?? '';
            $this->loading_contact_name = $transportOrder->loading_contact_name ?? '';
            $this->loading_contact_phone = $transportOrder->loading_contact_phone ?? '';
            $this->loading_latest_date = $transportOrder->loading_latest_date ?? '';
            $this->transport_price_eur = $transportOrder->transport_price_eur ?? '';
            $this->add_transport_date = $transportOrder->add_date ?? '';
            $this->transport_type = $transportOrder->transport_type ?? '';
            $this->unloading_address_id = $transportOrder->unloading_address_id ?? '';
            $this->merchant_id = $transportOrder->merchant_id ?? '';
        }


        $this->transport_price_eur = $reference->transportOrder->first()->transport_price_eur ?? '';
        $this->add_transport_date = $reference->transportOrder->first()->add_date ?? '';
        $this->transport_type = $reference->transportOrder->first()->transport_type ?? '';

        $this->unloading_addresses = UnloadingAddress::all();
        $this->merchant_id = $this->reference->transportOrder->first()->merchant_id ?? '';

        // banks
        $this->bank_accounts = BankDetail::all();

        // Invoice
        $this->invoices = Invoice::where('reference_id', $referenceId)->get();
        $this->freight_payer = $this->reference->invoice->freight_payer ?? '';
        $this->offered_in_eur = $this->reference->invoice->amount ?? '';
        $this->vat = $this->reference->invoice->tax_rate ?? '';
        $this->invoice_language = $this->reference->invoice->language ?? '';
        $this->bank_account_id = $this->reference->invoice->bank_account_id ?? '';

        // guarantees
        $this->guarantees = Guarantee::where('reference_id', $referenceId)->get();
        $this->order_placed_by = $reference->guarantee->order_placed_by ?? '';
        $this->date_displayed = $reference->guarantee->date_displayed ?? '';
        $this->issuer = $reference->guarantee->issuer ?? '';

        $this->freights = $this->reference->freights()->get()->toArray();
        foreach ($this->freights as $freight) {
            if ($freight['type'] === 'vehicle') {
                $this->vehicleFreights[] = $freight;
            } else {
                $this->otherGoodsFreights[] = $freight;
            }
        }

        $this->additional_fees = $this->reference->additionalFees->toArray();
        $this->getHistories = ReferencesEditHistory::where('reference_id', $this->reference->id)->get();
        $this->offered_in_eur = $reference->price;

    }


    public function render()
    {
        return view('livewire.references.edit', [
            'freight_types' => FreightType::latest()->limit(5)->get(),
            'bank_accounts' => $this->bank_accounts,
            'freights' => $this->freights,
            'company' => $this->company,
            'profit' => $this->calculateProfit(),
        ]);
    }

    public function createInvoice()
    {
        $this->invoice_id = null;
        $this->freight_payer = '';
        $this->offered_in_eur = '';
        $this->invoice_language = '';
        $this->bank_account_id = '';
        $this->vat = '';        
    }

    public function editInvoice($invoice_id)
    {
        $this->invoice_id = $invoice_id;
        $invoice = Invoice::findOrFail($this->invoice_id);
        $this->freight_payer = $invoice->freight_payer;
        $this->offered_in_eur = $invoice->amount;
        $this->invoice_language = $invoice->language;
        $this->bank_account_id = $invoice->bank_account_id;
        $this->vat = $invoice->tax_rate;

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

    public function openEditHistoryModal()
    {
      //  $this->editHistories = $this->reference->editHistories();
    }

    public function toggleCreateUnloadingAddress()
    {
        $this->create_unloading_address = !$this->create_unloading_address;
    }

    public function saveUnloadingAddress()
    {
        // Validation
        $this->validate([
            'unloading_company_name' => 'required',
            'unloading_street' => 'required',
            'unloading_zip_city' => 'required',
            'unloading_contact_name' => 'required',
            'unloading_contact_phone' => 'required',
            'unloading_latest_date' => 'required|date',
        ]);

        // Save the unloading address
        UnloadingAddress::create([
            'company_name' => $this->unloading_company_name,
            'street' => $this->unloading_street,
            'zip_city' => $this->unloading_zip_city,
            'contact_name' => $this->unloading_contact_name,
            'contact_phone' => $this->unloading_contact_phone,
            'latest_date' => $this->unloading_latest_date,
        ]);

        // Reset fields and toggle flag
        $this->reset([
            'unloading_company_name',
            'unloading_street',
            'unloading_zip_city',
            'unloading_contact_name',
            'unloading_contact_phone',
            'unloading_latest_date',
            'create_unloading_address',
        ]);

        // Refresh the list of unloading addresses
        $this->unloading_addresses = UnloadingAddress::all();

        // Show success message or perform any other action
        session()->flash('message', 'Unloading address saved successfully.');
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['price', 'carrier_fees', 'agent_fees', 'extra_fees_eur'])) {
            $this->calculateProfit();
        }
    }

    public function addFee()
    {
        $this->additional_fees[] = ['name' => '', 'amount' => ''];
    }

    public function removeFee($index)
    {
        unset($this->additional_fees[$index]);
        $this->additional_fees = array_values($this->additional_fees);
    }

    public function addFreight()
    {
        $this->freights[] = [
            'freight_type_id' => null,
            'vehicle_model' => '',
            'vehicle_fin' => '',
            'description' => '',
        ];
    }

    public function removeFreight($index)
    {
        unset($this->freights[$index]);
    }

    public function addVehicleFreight()
    {
        $this->vehicleFreights[] = [
            'vehicle_model' => '',
            'vehicle_type' => '',
            'vehicle_fin' => '',
        ];

        // dispatch the refresh event to re-render the component
        $this->dispatch('refresh');
    }

    public function addOtherGoodsFreight()
    {
        $this->otherGoodsFreights[] = [
            'description' => '',
        ];

        // dispatch the refresh event to re-render the component
        $this->dispatch('refresh');
    }

    public function removeVehicleFreight($index)
    {
        unset($this->vehicleFreights[$index]);
    }

    public function removeOtherGoodsFreight($index)
    {
        unset($this->otherGoodsFreights[$index]);
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

    public function update()
    {
        $this->validate();

        // Retrieve the existing reference record
        $reference = Reference::findOrFail($this->reference->id);

        // Update the reference attributes
        $reference->update([
            'last_edited_at' => now(),
            'created_by' => Auth::user()->id,
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

        $this->reference->additionalFees()->delete();
        foreach ($this->additional_fees as $fee) {
            $this->reference->additionalFees()->create($fee);
        }
        foreach ($this->vehicleFreights as $key => $vehicleFreightData) {
            $vehicleFreightData['destination_id'] = $this->destination_id;
            $vehicleFreightData['freight_type_id'] = $this->freight_type_id;
            if (isset($vehicleFreightData['id'])) {
                $freight = Freight::findOrFail($vehicleFreightData['id']);
                $freight->update($vehicleFreightData);
				Freight::where('reference_id', $this->reference->id)->where('id', '!=', $vehicleFreightData['id'])->delete();
            } else {
                Freight::create([
                    'reference_id' => $this->reference->id,
                    'freight_type_id' => $this->freight_type_id,
                    'vehicle_model' => $vehicleFreightData['vehicle_model'] ?? '',
                    'vehicle_fin' => $vehicleFreightData['vehicle_fin'] ??  '',
                    'description' => $vehicleFreightData['description'] ?? '',
                    'vehicle_type' => $vehicleFreightData['vehicle_type'] ?? '',
                    'destination_id' => $vehicleFreightData['destination_id'],
                    'type' => 'vehicle',
                ]);
            }
        }

        foreach ($this->otherGoodsFreights as $key => $otherGoodsFreightData) {
            $otherGoodsFreightData['destination_id'] = $this->destination_id;
            $otherGoodsFreightData['freight_type_id'] = $this->freight_type_id;
            if (isset($otherGoodsFreightData['id'])) {
                $freight = Freight::findOrFail($otherGoodsFreightData['id']);
                $freight->update($otherGoodsFreightData);
            } else {
                Freight::create([
                    'reference_id' => $this->reference->id,
                    'freight_type_id' => $otherGoodsFreightData['freight_type_id'],
                    'vehicle_model' => null,
                    'vehicle_fin' => null,
                    'description' => $otherGoodsFreightData['description'],
                    'destination_id' => $otherGoodsFreightData['destination_id'],
                    'type' => 'other_goods',
                ]);
            }
        }

        self::referenceEdited($this->reference->id, 'Referenced Edited');
        // Reset form fields
        // $this->resetFields();
        // Flash success message
        session()->flash('success', 'Data updated successfully!');
        return redirect()->route('references.list');

    }

    public function updateShipment()
    {
        $this->validate([
            'vessel_name' => 'required|string|max:255',
            'estimated_time_shipment' => 'required|date|max:255',
            'estimated_time_arrival' => 'required|date|max:255',
        ]);

        $this->reference->vessel_name = $this->vessel_name;
        $this->reference->estimated_time_shipment = $this->estimated_time_shipment;
        $this->reference->estimated_time_arrival = $this->estimated_time_arrival;
        $this->reference->save();

        self::referenceEdited($this->reference->id, 'Shipment Status Details Updated to Vessel Name: '.$this->vessel_name.', ETS: '.$this->estimated_time_shipment.', ETA: '.$this->estimated_time_arrival);

        session()->flash('success', 'Shipment details saved successfully!');

    }

    protected function referenceEdited($referenced_id, $details)
    {
        ReferencesEditHistory::create([
            'reference_id' => $referenced_id,
            'editor_id' => Auth::user()->id,
            'details' => $details,
        ]);
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

    public function saveDriverAuthorization()
    {
        // Validate the input fields
        $this->validate([
            'driver_name' => 'required|string|max:250',
            'plate_no' => 'required|string|max:50',
            'add_date' => 'required|date|max:10',
            'freight_vehicle_fin' => 'required|numeric|exists:freights,id',
        ]);

        // Check if a driver authorization record already exists for the reference ID
        $existingAuthorization = DriverAuthorization::where('reference_id', $this->reference->id)->first();

        if ($existingAuthorization) {
            // Update the existing record
            $existingAuthorization->update([
                'driver_name' => $this->driver_name,
                'plate_no' => $this->plate_no,
                'add_date' => $this->add_date,
                'freight_id' => $this->freight_vehicle_fin,
            ]);

            $this->driver_authorizations[] = $existingAuthorization;

            // Show success message for update
            self::referenceEdited($this->reference->id, 'Driver Authorization Updated');
            session()->flash('driver_message', 'Driver authorization updated successfully!');
        } else {
            // Create a new DriverAuthorization instance
            $this->driver_authorizations[] = DriverAuthorization::create([
                'driver_name' => $this->driver_name,
                'plate_no' => $this->plate_no,
                'reference_id' => $this->reference->id,
                'add_date' => $this->add_date,
                'freight_id' => $this->freight_vehicle_fin,
            ]);
            self::referenceEdited($this->reference->id, 'Driver Authorization Created');
            // Show success message for creation
            session()->flash('driver_message', 'Driver authorization saved successfully!');
        }

    }

    public function saveOrder()
    {
        // Validate input fields
        $this->validate([
            'bill_of_lading' => 'required|string|max:255',
            'rate_price' => ['required', 'numeric', 'regex:/^\d{1,8}(\.\d{1,2})?$/'],
        ]);

        // Check if an order already exists for the reference
        $order = Order::where('reference_id', $this->reference->id)->first();

        if ($order) {
            // If order exists, update its attributes
            $order->update([
                'bill_of_lading' => $this->bill_of_lading,
                'rate_price' => $this->rate_price,
            ]);
            self::referenceEdited($this->reference->id, 'Booking Order Updated');
        } else {
            // If no order exists, create a new one
            Order::create([
                'reference_id' => $this->reference->id,
                'bill_of_lading' => $this->bill_of_lading,
                'rate_price' => $this->rate_price,
            ]);

            self::referenceEdited($this->reference->id, 'Booking Order Created');
            $this->reference->markAsBooked();
        }

        // Reset input fields
        // $this->reset(['bill_of_lading', 'rate_price']);

        // Show success message
        $this->orders = Order::where('reference_id', $this->reference->id)->get();
        session()->flash('booking_message', 'Booking order saved successfully!');
    }

    public function saveTransportOrder()
    {
        $rules = [
            'loading_company_name' => 'nullable|string|max:255',
            'transport_type' => 'required|string|in:new,merchant',
            'loading_street' => 'nullable|string|max:255',
            'loading_zip_city' => 'nullable|string|max:255',
            'loading_contact_name' => 'nullable|string|max:255',
            'loading_contact_phone' => 'nullable|string|max:255',
            'loading_latest_date' => 'nullable|date',
            'unloading_address_id' => 'required|numeric|exists:unloading_addresses,id',
            'transport_price_eur' => 'required|numeric',
            'add_transport_date' => 'required|date|max:10',
        ];

        if ($this->transport_type === 'merchant') {
            $rules['merchant_id'] = 'required|numeric|exists:database_entries,id';
        }

        $this->validate($rules);

        $transportOrder = TransportOrder::where('reference_id', $this->reference->id)->first();

        if ($transportOrder) {
            $transportOrder->update([
                'loading_company_name' => $this->loading_company_name,
                'transport_type' => $this->transport_type,
                'merchant_id' => $this->merchant_id,
                'loading_street' => $this->loading_street,
                'loading_zip_city' => $this->loading_zip_city,
                'loading_contact_name' => $this->loading_contact_name,
                'loading_contact_phone' => $this->loading_contact_phone,
                'loading_latest_date' => $this->loading_latest_date,
                'unloading_address_id' => $this->unloading_address_id,
                'transport_price_eur' => $this->transport_price_eur,
                'add_date' => $this->add_transport_date,
            ]);
            $this->reference->merchant_id = $this->merchant_id;
            $this->reference->save();
            $this->merchant = $this->merchant_id;
            self::referenceEdited($this->reference->id, 'Transport Order Updated');
            $this->dispatch('showMerchants', $this->merchant_id);
        } else {
            // If no transport order exists, create a new one
            TransportOrder::create([
                'reference_id' => $this->reference->id,
                'loading_company_name' => $this->loading_company_name,
                'transport_type' => $this->transport_type,
                'merchant_id' => $this->merchant_id,
                'loading_street' => $this->loading_street,
                'loading_zip_city' => $this->loading_zip_city,
                'loading_contact_name' => $this->loading_contact_name,
                'loading_contact_phone' => $this->loading_contact_phone,
                'loading_latest_date' => $this->loading_latest_date,
                'unloading_address_id' => $this->unloading_address_id,
                'transport_price_eur' => $this->transport_price_eur,
                'add_date' => $this->add_transport_date,
            ]);

            $this->reference->merchant_id = $this->merchant_id;
            $this->reference->save();
            $this->merchant = $this->merchant_id;
            $this->reference->markAsPickupScheduled();
            self::referenceEdited($this->reference->id, 'Transport Order Created');
            $this->dispatch('showMerchants', $this->merchant_id);

        }

        // Reset the input fields
        // $this->reset([
        //     'loading_company_name', 'loading_street', 'loading_zip_city',
        //     'loading_contact_name', 'loading_contact_phone', 'loading_latest_date',
        //     'unloading_company_name', 'unloading_street', 'unloading_zip_city',
        //     'unloading_contact_name', 'unloading_contact_phone', 'unloading_latest_date',
        //     'transport_price_eur', 'add_transport_date',
        // ]);

        // Show success message
        $this->transport_orders = TransportOrder::where('reference_id', $this->reference->id)->get();
        session()->flash('transport_message', 'Transport order created successfully!');
    }

    public function saveInvoice()
    {
        $this->validate([
            'freight_payer' => 'required|numeric|exists:database_entries,id',
            'offered_in_eur' => 'required|numeric',
            'vat' => 'required|numeric',
            'invoice_language' => 'required|in:English,German',
            'bank_account_id' => 'required|exists:bank_details,id',
        ]);

        $invoice = Invoice::find($this->invoice_id);

        if ($invoice) {
            $invoice->update([
                'freight_payer' => $this->freight_payer,
                'amount' => $this->offered_in_eur,
                'tax_rate' => $this->vat,
                'language' => $this->invoice_language,
                'bank_account_id' => $this->bank_account_id,
            ]);
            $this->reference->price = $this->offered_in_eur;
            $this->reference->save();
            self::referenceEdited($this->reference->id, 'Invoice '.$invoice->invoice_number.' Updated');
        } else {
            $latestInvoice = Invoice::latest()->first();
            $currentYear = date('y');
            if ($latestInvoice) {
                $latestYear = substr($latestInvoice->invoice_number, 5, 2);
                if ($latestYear == $currentYear) {
                    $sequentialNumber = str_pad((int)substr($latestInvoice->invoice_number, 0, 4) + 1, 4, '0', STR_PAD_LEFT);
                } else {
                    $sequentialNumber = '0001';
                }
            } else {
                $sequentialNumber = '0001';
            }

            $invoiceNumber = $sequentialNumber . '-' . $currentYear;

            Invoice::create([
                'reference_id' => $this->reference->id,
                'invoice_number' => $invoiceNumber,
                'freight_payer' => $this->freight_payer,
                'amount' => $this->offered_in_eur,
                'tax_rate' => $this->vat,
                'language' => $this->invoice_language,
                'bank_account_id' => $this->bank_account_id,
            ]);
            $this->reference->price = $this->offered_in_eur;
            $this->reference->save();

            self::referenceEdited($this->reference->id, 'Invoice '.$invoiceNumber.' Created');
        }

        // Reset input fields
        // $this->reset([
        //     'freight_payer', 'offered_in_eur', 'vat', 'invoice_language', 'bank_account_id'
        // ]);

        // Show success message
        return redirect()->route('references.edit', encrypt($this->reference->id))->with('success', 'Invoice saved successfully!');
        $this->invoices = Invoice::where('reference_id', $this->reference->id)->get();
        session()->flash('invoice_message', 'Invoice saved successfully!');
        $this->dispatch('refresh-entries');
    }

    public function saveGuarantee()
    {
        // Validate input fields
        $this->validate([
            'order_placed_by' => 'required|string|in:Merchant,NMH',
            'issuer' => 'required|string|in:client,merchant,consignee',
            'date_displayed' => 'required|date|max:10',
        ]);

        // Check if an guarantee already exists for the reference
        $guarantee = Guarantee::where('reference_id', $this->reference->id)->first();

        if ($guarantee) {
            // If order exists, update its attributes
            $guarantee->update([
                'order_placed_by' => $this->order_placed_by,
                'issuer' => $this->issuer,
                'date_displayed' => $this->date_displayed
            ]);
            self::referenceEdited($this->reference->id, 'Guarantee Updated');
        } else {
            // If no order exists, create a new one
            Guarantee::create([
                'order_placed_by' => $this->order_placed_by,
                'issuer' => $this->issuer,
                'date_displayed' => $this->date_displayed,
                'reference_id' => $this->reference->id,
            ]);
            self::referenceEdited($this->reference->id, 'Guarantee Created');
        }

        // Reset input fields
        // $this->reset(['date_displayed', 'order_placed_by']);

        // Show success message
        $this->guarantees = Order::where('reference_id', $this->reference->id)->get();
        session()->flash('guarantee_message', 'Guarantee order saved successfully!');
    }

    public function toggleDocumentModal($documentId)
    {
        $document = Document::findOrFail($documentId);
        $this->selectedDocument = $document;
        $this->filename = $document->file_name;
        $this->document_type = $document->document_type;
        $this->showDocumentModal = true;
    }

    public function uploadDocument($documentId)
    {
        $this->validate([
            'file' => 'required|file|max:10240',
            'filename' => 'required|string|max:255',
            'document_type' => 'required|string|max:255',
        ]);
        $document = Document::findOrFail($documentId);
        Storage::disk('public')->delete($document->document_path);
        $filePath = $this->file->store('documents', 'public');
        $document->update([
            'document_type' => $this->document_type,
            'file_name' => $this->filename,
            'document_path' => $filePath,
        ]);
        self::referenceEdited($document->reference_id, 'File Updated FileName '.$filePath);
        session()->flash('upload_message', 'File updated successfully!');
        $this->reset(['file', 'filename', 'document_type']);
    }

    public function saveFile()
    {
        $this->validate([
            'file' => 'required|file|max:10240',
            'filename' => 'required|string|max:255',
            'document_type' => 'required|string|max:255',
        ]);

        $filePath = $this->file->store('invoices', 'public');

        Document::create([
            'reference_id' => $this->reference->id,
            'document_type' => $this->document_type,
            'file_name' => $this->filename,
            'document_path' => $filePath,
        ]);

        self::referenceEdited($this->reference->id, 'File Uploaded FileName '.$filePath);

        session()->flash('success', 'File uploaded successfully!');
        $this->reset(['file', 'filename', 'document_type']);
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

        $this->dispatch('afterDomUpdate');
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

    // change statuses
	public function changeStatus($statusMethod)
	{
		try {
			if ($statusMethod === 'markAsSelfDelivery' || ($this->reference->transportOrder->isNotEmpty() && $statusMethod !== 'markAsSelfDelivery')) {
				if (method_exists($this->reference, $statusMethod)) {
					$this->reference->$statusMethod();
					session()->flash('success', 'Status changed successfully!');
				} else {
					session()->flash('error', 'Invalid status method!');
				}
			} else {
				session()->flash('error', 'Please add transport order and booking information first!');
			}
		} catch (\Exception $e) {
			session()->flash('error', 'An error occurred please try again later. ');
		}
	}

}
