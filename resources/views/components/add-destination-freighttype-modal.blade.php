<div class="modal fade" id="add-destination-freight-type" tabindex="-1" role="dialog" wire:ignore.self>
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New destination entry</h5>
                <button class="btn-close me-1" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row row-sm">
                    <div class="col-lg-12">
                        @if (session('success_freight_destination'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success_freight_destination') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><span class="fe fe-x"></span></button>
                        </div>
                        @endif
                        
                        <form wire:submit.prevent="saveFreightDestination" wire:loading.attr="disabled">
                        @if($id)
                            <input type="hidden" name="id" value="{{$id}}">
                        @endif
                        <div class="row">
                            @foreach ($errorMessages as $errors)
                                @foreach ($errors as $error)
                                    <div class="alert alert-danger">{{ $error }}</div>
                                @endforeach
                            @endforeach         
                            @error('entryTypes') <div class="alert alert-danger">{{ $message }}</div> @enderror
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label for="zip_code" class="form-label">Destination name</label>                                      
                                        <input type="text" class="form-control" id="entry_name" wire:model="entry_name" required>
                                        @error('entry_name') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                </div>
                            </div>
                            <div class="modal-footer">
                                <input type="hidden" wire:model="entryType" value="client">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary" wire:click="entryType = 'client'">Save Entry</button>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
