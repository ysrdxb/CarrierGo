<div class="modal fade" id="transportorder" tabindex="-1" role="dialog" wire:ignore.self>
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Transport Order</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                
            </div>
            <form class="form-horizontal" wire:submit.prevent="saveTransportOrder">
                <div class="modal-body">
                    <div class="card-body">
                        @if (session('transport_message'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('transport_message') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><span class="fe fe-x"></span></button>
                        </div>
                        @endif                         
                        <div class="row mb-4">
                            <label class="col-md-3 form-label"><h3>Loading Address:</h3></label>
                            <div class="col-md-9">
                                <label class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" name="transport_type" value="merchant" wire:model.live="transport_type">
                                    <span class="custom-control-label">Merchant</span>
                                </label>
                                @if($transport_type === 'merchant')
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Select Merchant</label>
                                    <div class="col-md-9">
                                        <select class="form-control" wire:model="merchant_id">
                                            <option value="">Select Merchant</option>
                                            @foreach($merchants as $merchant)
                                                <option value="{{ $merchant->id }}">{{ $merchant->firstname . ' ' . $merchant->lastname }}</option>
                                            @endforeach
                                        </select>
                                        @error('merchant_id') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                @endif                                
                                <label class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" name="transport_type" value="new" wire:model.live="transport_type">
                                    <span class="custom-control-label">Add New Loading Address</span>
                                </label>
                                @error('transport_type') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="row mb-4">
                                <label class="col-md-3 form-label">Company Name</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" wire:model="loading_company_name">
                                    @error('loading_company_name') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label class="col-md-3 form-label">Street + No.</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" wire:model="loading_street">
                                    @error('loading_street') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label class="col-md-3 form-label">ZIP + City</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" wire:model="loading_zip_city">
                                    @error('loading_zip_city') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label class="col-md-3 form-label">Contact Name</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" wire:model="loading_contact_name">
                                    @error('loading_contact_name') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label class="col-md-3 form-label">Contact Phone No.</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" wire:model="loading_contact_phone">
                                    @error('loading_contact_phone') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label class="col-md-3 form-label">Latest loading Date</label>
                                <div class="col-md-9">
                                    <input type="date" class="form-control" wire:model="loading_latest_date">
                                    @error('loading_latest_date') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-md-3 form-label"><h3>Unloading Address:</h3></label>
                            <div class="col-md-9">
                                <select class="form-control" wire:model="unloading_address_id">
                                    <option value="">Select Unloading Address</option>
                                    @foreach($unloading_addresses as $address)
                                        <option value="{{ $address->id }}">{{ $address->company_name }}</option>
                                    @endforeach
                                </select>
                                @error('unloading_address_id') <span class="text-danger">{{ $message }}</span> @enderror
                                <a class="pull-right text-primary" href="#" wire:click.prevent="toggleCreateUnloadingAddress">+ Create New Unloading Address</a>
                            </div>
                        </div>
                        @if($create_unloading_address)
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div class="card card-info">
                                        <div class="card-header">
                                            <h4>Create New Unloading Address</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="row mb-4">
                                                <label class="col-md-3 form-label">Company Name</label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" wire:model="unloading_company_name">
                                                    @error('unloading_company_name') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            </div>
                                            <div class="row mb-4">
                                                <label class="col-md-3 form-label">Street + No.</label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" wire:model="unloading_street">
                                                    @error('unloading_street') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            </div>
                                            <div class="row mb-4">
                                                <label class="col-md-3 form-label">ZIP + City</label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" wire:model="unloading_zip_city">
                                                    @error('unloading_zip_city') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            </div>
                                            <div class="row mb-4">
                                                <label class="col-md-3 form-label">Contact Name</label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" wire:model="unloading_contact_name">
                                                    @error('unloading_contact_name') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            </div>
                                            <div class="row mb-4">
                                                <label class="col-md-3 form-label">Contact Phone No.</label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" wire:model="unloading_contact_phone">
                                                    @error('unloading_contact_phone') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            </div>
                                            <div class="row mb-4">
                                                <label class="col-md-3 form-label">Latest Unloading Date</label>
                                                <div class="col-md-9">
                                                    <input type="date" class="form-control" wire:model="unloading_latest_date">
                                                    @error('unloading_latest_date') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-primary" wire:click="saveUnloadingAddress">Save Unloading Address</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="row mb-4">
                            <label class="col-md-3 form-label">Transport Price in {{ session()->has('currency') ? session('currency') : '€' }}</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" wire:model="transport_price_eur">
                                @error('transport_price_eur') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-md-3 form-label">Add Date</label>
                            <div class="col-md-9">
                                <input type="date" class="form-control" wire:model="add_transport_date">
                                @error('add_transport_date') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Generate Transport Order</button>
                </div>
            </form>
        </div>
    </div>
</div>
