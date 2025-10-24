<div class="modal fade" id="invoice" tabindex="-1" role="dialog" wire:ignore.self>
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Generate new invoice</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                
            </div>
            <form class="form-horizontal" wire:submit.prevent="saveInvoice">
                <div class="modal-body">
                    <div class="card-body">
                        @include('components.message')
                        @if (session('invoice_message'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('invoice_message') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><span class="fe fe-x"></span></button>
                        </div>
                        @endif                         
                        <div class="row mb-4">
                            <label class="col-md-3 form-label">Freight payer*</label>
                            <div class="col-md-9">
                                <label class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" name="freight_payer" wire:model="freight_payer" value="{{ $reference->client_id }}">
                                    <span class="custom-control-label">Client</span>
                                </label>
                                <label class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" name="freight_payer" wire:model="freight_payer" value="{{ $reference->consignee_id }}">
                                    <span class="custom-control-label">Consignee</span>
                                </label>
                                <label class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" name="freight_payer" wire:model="freight_payer" value="{{ $reference->merchant_id }}">
                                    <span class="custom-control-label">Merchant</span>
                                </label>
                                @error('freight_payer') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-md-3 form-label">Offered in {{ session()->has('currency') ? session('currency') : '€' }}</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" wire:model="offered_in_eur">
                                @error('offered_in_eur') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-md-3 form-label">VAT in %</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" wire:model="vat">
                                @error('vat') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-md-3 form-label">Invoice language</label>
                            <div class="col-md-9">
                                <label class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" name="invoice_language" wire:model="invoice_language" value="English">
                                    <span class="custom-control-label">English</span>
                                </label>
                                <label class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" name="invoice_language" wire:model="invoice_language" value="German">
                                    <span class="custom-control-label">German</span>
                                </label>                                
                                @error('invoice_language') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-md-3 form-label">NMH Bank account</label>
                            <div class="col-md-9">
                                @foreach($bank_accounts as $account)
                                <label class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" name="bank_account_id" wire:model="bank_account_id" value="{{ $account->id }}">
                                    <span class="custom-control-label">{{ $account->bank_name }}</span>
                                </label>
                                @endforeach
                                @error('bank_account_id') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Generate invoice</button>
                </div>
            </form>
        </div>
    </div>
</div>
