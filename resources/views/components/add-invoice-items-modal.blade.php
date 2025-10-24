<div class="modal fade" id="addServiceModal" tabindex="-1" role="dialog" wire:ignore.self>
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $editMode ? 'Edit' : 'Add' }} service fees</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if (session('add_item_message'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('add_item_message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <form wire:submit.prevent="submitForm">
                    <div class="row mb-4">
                        <label class="col-md-3 form-label">Name</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" wire:model="item_name">
                            @error('item_name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>                    
                    <div class="row mb-4">
                        <label class="col-md-3 form-label">DESCRIPTION</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" wire:model="item_description">
                            @error('item_description') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-md-3 form-label">QTY</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" wire:model="item_quantity">
                            @error('item_quantity') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-md-3 form-label">UNIT PRICE in {{ session()->has('currency') ? session('currency') : '€' }}</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" wire:model="item_price">
                            @error('item_price') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">{{ $editMode ? 'Update' : 'Add' }} service</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
