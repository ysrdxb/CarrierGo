@if (session('success_consignee'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success_consignee') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><span class="fe fe-x"></span></button>
</div>
@endif
<form wire:submit.prevent="saveEntry" wire:loading.attr="disabled">
    <div class="row">
        <div class="col-lg-6">
            <div class="mb-3">
                <label for="company" class="form-label">Name</label>
                <input type="text" class="form-control" id="company" wire:model="company_name" required>
                @error('company_name') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="mb-3">
                <label for="vat_no" class="form-label">VAT No.</label>
                <input type="text" class="form-control" id="vat_no" wire:model="vat_no" required>
                @error('vat_no') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="mb-3">
                <label for="street" class="form-label">Street</label>
                <input type="text" class="form-control" id="street" wire:model="street" required>
                @error('street') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="mb-3">
                <label for="street_no" class="form-label">Street No.</label>
                <input type="text" class="form-control" id="street_no" wire:model="street_no" required>
                @error('street_no') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="mb-3">
                <label for="zip_code" class="form-label">ZIP Code</label>
                <input type="text" class="form-control" id="zip_code" wire:model="zip_code" required>
                @error('zip_code') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="col-lg-6">
            <div class="mb-3">
                <label for="country" class="form-label">Country</label>
                <input type="text" class="form-control" id="country" wire:model="country" required>
                @error('country') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="mb-3">
                <label for="city" class="form-label">City</label>
                <input type="text" class="form-control" id="city" wire:model="city" required>
                @error('city') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" wire:model="email" required>
                @error('email') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="mb-3">
                <label for="email_2" class="form-label">Email 2</label>
                <input type="email" class="form-control" id="email_2" wire:model="email_2">
                @error('email_2') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" class="form-control" id="phone" wire:model="phone" required>
                @error('phone') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="mb-3">
                <label for="phone_2" class="form-label">Phone 2</label>
                <input type="text" class="form-control" id="phone_2" wire:model="phone_2">
                @error('phone_2') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <input type="hidden" wire:model="entryType" value="consignee">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" wire:click="entryType = 'consignee'">Save Consignee</button>
    </div>
</form>
