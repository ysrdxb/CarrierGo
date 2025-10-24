<div>
    <div class="main-content mt-0 hor-content">
        <div class="side-app">
            <div class="main-container container-fluid" style="max-width:85% !important;">
                <div class="page-header">
                    <h1 class="page-title">Bank Details</h1>
                </div>
                <div class="row">
                    @include('components.message')                              
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <button wire:click="addNewBankDetail" class="btn btn-primary mb-4"><i class="fe fe-plus"></i> Add New Bank Detail</button>
                                @csrf
                                <div class="table-responsive">
                                    <table class="table table-bordered border text-nowrap mb-0" id="new-edit">
                                        <thead>
                                            <tr>
                                                <th>Company</th>
                                                <th>Bank Name</th>                                                
                                                <th>IBAN</th>
                                                <th>SWIFT Code</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($this->bankDetails as $bankDetail)
                                                <tr wire:key="bank-detail-{{ $bankDetail->id }}">
                                                    <td>
                                                        @if($bankDetail->editing)
                                                            <input class="form-control" type="text" wire:model="editingBankDetail.company_name">
                                                        @else
                                                            {{ $bankDetail->company_name }}
                                                        @endif                                                        
                                                    </td>                                                    
                                                    <td>
                                                        @if($bankDetail->editing)
                                                            <input class="form-control" type="text" wire:model="editingBankDetail.bank_name">
                                                        @else
                                                            {{ $bankDetail->bank_name }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($bankDetail->editing)
                                                            <input class="form-control" type="text" wire:model="editingBankDetail.iban">
                                                        @else
                                                            {{ $bankDetail->iban }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($bankDetail->editing)
                                                            <input class="form-control" type="text" wire:model="editingBankDetail.swift_code">
                                                        @else
                                                            {{ $bankDetail->swift_code }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($bankDetail->trashed())
                                                            <button wire:click="restoreBankDetail({{ $bankDetail->id }})" class="btn btn-success btn-sm"><span class="fe fe-refresh-cw"></span></button>
                                                        @else
                                                            @if($bankDetail->editing)
                                                                <button wire:click.defer="saveBankDetail({{ $bankDetail->id }})" class="btn btn-primary btn-sm"><span class="fe fe-check-circle"></span></button>
                                                                <button wire:click="cancelEdit({{ $bankDetail->id }})" class="btn btn-danger btn-sm"><span class="fe fe-x-circle"></span></button>
                                                            @else
                                                                <button wire:click="editBankDetail({{ $bankDetail->id }})" class="btn btn-primary btn-sm"><span class="fe fe-edit"></span></button>
                                                                <button wire:click.prevent="confirmDelete({{ $bankDetail->id }})" class="btn btn-danger btn-sm"><span class="fe fe-trash-2"></span></button>
                                                            @endif
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{ $data->links() }}
            </div>
        </div>
    </div>
</div>
@push('script')
<script>
// Add this script in your Blade template or in a separate JavaScript file
window.addEventListener('show-confirm-delete', event => {
    
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            Livewire.dispatch('deleteConfirmed');
        }
    });
});

</script>
@endpush