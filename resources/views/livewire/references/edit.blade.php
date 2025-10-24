<div>
    @push('head')
    <link href="{{ asset('admin/plugins/formwizard/smart_wizard.css') }}" rel="stylesheet" />
    <link href="{{ asset('admin/plugins/formwizard/smart_wizard_theme_arrows.css') }}" rel="stylesheet" />
    <link href="{{ asset('admin/plugins/formwizard/smart_wizard_theme_circles.css') }}" rel="stylesheet" />
    <link href="{{ asset('admin/plugins/formwizard/smart_wizard_theme_dots.css') }}" rel="stylesheet" />

    @endpush
    <div class="main-content mt-0 hor-content">
        <div class="side-app">

            <!-- CONTAINER -->
            <div class="main-container container">

                <div class="page-header">
                    <h1 class="page-title">{{ $reference->reference_no }} *Edit*</h1>
                </div>
                @include('components.message') 
                <div class="row row-cards">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">
                                    Created:
                                </div>
                            </div>
                            <div class="card-body">
                                <div>{{ $reference->created_at }}<br>by {{ $reference->creator->firstname . ' ' .$reference->creator->lastname }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">
                                    Last edited:
                                </div>
                            </div>
                            <div class="card-body">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#editHistoryModal" class="btn btn-primary">
                                    View History
                                </a>
                            </div>
                        </div>
                    </div>                    
                </div>

                @include('livewire.references.sections.shipment-status')

                <div class="row">
                    <div class="col-xl-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Generate doc:</h4>
                            </div>
                            <div class="card-body">
                                <div class="btn-list">                               
                                    <a href="#" class="btn btn-{{ $this->orders->isNotEmpty() ? 'success' : 'default' }}" data-bs-toggle="modal" data-bs-target="#bookingorder">Booking order</a>
                                    <a href="#" class="btn btn-{{ $this->transport_orders->isNotEmpty() ? 'success' : 'default' }}" data-bs-toggle="modal" data-bs-target="#transportorder">Transport order</a>
                                    <a href="#" class="btn btn-{{ $this->invoices->isNotEmpty() ? 'success' : 'default' }}" data-bs-toggle="modal" data-bs-target="#invoice" wire:click="createInvoice"><i class="fe fe-plus"></i> Create Invoice</a>
                                    <a href="#" class="btn btn-{{ $this->driver_authorizations->isNotEmpty() ? 'success' : 'default' }}" data-bs-toggle="modal" data-bs-target="#driverauth">Driver authorization</a>
                                    <a href="#" class="btn btn-{{ $this->guarantees->isNotEmpty() ? 'success' : 'default' }}" data-bs-toggle="modal" data-bs-target="#guarantee">Guarantee</a>
                                    <a href="#" class="btn btn-default" data-bs-toggle="modal" data-bs-target="#bfu">BFU</a>                                            
                                </div>

                                <br><br>
                                <div class="tags">
                                    @if($transport_orders->isNotEmpty())
                                        @foreach($transport_orders as $row)
                                            <span class="tag file-radius-attachments tag-outline mt-0">
                                                <span><i class="mdi mdi-file-pdf fs-18 px-1 text-danger"></i></span> 
                                                transport_order_{{ $row->reference->reference_no }}.pdf 
                                                <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#transportorder" class="mt-1 ms-2">
                                                    <i class="fe fe-edit"></i>
                                                </a>
                                                <a href="{{ route('transport-orders.download', ['id' => $row->id]) }}" class="mt-1 ms-2">
                                                    <i class="fe fe-download"></i>
                                                </a>
                                                <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#transportorder" class="mt-1 ms-2">
                                                    <i class="fe fe-eye"></i>
                                                </a>
                                                <a href="{{ route('transport-orders.mail', ['id' => $row->id]) }}" class="mt-1 ms-2">
                                                    <i class="fe fe-mail"></i>
                                                </a>
                                                
                                            </span>
                                        @endforeach
                                    @endif

                                    @if($driver_authorizations->isNotEmpty())
                                        @foreach($driver_authorizations as $row)
                                            <span class="tag file-radius-attachments tag-outline mt-0">
                                                <span><i class="mdi mdi-file-pdf fs-18 px-1 text-danger"></i></span> 
                                                driver_authorizations_{{ $row->reference->reference_no }}.pdf 
                                                <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#driverauth" class="mt-1 ms-2">
                                                    <i class="fe fe-edit"></i>
                                                </a>
                                                <a href="{{ route('driver-authorization.download', ['id' => $row->id]) }}" class="mt-1 ms-2">
                                                    <i class="fe fe-download"></i>
                                                </a>
                                                <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#driverauth" class="mt-1 ms-2">
                                                    <i class="fe fe-eye"></i>
                                                </a>
                                                <a href="{{ route('driver-authorization.mail', ['id' => $row->id]) }}" class="mt-1 ms-2">
                                                    <i class="fe fe-mail"></i>
                                                </a>
                                                
                                            </span>
                                        @endforeach
                                    @endif    
                                    
                                    @if($orders->isNotEmpty())
                                        @foreach($orders as $row)
                                            <span class="tag file-radius-attachments tag-outline mt-0">
                                                <span><i class="mdi mdi-file-pdf fs-18 px-1 text-danger"></i></span> 
                                                orders_{{ $row->reference->reference_no }}.pdf 
                                                <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#bookingorder" class="mt-1 ms-2">
                                                    <i class="fe fe-edit"></i>
                                                </a>
                                                <a href="{{ route('order.download', ['id' => $row->id]) }}" class="mt-1 ms-2">
                                                    <i class="fe fe-download"></i>
                                                </a>
                                                <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#bookingorder" class="mt-1 ms-2">
                                                    <i class="fe fe-eye"></i>
                                                </a>
                                                <a href="{{ route('order.mail', ['id' => $row->id]) }}" class="mt-1 ms-2">
                                                    <i class="fe fe-mail"></i>
                                                </a>
                                                
                                            </span>
                                        @endforeach
                                    @endif      
                                    
                                    @if($invoices->isNotEmpty())
                                        @foreach($invoices as $row)
                                            <span class="tag file-radius-attachments tag-outline mt-0">
                                                <span><i class="mdi mdi-file-pdf fs-18 px-1 text-danger"></i></span> 
                                                invoice_{{ $row->invoice_number }}.pdf
                                                <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#invoice" class="mt-1 ms-2" wire:click="editInvoice({{ $row->id }})">
                                                    <i class="fe fe-edit"></i>
                                                </a>
                                                <a href="{{ route('invoice.download', ['id' => $row->id]) }}" class="mt-1 ms-2">
                                                    <i class="fe fe-download"></i>
                                                </a>
                                                <a href="{{ route('invoice.detail', ['id' => $row->id]) }}" class="mt-1 ms-2">
                                                    <i class="fe fe-eye"></i>
                                                </a>
                                                <a href="{{ route('invoice.mail', ['id' => $row->id]) }}" class="mt-1 ms-2">
                                                    <i class="fe fe-mail"></i>
                                                </a>
                                                
                                            </span>
                                        @endforeach
                                    @endif   

                                    @if($guarantees->isNotEmpty())
                                        @foreach($guarantees as $row)
                                            <span class="tag file-radius-attachments tag-outline mt-0">
                                                <span><i class="mdi mdi-file-pdf fs-18 px-1 text-danger"></i></span> 
                                                guarantees_{{ $row->reference->reference_no }}.pdf 
                                                <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#guarantee" class="mt-1 ms-2">
                                                    <i class="fe fe-edit"></i>
                                                </a>
                                                <a href="{{ route('guarantee.download', ['id' => $row->id]) }}" class="mt-1 ms-2">
                                                    <i class="fe fe-download"></i>
                                                </a>
                                                <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#guarantee" class="mt-1 ms-2">
                                                    <i class="fe fe-eye"></i>
                                                </a>
                                                <a href="{{ route('guarantee.mail', ['id' => $row->id]) }}" class="mt-1 ms-2">
                                                    <i class="fe fe-mail"></i>
                                                </a>
                                            
                                            </span>
                                        @endforeach
                                    @endif                                              
                                </div>

                            </div>
                        </div>                    
                    </div>

                </div>

                <div class="row">
                    <div class="col-xl-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Uploaded files</h4>
                            </div>
                            <div class="card-body">
                                
                                <div class="tags">
                                    @if(!empty($reference->incInvoices))
                                        @foreach($reference->incInvoices as $incinvoice)
                                        <span class="tag file-radius-attachments tag-outline mt-0">
                                            <span><i class="mdi mdi-file-pdf fs-18 px-1 text-danger"></i></span> 
                                            inc_invoice_{{ $incinvoice->invoice->reference_no }}.pdf 
                                            <a href="{{ route('incinvoices.edit', $incinvoice->invoice_id )}}" class="mt-1 ms-2">
                                                <i class="fe fe-edit"></i>
                                            </a>
                                            <a class="mt-1 ms-2" href="{{ route('incinvoice.download', $incinvoice->invoice->id) }}"><i class="fe fe-download"></i></a>
                                            <a class="mt-1 ms-2" href="{{ route('incinvoices.detail', $incinvoice->invoice->id) }}"><i class="fe fe-eye"></i></a>        
                                        </span>
                                        @endforeach
                                    @endif              

                                    @if($reference->documents)
                                        @foreach($reference->documents as $document)
                                            <span class="tag file-radius-attachments tag-outline mt-0">
                                                <span><i class="mdi mdi-file-pdf fs-18 px-1 text-danger"></i></span>
                                                {{ $document->file_name }}
                                                <a href="javascript:;" data-bs-toggle="modal" wire:click="toggleDocumentModal({{ $document->id }})" data-bs-target="#addDocumentModal" class="mt-1 ms-2">
                                                    <i class="fe fe-edit"></i>
                                                </a>
                                                <a href="{{ route('document.download', $document->id) }}" class="mt-1 ms-2">
                                                    <i class="fe fe-download"></i>
                                                </a>
                                                <a href="{{ route('document.download', $document->id) }}" class="mt-1 ms-2">
                                                    <i class="fe fe-eye"></i>
                                                </a>
                                                <a href="{{ route('document.mail', $document->id) }}" class="mt-1 ms-2">
                                                    <i class="fe fe-mail"></i>
                                                </a>                                                
                                            </span>
                                        @endforeach
                                    @endif
                                </div>
                                                                        
                                <br>
                                        <form wire:submit.prevent="saveFile" class="card" enctype="multipart/form-data">
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <input wire:model="file" class="form-control" type="file">
                                                </div>
                                                <div class="form-group">
                                                    <input wire:model.defer="filename" class="form-control" type="text" placeholder="Specify filename*" required>
                                                </div>
                                                <div class="form-group col-xl-6 mb-3">
                                                    <label for="document_type">Document type</label>
                                                    <select wire:model.defer="document_type" class="form-control select2-show-search form-select" data-placeholder="Choose one" required>
                                                        <option value="">Choose</option>
                                                        <option value="Dealer Invoice">Dealer invoice</option>
                                                        <option value="Car docs ( Schein)">Car docs ( Schein)</option>
                                                        <option value="Car docs ( Brief)">Car docs ( Brief)</option>
                                                        <option value="Bill of Lading">Bill of Lading</option>
                                                        <option value="Carries invoice">Carries invoice</option>
                                                        <option value="Agent invoice">Agent invoice</option>
                                                        <option value="EUR1">EUR1</option>
                                                        <option value="Consignee data">Consignee data</option>
                                                        <option value="Reciept">Reciept</option>
                                                        <option value="other shipping docs">other shipping docs</option>
                                                        <option value="others">others</option>
                                                    </select>
                                                </div>
                                                <button type="submit" class="btn btn-primary btn-rounded waves-effect waves-light">Upload file</button>
                                            </div>
                                        </form>
                                                                              

                            </div>
                        </div>
                    </div>

                </div>

                <form wire:submit.prevent="update">
                <div class="row">
                    
                    <div class="col-xl-4 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Client details</h4>
                            </div>
                            <div class="card-body">
                                <div class="row mb-4">
                                    <div wire:ignore>
                                        <select id="client_id" class="form-control select select-entries" wire:model="client" data-type="client">
                                            <option>Choose Client</option>
                                            @foreach($clients as $client)
                                            <option value="{{ $client->id }}">{{ $client->company_name }}</option>
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
                                                <option value="{{ $row->id }}">{{ $row->company_name }}</option>
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
                                            <option value="{{ $row->id }}">{{ $row->company_name }}</option>
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
                                        <select id="freight_type_id" wire:model="freight_type_id" class="form-control select select2-show-search form-select select-freight-type" data-placeholder="Choose one" required wire:loading.attr="disabled">
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
                                        <div class="tab-pane{{ $freights_tab === 'vehicle' ? ' active show' : '' }}" id="tab20">
                                            @foreach($vehicleFreights as $index => $vehicleFreight)
                                            <div class="row mb-4">
                                                <label class="col-md-3 form-label">Model</label>
                                                <div class="col-md-9">
                                                    <input wire:model="vehicleFreights.{{ $index }}.vehicle_model" type="text" class="form-control" value="{{ $vehicleFreight['vehicle_model'] }}">
                                                </div>
                                            </div>
                                            <div class="row mb-4">
                                                <label class="col-md-3 form-label">Type</label>
                                                <div class="col-md-9">
                                                    <input wire:model="vehicleFreights.{{ $index }}.vehicle_type" type="text" class="form-control" value="{{ $vehicleFreight['vehicle_type'] }}">
                                                </div>
                                            </div>
                                            <div class="row mb-4">
                                                <label class="col-md-3 form-label">FIN No.</label>
                                                <div class="col-md-9">
                                                    <input wire:model="vehicleFreights.{{ $index }}.vehicle_fin" type="text" class="form-control" value="{{ $vehicleFreight['vehicle_fin'] }}">
                                                </div>
                                            </div>
                                            <a class="text-danger" href="javascript:;" wire:click="removeVehicleFreight({{ $index }})">Remove</a>
                                            @endforeach
                                            <div class="row mb-4 text-center">
                                                <a class="text-primary" href="javascript:;" wire:click="addVehicleFreight">+ Add Freight</a>
                                            </div>
                                        </div>
                                        <div class="tab-pane{{ $freights_tab === 'other_goods' ? ' active show' : '' }}" id="tab21">
                                            @foreach($otherGoodsFreights as $index => $otherGoodsFreight)
                                            <div class="row mb-4">
                                                <label class="col-md-3 form-label">Description</label>
                                                <div class="col-md-9">
                                                    <input wire:model="otherGoodsFreights.{{ $index }}.description" type="text" class="form-control" value="{{ $otherGoodsFreight['description'] }}">
                                                </div>
                                            </div>
                                            <a href="javascript:;" class="text-danger" wire:click="removeOtherGoodsFreight({{ $index }})">Remove</a>
                                            @endforeach
                                            <div class="row mb-4 text-center">
                                                <a href="javascript:;" class="text-primary" wire:click="addOtherGoodsFreight">+ Add another line</a>
                                            </div>
                                        </div>
                                        <div class="row mb-4">
                                            <label class="col-md-3 form-label">Destination</label>
                                            <div class="col-md-9" wire:ignore>
                                                <select id="destination_id" wire:model="destination_id" class="form-control select select2-show-search form-select" data-placeholder="Choose one">
                                                    <option selected value="">Choose Destination</option>
                                                    @foreach($destinations as $destination)
                                                    <option value="{{ $destination->id }}" {{ $destination_id == $destination->id ? 'selected' : '' }}>{{ $destination->name }}</option>
                                                    @endforeach
                                                    <option value="add_destination">+ Add new destination</option>
                                                </select>
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
                                            <input type="text" wire:model="profit" class="form-control" readonly="" value="{{ $profit }}">
                                            
                                            <div class="valid-feedback">Looks good!</div>
                                        </div>
                                        
                                    </div>
                                    <div class="form-row">
                                        <div class="col-xl-6 mb-3" wire:ignore>
                                            <label for="validationCustom01">Choose Carrier</label>
                                            <select data-type="carrier" id="carrier_id" wire:model="carrier_id" class="form-control select select2-show-search form-select select-entries" data-placeholder="Choose one"
                                                required>
                                                <option selected value="">Choose Carrier</option>
                                                @foreach($carriers as $carrier)
                                                <option value="{{ $carrier->id }}"> {{ $carrier->company_name }}</option>
                                                @endforeach
                                                <option value="add_new_carrier_modal">+ add new Carrier </option>
                                            </select>
                                            @error('carrier_id') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="col-xl-6 mb-3">
                                            <label for="validationCustom01">Carrier fees in {{ $company->currency }}</label>
                                            <input wire:model.live.debounce.1000ms="carrier_fees" type="text" class="form-control" id="validationCustom01" value="" 
                                                value="">
                                            @error('carrier_fees') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-xl-6 mb-3" wire:ignore>
                                            <label for="validationCustom01">Choose Agent</label>
                                            <select data-type="agent" id="agent_id" wire:model="agent_id" class="form-control select select2-show-search form-select select-entries" data-placeholder="Choose one" required>
                                                <option selected value="">Choose Agent</option>
                                                @foreach($agents as $agent)
                                                    <option value="{{ $agent->id }}"> {{ $agent->company_name }}</option>
                                                @endforeach
                                                <option value="add_new_agent_modal">+ add new Agent </option>
                                            </select>
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
                                            <input wire:model="extra_fees" type="text" class="form-control" id="validationCustom01" placeholder="ex. DHL, Insurance, ect." 
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
                                                <input wire:model.live="additional_fees.{{ $index }}.amount" type="text" class="form-control" placeholder="Amount">
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
                                    <button type="submit" class="btn btn-info">Save</button>
                                    </div>
                            </div>
                        </div>
                    </div>

                </div>

            </form>
                

            </div>
            <!-- CONTAINER END -->

            
        </div>
   
    <livewire:database-entries-create/>
    <livewire:freights/>

    @include('components.generate-invoice-modal')
    @include('components.generate-booking-modal')
    @include('components.generate-driver-authorization-modal')
    @include('components.generate-guarantee-modal')
    @include('components.generate-transportorder-modal')
    @include('components.edit-reference-histories-modal')
    @include('components.edit-document-modal')


@push('script')
<script src="{{ asset('admin/plugins/accordion-Wizard-Form/jquery.accordion-wizard.min.js') }}"></script>
<script src="{{ asset('admin/plugins/formwizard/fromwizard.js') }}"></script>
<script src="{{ asset('admin/plugins/jquery-steps/jquery.steps.min.js') }}"></script>
<script src="{{ asset('admin/plugins/accordion-Wizard-Form/jquery.accordion-wizard.min.js') }}"></script>
<script src="{{ asset('admin/plugins/formwizard/jquery.smartWizard.js') }}"></script>
<script src="{{ asset('admin/js/form-wizard.js') }}"></script>
@endpush
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
    $('#freight_type_id').on('change', function(e) {
        $wire.$set('freight_type_id', $(this).val());
    });
    $('#destination_id').on('change', function(e) {
        $wire.$set('destination_id', $(this).val());
    });  
    $('#carrier_id').on('change', function(e) {
        if($(this).val() !== 'add_new_carrier') {
            $wire.$set('carrier_id', $(this).val());
        }
    });         
    $('#agent_id').on('change', function(e) {
        if($(this).val() !== 'add_new_agent') {
            $wire.$set('agent_id', $(this).val());
        }        
    }); 
        
    let selectEntries = $('.select-entries').select2();
    selectEntries.on('change', function(e) {
        let entryValue = $(this).val();
        let entryType = $(this).data('type');
        let entryEvent = 'show' + entryType.charAt(0).toUpperCase() + entryType.slice(1) + 's'; 
        if (entryValue === 'add_new_' + entryType + '_modal') {
            $('#' + 'add' + entryType.charAt(0).toUpperCase() + entryType.slice(1) + 'Modal').modal('show');
        } else {
            $wire.dispatch(entryEvent, {value:entryValue});
        }
    });

    let selectFreightType = $('.select-freight-type').select2();
    selectFreightType.on('change', function(e) {
        // @this.set($(this).attr('id'), e.target.value);
        let freightValue = $(this).val();
        if(freightValue === 'add_new_freight_type') {
            $('#showFreightModal').modal('show');
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

$wire.on('afterDomUpdate', function() {
    $('.select-entries').select2();
});

</script>
@endscript
