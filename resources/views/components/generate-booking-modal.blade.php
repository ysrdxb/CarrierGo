<div class="modal fade" id="bookingorder" tabindex="-1" role="dialog" wire:ignore.self>
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Generate booking order</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                
            </div>
            <form class="form-horizontal" wire:submit.prevent="saveOrder">
                <div class="modal-body">
                    <div class="card-body">
                        @include('components.message')
                        @if (session('booking_message'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('booking_message') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><span class="fe fe-x"></span></button>
                        </div>
                        @endif                          
                        <div class="row mb-4">
                            <label class="col-md-3 form-label">Bill of Lading</label>
                            <div class="col-md-9">
                                <label class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" name="bill_of_lading" value="BOL Instruction ASAP" wire:model="bill_of_lading">
                                    <span class="custom-control-label">BOL Instruction ASAP</span>
                                </label>                                
                                <label class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" name="bill_of_lading" value="Express" wire:model="bill_of_lading">
                                    <span class="custom-control-label">Express</span>
                                </label>
                                <label class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" name="bill_of_lading" value="Release after our confirmation" wire:model="bill_of_lading">
                                    <span class="custom-control-label">Release after our confirmation</span>
                                </label>
                                <label class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" name="bill_of_lading" value="Original Bill of Lading" wire:model="bill_of_lading">
                                    <span class="custom-control-label">Original Bill of Lading</span>
                                </label>
                                @error('bill_of_lading') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <label class="col-md-3 form-label">Rate price in {{ session()->has('currency') ? session('currency') : '€' }}</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" wire:model="rate_price">
                                @error('rate_price') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Generate booking order</button>
                </div>
            </form>
        </div>
    </div>
</div>
