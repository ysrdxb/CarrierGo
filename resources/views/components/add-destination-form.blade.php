@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><span class="fe fe-x"></span></button>
</div>
@endif
<form wire:submit.prevent="saveDestination">
    <div class="row">
        <div class="col-lg-12">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" wire:model="name" required>
                @error('name') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" wire:click="entryType = 'client'">Save</button>
    </div>
</form>
