<div>
    <div class="main-content mt-0 hor-content">
        <div class="side-app">

            <!-- CONTAINER -->
            <div class="main-container container">

                <!-- PAGE-HEADER -->
                <div class="page-header">
                    @if($this->nextReferenceNumber)
                        <h1 class="page-title">{{ $this->nextReferenceNumber }} *NEW* </h1>
                    @endif
                </div>
                @include('components.message') 
                @if($this->nextReferenceNumber)
                <form wire:submit.prevent="create">
                    <div class="row">
                        
                        <div class="col-xl-4 col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Client details</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-4">
                                        <div wire:ignore>
                                            <select id="client_id" wire:model="client" class="form-control select select-entries" data-type="client">
                                                <option value="">Choose Client</option>
                                                
                                                    @foreach($clients as $client)
                                                        <option value="{{ $client->id }}">{{ $client->firstname . ' ' . $client->lastname }}</option>
                                                    @endforeach
                                                <option value="add_new_client_modal">+ Add new Client</option>
                                            </select>
                                        
                                        </div>
                                        @error('client') <span class="text-danger">{{ $message }} </span>  @enderror                               
                                    </div>
                                    @include('components.client_data')                                                                     
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Consignee details</h4>
                                </div>
                                <div class="card-body">
                                        <div class="row mb-4">
                                            <div wire:ignore>
                                                <select id="consignee_id" wire:model="consignee" class="form-control select select-entries" data-type="consignee">
                                                    <option>Choose Consignee</option>
                                                    @foreach($consignees as $row)
                                                    <option value="{{ $row->id }}">{{ $row->firstname . ' ' . $row->lastname }}</option>
                                                    @endforeach
                                                    <option value="add_new_consignee_modal">+ Add new Consignee</option>
                                                </select>         
                                            </div>
                                            @error('consignee') <span class="text-danger">{{ $message }} </span> @enderror                       
                                        </div>
                                        @include('components.consignee_data')                                    
                                        
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Merchant details</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-4">
                                        <div wire:ignore>
                                            <select id="merchant_id" wire:model="merchant" class="form-control select select-entries" data-type="merchant">
                                                <option>Choose Merchant</option>
                                                @foreach($merchants as $row)
                                                <option value="{{ $row->id }}">{{ $row->firstname . ' ' . $row->lastname }}</option>
                                                @endforeach
                                                <option value="add_new_merchant_modal">+ Add new Merchant</option>
                                            </select>
                                        </div>
                                        @error('merchant') <span class="text-danger">{{ $message }} </span> @enderror                
                                    </div>
                                    
                                    @include('components.merchant_data')                                                                     
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Freight details</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-4">
                                        <div wire:ignore>
                                            <select id="freight_type_id" wire:model="freight_type_id" class="form-control select form-select select-freight-type" data-placeholder="Choose one">
                                                <option value="">Choose Freight Type</option>
                                                @foreach($freight_types as $row)
                                                <option value="{{ $row->id }}">{{ $row->name }}</option>
                                                @endforeach
                                                <option value="add_new_freight_type">+ Add new freight type</option>
                                            </select>
                                        </div>
                                        @error('freight_type_id') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                        
                                    <div class="card-pay">
                                        <ul class="tabs-menu nav">
                                            <li class="">
                                                <a class="{{ $freights_tab === 'vehicle' ? 'active' : '' }}" href="#" wire:click.prevent="changeTab('vehicle')">Vehicle</a>
                                            </li>
                                            <li class="">
                                                <a class="{{ $freights_tab === 'other_goods' ? 'active' : '' }}" href="#" wire:click.prevent="changeTab('other_goods')">Other Goods</a>
                                            </li>
                                        </ul>
                                        <div class="tab-content">
                                            <div class="tab-pane {{ $freights_tab === 'vehicle' ? ' active show' : '' }}" id="tab20">
                                                @foreach($vehicleFreights as $index => $vehicleFreight)
                                                <div class="row mb-4">
                                                    <label class="col-md-3 form-label">Model</label>
                                                    <div class="col-md-9">
                                                        <input wire:model="vehicleFreights.{{ $index }}.vehicle_model" type="text" class="form-control" value="">
                                                    </div>
                                                </div>
                                                @error("vehicleFreights.{$index}.vehicle_model")
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                                
                                                <div class="row mb-4">
                                                    <label class="col-md-3 form-label">Type</label>
                                                    <div class="col-md-9">
                                                        <input wire:model="vehicleFreights.{{ $index }}.vehicle_type" type="text" class="form-control" value="">
                                                    </div>
                                                </div>
                                                @error("vehicleFreights.{$index}.vehicle_type")
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                                
                                                <div class="row mb-4">
                                                    <label class="col-md-3 form-label">FIN No.</label>
                                                    <div class="col-md-9">
                                                        <input wire:model="vehicleFreights.{{ $index }}.vehicle_fin" type="text" class="form-control" value="">
                                                    </div>
                                                </div>
                                                @error("vehicleFreights.{$index}.vehicle_fin")
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                                
                                                <a href="javascript:;" class="text-danger" wire:click="removeVehicleFreight({{ $index }})">x Remove Vehicle</a>
                                                @endforeach 
                                                                                                
                                                <div class="row mb-4 text-center">
                                                    <a href="javascript:;" class="text-primary" wire:click="addVehicleFreight">+ Add vehicle</a>
                                                </div>
                                            </div>
                                            <div class="tab-pane{{ $freights_tab === 'other_goods' ? ' active show' : '' }}" id="tab21">
                                                @foreach($otherGoodsFreights as $index => $otherGoodsFreight)
                                                <div class="row mb-4">
                                                    <label class="col-md-3 form-label">Description</label>
                                                    <div class="col-md-9">
                                                        <input wire:model="otherGoodsFreights.{{ $index }}.description" type="text" class="form-control" value="">
                                                    </div>
                                                </div>
                                                <a href="javascript:;" class="text-danger" wire:click="removeOtherGoodsFreight({{ $index }})">x Remove the line</span></a>
                                                @endforeach
                                                <div class="row mb-4 text-center">
                                                    <a href="javascript:;" class="text-primary" wire:click="addOtherGoodsFreight">+ Add another line</a>
                                                </div>
                                            </div>
                                            <div class="row mb-4">
                                                <div class="col-md-12">
                                                    <div wire:ignore>
                                                        <label class="col-md-3 form-label">Destination</label>
                                                        <select id="destination_id" wire:model="destination_id" class="form-control select select2-show-search form-select" data-placeholder="Choose one">
                                                            <option selected value="">Choose Destination</option>
                                                            @foreach($destinations as $destination)
                                                            <option value="{{ $destination->id }}">{{ $destination->name }}</option>
                                                            @endforeach
                                                            <option value="add_destination">+ Add new destination</option>
                                                        </select>
                                                    </div>
                                                    @error('destination_id') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            </div>                                        
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xl-6 col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Calculation</h4>
                                </div>
                                <div class="card-body">
                                        <div class="form-row">
                                            
                                            <div class="col-xl-6 mb-3">
                                                <label for="validationCustom01">Offered in {{ $company->currency }}</label>
                                                <input wire:model.live="price" type="text" class="form-control" id="validationCustom01" value="" 
                                                    value="">
                                                @error('price') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                            <div class="col-xl-6 mb-3">
                                                <label for="validationCustom01">Profit</label>
                                                <input type="text" class="form-control" wire:model="profit" readonly>                                                
                                                <div class="valid-feedback">Looks good!</div>
                                            </div>
                                            
                                        </div>
                                        <div class="form-row">
                                            <div class="col-xl-6 mb-3">
                                                <div wire:ignore>
                                                    <label for="validationCustom01">Choose Carrier</label>
                                                    <select data-type="carrier" id="carrier_id" wire:model="carrier_id" class="form-control select select2-show-search form-select select-entries" data-placeholder="Choose one"
                                                        >
                                                        <option selected value="">Choose Carrier</option>
                                                        @foreach($carriers as $carrier)
                                                        <option value="{{ $carrier->id }}"> {{ $carrier->company_name }}</option>
                                                        @endforeach
                                                        <option value="add_new_carrier_modal">+ Add new Carrier</option>
                                                    </select>
                                                </div>
                                                @error('carrier_id') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                            <div class="col-xl-6 mb-3">
                                                <label for="validationCustom01">Carrier fees in {{ $company->currency }}</label>
                                                <input wire:model.live.debounce.1000ms="carrier_fees" type="text" class="form-control" id="validationCustom01">
                                                @error('carrier_fees') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-xl-6 mb-3">
                                                <div wire:ignore>
                                                    <label for="validationCustom01">Choose Agent</label>
                                                    <select data-type="agent" id="agent_id" wire:model="agent_id" class="form-control select select2-show-search form-select select-entries" data-placeholder="Choose one">
                                                        <option selected value="">Choose Agent</option>
                                                        @foreach($agents as $agent)
                                                            <option value="{{ $agent->id }}"> {{ $agent->company_name }}</option>
                                                        @endforeach
                                                        <option value="add_new_agent_modal">+ Add new Agent</option>
                                                    </select>
                                                </div>
                                                @error('agent_id') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                            
                                            <div class="col-xl-6 mb-3">
                                                <label for="validationCustom01">Agent fees in {{ $company->currency }}</label>
                                                <input wire:model.live="agent_fees" type="text" class="form-control" id="validationCustom01" value="" 
                                                    value="">
                                                @error('agent_fees') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-xl-6 mb-3">
                                                <label for="validationCustom01">Extra fees</label>
                                                <input wire:model.live="extra_fees" type="text" class="form-control" id="validationCustom01" placeholder="ex. DHL, Insurance, ect." 
                                                    value="">
                                                    @error('extra_fees') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                            <div class="col-xl-6 mb-3">
                                                <label for="validationCustom01">Extra fees in {{ $company->currency }}</label>
                                                <input wire:model.live="extra_fees_eur" type="text" class="form-control" id="validationCustom01" value="" 
                                                    value="">
                                                    @error('extra_fees_eur') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-xl-12 mb-3 text-center">
                                                <a href="javascript:;" wire:click="addFee" class="text-primary float-right"><i class="fe fe-plus me-2"></i>Add Other Fee</a>
                                            </div>                                        
                                            @foreach($additional_fees as $index => $fee)
                                                <div class="col-xl-5 mb-3">
                                                    <label for="validationCustom01">Other Fees Name</label>
                                                    <input wire:model="additional_fees.{{ $index }}.name" type="text" class="form-control" placeholder="Fee Name">
                                                    @error('additional_fees.'.$index.'.name') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                                <div class="col-xl-5 mb-3">
                                                    <label for="validationCustom01">Other Fees Amount</label>
                                                    <input wire:model.live="additional_fees.{{ $index }}.amount" type="number" step=".01" class="form-control" placeholder="Amount">
                                                    @error('additional_fees.'.$index.'.amount') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                                <div class="col-xl-2 mb-3" style="margin-top:35px">
                                                    <a href="javascript:;" wire:click="removeFee({{ $index }})" class="text-danger text-right float-right mt-4">Remove</a>
                                                </div>
                                            @endforeach
                                        </div>
                                        
                                        
                                </div>
                            </div>
                        </div>


                        <div class="col-xl-12 col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class=" row mb-4">
                                        <button type="submit" class="btn btn-info" wire:loading.attr='disabled'>Save Reference</button>
                                       </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
                @endif

            </div>
            <!-- CONTAINER END -->
        </div>
   
    <livewire:database-entries-create/>
    <livewire:freights/>
</div>

@script()
<script>
$(document).ready(function(){
    $('.form-select').select2();

    $('#destination_id').on('change', function(e) {
        $wire.$set('destination_id', $(this).val());
        let destination_value = $(this).val();
        if(destination_value === 'add_destination') {
            $('#showDestinationModal').modal('show');
        }        
    });  
    $('#carrier_id').on('change', function(e) {
        $wire.$set('carrier_id', $(this).val());
    });         
    $('#agent_id').on('change', function(e) {
        $wire.$set('agent_id', $(this).val());
    });    
    const select2 = $('.select-entries').select2();
    select2.on('change', function(e) {
        let entryValue = $(this).val();
        let entryType = $(this).data('type');
        let entryEvent = 'show' + entryType.charAt(0).toUpperCase() + entryType.slice(1) + 's'; 
        if (entryValue === 'add_new_' + entryType + '_modal') {
            $('#' + 'add' + entryType.charAt(0).toUpperCase() + entryType.slice(1) + 'Modal').modal('show');
        } else {
            $wire.dispatch(entryEvent, {value: entryValue});
        }
    });

    $('.select-freight-type').on('change', function(e) {
        let freightValue = $(this).val();
        if(freightValue === 'add_new_freight_type') {
            $('#showFreightModal').modal('show');
        } else {
            $wire.$set('freight_type_id', freightValue);
        }
    });    

    Livewire.on('freightTypeCreated', (event) => {
        var id = event[0].id;
        var name = event[0].name;
        var newOption = new Option(name, id, true, true);
        $('#freight_type_id').append(newOption).trigger('change');
        $wire.$set('freight_type_id', id);        
    }); 

    Livewire.on('destinationCreated', (event) => {
        var id = event[0].id;
        var name = event[0].name;
        var newOption = new Option(name, id, true, true);
        $('#destination_id').append(newOption).trigger('change');
        $wire.$set('destination_id', id);        
    });    
    
    Livewire.on('databaseEntryCreated', (event) => {
        var name = event[0].name;
        var id = event[0].id;
        var type = event[0].type;
        var field = $('#'+type+'_id');
        var newOption = new Option(name, id, true, true);
        field.append(newOption).trigger('change');
        $wire.$set(type+'_id', id);
    });      
});



  // Consider more specific event listeners:
  $wire.on('showClients', function() {
    initializeSelect2();
  });

function initializeSelect2() {
const select2Entries = $('.select-entries');
const existingSelect2 = select2Entries.data('select2');

if (existingSelect2) {
    existingSelect2.select2('destroy');
}

select2Entries.select2();
}  

$wire.on('clientSearchUpdated', function(data) {
  // Update the select field options here based on data.filteredClients
  const selectElement = document.querySelector('.select-entries');
  selectElement.innerHTML = ''; // Clear existing options

  data.filteredClients.forEach(client => {
    const option = document.createElement('option');
    option.value = client.id;
    option.text = client.firstname + ' ' + client.lastname;
    selectElement.appendChild(option);
  });
});
</script>
@endscript

