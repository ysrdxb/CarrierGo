<div class="modal fade" id="assignReferenceModal" tabindex="-1" role="dialog" wire:ignore.self>
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assign References to Invoice</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form class="form-horizontal" wire:submit.prevent="assignReferences">
                <div class="modal-body">
                    <div class="card-body">
                        @include('components.message')
                        @if (session('assign_message'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('assign_message') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><span class="fe fe-x"></span></button>
                        </div>
                        @endif
                        <div class="row mb-4">
                            <label class="col-md-3 form-label">Select References</label>
                            <div class="col-md-9">
                                <select class="form-control select2" multiple wire:model="selectedReferences">
                                    @foreach($references as $reference)
                                        <option value="{{ $reference->id }}" {{ in_array($reference->id, $selectedReferences) ? 'selected' : '' }}>
                                            {{ $reference->reference_no }}
                                        </option>
                                    @endforeach
                                </select>                                
                                @error('selectedReferences') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Assign References</button>
                </div>
            </form>
        </div>
    </div>
</div>
