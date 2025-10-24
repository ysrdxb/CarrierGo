<div class="modal fade" id="guarantee" tabindex="-1" role="dialog" wire:ignore.self>
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Generate Guarantee</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                
            </div>
            <form class="form-horizontal" wire:submit.prevent="saveGuarantee">
            <div class="modal-body">
                <div class="card-body">
                    @include('components.message')
                    @if (session('guarantee_message'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('guarantee_message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><span class="fe fe-x"></span></button>
                    </div>
                    @endif 
                   
                        <div class="row mb-4">
                            <label class="col-md-3 form-label">EX1</label>
                            <div class="col-md-9">
                                <label class="custom-control custom-radio">
                                    <input type="radio"wire:model="issuer" class="custom-control-input" name="issuer" value="NMH">
                                    <span class="custom-control-label">NMH</span>
                                </label>
                                <label class="custom-control custom-radio">
                                    <input type="radio" wire:model='issuer' class="custom-control-input" name="issuer" value="Merchant">
                                    <span class="custom-control-label">Merchant/Other</span>
                                </label>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-md-3 form-label">Order given</label>
                            <div class="col-md-9">
                                <label class="custom-control custom-radio">
                                    <input wire:model='order_placed_by' type="radio" class="custom-control-input" name="order_placed_by" value="client">
                                    <span class="custom-control-label">Client</span>
                                </label>
                                <label class="custom-control custom-radio">
                                    <input wire:model='order_placed_by' type="radio" class="custom-control-input" name="order_placed_by" value="merchant">
                                    <span class="custom-control-label">Merchant</span>
                                </label>
                                <label class="custom-control custom-radio">
                                    <input wire:model='order_placed_by' type="radio" class="custom-control-input" name="order_placed_by" value="consignee">
                                    <span class="custom-control-label">Consignee</span>
                                </label>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-md-3 form-label">Add date</label>
                            <div class="col-md-9">
                                <input type="date" class="form-control" value="" wire:model='date_displayed'>
                            </div>
                        </div>
                    
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Generate guarantee</button>
            </div>
        </form>
        </div>
    </div>
</div>
