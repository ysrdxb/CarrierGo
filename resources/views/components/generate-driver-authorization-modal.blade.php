<div class="modal fade" id="driverauth" tabindex="-1" role="dialog" wire:ignore.self>
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <form class="form-horizontal" wire:submit.prevent="saveDriverAuthorization">
                <div class="modal-header">
                    <h5 class="modal-title">Generate Driver authorization</h5>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        @include('components.message')
                        @if (session('driver_message'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('driver_message') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><span class="fe fe-x"></span></button>
                        </div>
                        @endif                        
                            <div class="row mb-4">
                                <label class="col-md-3 form-label">Driver Name</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" wire:model="driver_name">
                                    @error('driver_name') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label class="col-md-3 form-label">Plates No.</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" wire:model="plate_no">
                                    @error('plate_no') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label class="col-md-3 form-label">Add date</label>
                                <div class="col-md-9">
                                    <input type="date" wire:model="add_date" class="form-control" placeholder="Add Date">
                                    @error('add_date') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label class="col-md-3 form-label">Vehicle FIN No.</label>
                                <div class="col-md-9">
                                    <select wire:model="freight_vehicle_fin" class="form-control select2">
                                        <option value="">Select FIN#</option>
                                        @foreach($reference->freights as $fr)
                                            <option value="{{ $fr->id }}">{{ $fr->vehicle_fin }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Generate driver authorization</button>
                </div>
            </form>
        </div>
    </div>
</div>
