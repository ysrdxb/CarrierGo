<div>
    <div class="main-content mt-0 hor-content">
        <div class="side-app">
            <div class="main-container container">                
                <div class="page-header">
                    <h1 class="page-title"></h1>
                </div>
                <div class="row">
                    <div class="col-xl-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Add incomming invoice</h4>
                            </div>
                            <div class="card-body">
                                @include('components.message') 
                                <!-- Form Start -->
                                <form wire:submit.prevent="saveEntry" method="post" class="card">
                                    <div class="card-body">
                                        <!-- File Upload -->
                                        <div class="form-group col-xl-6 mb-3">
                                            <input wire:model="file" type="file" class="form-control @error('file') is-invalid @enderror">
                                            @error('file') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                        </div>
                                        <!-- Client Reference Number -->
                                        <div class="form-group col-xl-6 mb-3">
                                            <label for="client_ref_no">Client Ref/No.</label>
                                            <input wire:model="client_ref_no" type="text" class="form-control @error('client_ref_no') is-invalid @enderror" id="client_ref_no">
                                            @error('client_ref_no') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                        </div>
                                        <!-- Document Type -->
                                        <div class="form-group col-xl-6 mb-3">
                                            <div wire:ignore>
                                                <label for="doc_type">Doc Type</label>
                                                <select wire:model="doc_type" class="form-control form-select select2" id="doc_type">
                                                    <option value="">Choose one</option>
                                                    <option value="Invoice">Invoice</option>
                                                    <option value="Proforma">Proforma</option>
                                                    <option value="Credit note">Credit note</option>
                                                </select>
                                            </div>
                                            @error('doc_type') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                        </div>

                                        <div class="form-row mb-4">
                                            
                                            <div class="form-group col-md-4 mb-0">
                                                <div class="form-group">
                                                    <label class="form-label">Receive invoice</label>
                                                    <div class="input-group">
                                                <div class="input-group-text">
                                                    <i class="fa fa-calendar tx-16 lh-0 op-6"></i>
                                                </div><input wire:model="invoice_date" type="date" class="form-control @error('invoice_date') is-invalid @enderror" placeholder="MM/DD/YYYY">
                                                @error('invoice_date') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                            </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-4 mb-0">
                                                <div class="form-group">
                                                    <label class="form-label">Invoice date</label>
                                                    <div class="input-group">
                                                <div class="input-group-text">
                                                    <i class="fa fa-calendar tx-16 lh-0 op-6"></i>
                                                </div><input wire:model="receive_date" type="date" class="form-control @error('receive_date') is-invalid @enderror" placeholder="MM/DD/YYYY">
                                                @error('receive_date') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                            </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-4 mb-0">
                                                <div class="form-group">
                                                    <label class="form-label">Payment terms</label>
                                                    <div class="input-group">
                                                <div class="input-group-text">
                                                    <i class="fa fa-calendar tx-16 lh-0 op-6"></i>
                                                </div><input wire:model="payment_date" type="date" class="form-control @error('payment_date') is-invalid @enderror" id="payment_date" placeholder="MM/DD/YYYY">
                                                @error('payment_date') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                            </div>
                                                </div>
                                            </div>

                                            <div class="form-group col-md-12 mb-4">
                                                <div wire:ignore>
                                                    <label class="col-md-3 form-label">Select Client/Agent</label>
                                                    <select class="form-control form-select select2" wire:model="client" id="client_id">
                                                        <option value="">Select Client</option>
                                                        @foreach($clients as $client)
                                                            <option value="{{ $client->id }}">
                                                                {{ $client->company_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>              
                                                </div>                  
                                                @error('client') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>

                                        </div>

                                        <!-- Submit Button -->
                                        <div class="form-group col-xl-12 mb-0">
                                            <button type="submit" class="btn btn-primary">Upload</button>
                                        </div>
                                    </div>
                                </form>
                                <!-- Form End -->


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>    
</div>
@script()
<script>
$(document).ready(function(){    
    $('.form-select').select2();
    $('#doc_type').on('change', function(e) {
        $wire.$set('doc_type', $(this).val());
    });     
    $('#client_id').on('change', function(e) {
        $wire.$set('client', $(this).val());
    });     
});
</script>
@endscript