@if(isset($invoice) && $invoice)
<div class="modal fade" id="addReceiptModal" tabindex="-1" role="dialog" wire:ignore.self>
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Receipt to Invoice</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form class="form-horizontal" wire:submit.prevent="uploadReceipt({{ $invoice->id }})">
                <div class="modal-body">
                    <div class="card-body">
                        @include('components.message')
                        @if (session('upload_message'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('upload_message') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><span class="fe fe-x"></span></button>
                        </div>
                        @endif
                        <div class="row mb-4">
                            <div class="form-group col-xl-12 mb-3">
                                <label for="">Upload Receipt</label>
                                <input wire:model="file" type="file" class="form-control @error('file') is-invalid @enderror">
                                @error('file') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>                            
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif