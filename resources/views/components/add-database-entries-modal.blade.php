<div class="modal fade" id="fullscreenmodal" tabindex="-1" role="dialog" wire:ignore.self>
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Database Entry</h5>
                <button class="btn-close me-1" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row row-sm">
                    <div class="col-lg-12">
                        @if (session('success_client'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success_client') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><span class="fe fe-x"></span></button>
                        </div>
                        @endif
                        
                        <form wire:submit.prevent="saveEntry" wire:loading.attr="disabled">
                        @if($id)
                            <input type="hidden" name="id" value="{{$id}}">
                        @endif
                        <div class="row">
                            <div class="col-lg-12 col-md-12">
                                <div class="card">
                                    @foreach ($errorMessages as $errors)
                                        @foreach ($errors as $error)
                                            <div class="alert alert-danger">{{ $error }}</div>
                                        @endforeach
                                    @endforeach         
                                    @error('entryTypes') <div class="alert alert-danger">{{ $message }}</div> @enderror

                                    @if($editMode)
                                    <div class="row"> 
                                        <div class="col-lg-12 col-md-6">
                                            <div class="card-header">                                       
                                                <div class="form-group" wire:loading.ignore>                                 
                                                    <label class="label">Select Data Type ({{ $database_type }})</label>
                                                    <div class="selectgroup">
                                                        @foreach (['client', 'consignee', 'merchant', 'agent', 'carrier'] as $type)
                                                        <label class="selectgroup-item" wire:ignore>
                                                            <input wire:change="selectedEntries('{{ $type }}')" wire:model.defer="entryType" type="radio" name="entryType" value="{{ $type }}" class="selectgroup-input">
                                                            <span class="selectgroup-button">{{ ucfirst($type) }}</span>
                                                        </label>
                                                        @endforeach
                                                    
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @else
                                    <div class="row"> 
                                        <div class="col-lg-12 col-md-6">
                                            <div class="card-header">                                       
                                                <div class="form-group" wire:ignore>                                 
                                                    <label class="form-label">Select Data Type* (Multiple Selection Possible)</label>
                                                    <div class="selectgroup selectgroup-pills">
                                                        @foreach (['client', 'consignee', 'merchant', 'agent', 'carrier'] as $type)
                                                        <label class="selectgroup-item">
                                                            <input wire:change="selectedEntries('{{ $type }}')" type="checkbox" wire:model.defer="entryTypes" name="entryTypes[]" value="{{ $type }}" class="selectgroup-input">
                                                            <span class="selectgroup-button">{{ ucfirst($type) }}</span>
                                                        </label>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6">
									<div class="mb-3">
										<label for="company" class="form-label">Name <span class="text-red">*</span></label>
										<input type="text" class="form-control" id="company" wire:model.defer="company_name" required>
										@error('company_name') <span class="text-danger">{{ $message }}</span> @enderror
									</div>
                                    <div class="mb-3">
                                        <label for="vat_no" class="form-label">VAT No.</label>
                                        <input type="text" class="form-control" id="vat_no" wire:model.defer="vat_no">
                                        @error('vat_no') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="street" class="form-label">Street</label>
                                        <input type="text" class="form-control" id="street" wire:model.defer="street" required>
                                        @error('street') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="street_no" class="form-label">Street No.</label>
                                        <input type="text" class="form-control" id="street_no" wire:model.defer="street_no" required>
                                        @error('street_no') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="zip_code" class="form-label">ZIP Code</label>
                                        <input type="text" class="form-control" id="zip_code" wire:model.defer="zip_code" required>
                                        @error('zip_code') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="country" class="form-label">Country</label>
                                        <input type="text" class="form-control" id="country" wire:model.defer="country" required>
                                        @error('country') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="city" class="form-label">City</label>
                                        <input type="text" class="form-control" id="city" wire:model.defer="city" required>
                                        @error('city') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" wire:model.defer="email" required>
                                        @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="email_2" class="form-label">Email 2</label>
                                        <input type="email" class="form-control" id="email_2" wire:model.defer="email_2">
                                        @error('email_2') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Phone</label>
                                        <input type="text" class="form-control" id="phone" wire:model.defer="phone" required>
                                        @error('phone') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="phone_2" class="form-label">Phone 2</label>
                                        <input type="text" class="form-control" id="phone_2" wire:model.defer="phone_2">
                                        @error('phone_2') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Save Entry</button>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
