<div>
    <div class="main-content mt-0 hor-content">
        <div class="side-app">    
            <div class="main-container container">
                <br><br>
                <div class="row row-sm">
                    <div class="col-xl-12 col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group" wire:ignore>
                                    <select name="country" class="form-control form-select select2" data-bs-placeholder="Select">
                                        <option value="br" selected="">2024</option>
                                        <option value="cz">2023</option>
                                        <option value="cz">2022</option>
                                        <option value="de">2021</option>
                                        <option value="pl">2020</option>
                                        <option value="pl">2019</option>
                                        <option value="pl">2018</option>
                                        <option value="pl">2017</option>
                                        <option value="pl">2016</option>
                                        <option value="pl">2015</option>
                                        <option value="pl">2014</option>
                                        <option value="pl">2013</option>
                                        <option value="pl">2012</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row row-sm">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                @include('components.message') 
                                <div class="table-responsive">
                                    <table class="table table-bordered text-nowrap border-bottom dataTable no-footer" id="responsive-datatable" role="grid" aria-describedby="responsive-datatable_info">
                                        <thead>
                                            <tr role="row">
                                                <th class="wd-15p border-bottom-0 sorting sorting_asc" tabindex="0" aria-controls="responsive-datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Upload Date: activate to sort column descending" style="width: 92.2125px;">Upload Date</th>
                                                <th class="wd-15p border-bottom-0 sorting" tabindex="0" aria-controls="responsive-datatable" rowspan="1" colspan="1" aria-label="Added by: activate to sort column ascending" style="width: 73.95px;">Added by</th>
                                                <th class="wd-15p border-bottom-0 sorting" tabindex="0" aria-controls="responsive-datatable" rowspan="1" colspan="1" aria-label="Client No.: activate to sort column ascending" style="width: 76.3px;">Client No.</th>
                                                <th class="wd-15p border-bottom-0 sorting" tabindex="0" aria-controls="responsive-datatable" rowspan="1" colspan="1" aria-label="Receive invoice: activate to sort column ascending" style="width: 119.787px;">Receive invoice</th>
                                                <th class="wd-15p border-bottom-0 sorting" tabindex="0" aria-controls="responsive-datatable" rowspan="1" colspan="1" aria-label="Invoice date: activate to sort column ascending" style="width: 95.9625px;">Invoice date</th>
                                                <th class="wd-15p border-bottom-0 sorting" tabindex="0" aria-controls="responsive-datatable" rowspan="1" colspan="1" aria-label="Payment terms: activate to sort column ascending" style="width: 112.988px;">Payment terms</th>
                                                <th class="wd-15p border-bottom-0 sorting" tabindex="0" aria-controls="responsive-datatable" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending" style="width: 102.425px;">Status</th>
                                                <th class="wd-15p border-bottom-0 sorting" tabindex="0" aria-controls="responsive-datatable" rowspan="1" colspan="1" aria-label="Assigned: activate to sort column ascending" style="width: 70.525px;">Assigned</th>
                                                <th class="wd-25p border-bottom-0 sorting" tabindex="0" aria-controls="responsive-datatable" rowspan="1" colspan="1" aria-label="Action: activate to sort column ascending" style="width: 111.162px;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($incomingInvoices as $invoice)
                                            <tr class="odd">
                                                <td class="sorting_1">{{ $invoice->created_at }}</td>
                                                <td>{{ $invoice->creator->firstname . ' ' . $invoice->creator->lastname }}</td>
                                                <td>{{ $invoice->reference_no }}</td>
                                                <td>{{ $invoice->receive_date }}</td>
                                                <td>{{ $invoice->invoice_date }}</td>
                                                <td class="{{ \Carbon\Carbon::parse($invoice->payment_date)->isPast() ? 'text-danger' : '' }}">
                                                    {{ $invoice->payment_date }}
                                                </td>                                                
                                                <td>
                                                    {{ $invoice->status }}

                                                    @if($invoice->status === 'Paid')
                                                        <button data-bs-original-title="Add Receipt" class="btn text-primary btn-sm" data-bs-toggle="modal" wire:click="toggleReceiptModal({{ $invoice->id }})" data-bs-target="#addReceiptModal">
                                                            <span class="fa fa-plus"></span>
                                                        </button>                                                    
                                                        <a class="btn text-primary btn-sm" href="{{ route('incinvoice.document.download', $invoice->id) }}" data-bs-toggle="tooltip" data-bs-original-title="Download Invoice">
                                                            <span class="fa fa-eye "></span>
                                                        </a>
                                                        <a class="btn text-primary btn-sm" href="#" data-bs-toggle="modal" wire:click="toggleReceiptModal({{ $invoice->id }})" data-bs-target="#addReceiptModal"><span class="fa fa-edit"></span></a>
                                                    @else
                                                        <a wire:click="toggleStatus({{ $invoice->id }})" class="btn text-primary btn-sm" data-bs-toggle="tooltip" href="#" data-bs-original-title="Change to {{ $invoice->status === 'Paid' ? 'Unpaid' : 'Paid' }}">
                                                            <span class="fa fa-refresh"></span>
                                                        </a>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $invoice->assigned }} 
                                                    <button class="btn text-primary btn-sm" data-bs-toggle="modal" wire:click="toggleAssignModal({{ $invoice->id }})" data-bs-target="#assignReferenceModal">
                                                        <span class="fa fa-plus"></span>
                                                    </button>
                                                </td>
                                                <td>
                                                    <a class="btn text-primary btn-sm" data-bs-toggle="tooltip" href="{{ route('incinvoices.edit', $invoice->id) }}" data-bs-original-title="Edit"><span class="fa fa-edit"></span></a>
                                                    <a class="btn text-primary btn-sm" data-bs-toggle="tooltip" href="{{ route('incinvoices.detail', $invoice->id) }}" data-bs-original-title="View invoice"><span class="fa fa-eye"></span></a>
                                                    <a class="btn text-primary btn-sm" data-bs-toggle="tooltip" href="{{ route('incinvoice.download', $invoice->id) }}" data-bs-original-title="Download"><span class="fa fa-download"></span></a>
                                                    <a x-on:click="
                                                    Swal.fire({
                                                        title: 'Are you sure?',
                                                        text: 'Do you want to perform this action?',
                                                        icon: 'warning',
                                                        showCancelButton: true,
                                                        confirmButtonColor: '#3085d6',
                                                        cancelButtonColor: '#d33',
                                                        confirmButtonText: 'Yes, Do it!'
                                                    }).then((result) => {
                                                        if (result.isConfirmed) {
                                                            $wire.delete({{ $invoice->id }});
                                                        }
                                                    });
                                                " class="btn text-primary btn-sm" data-bs-toggle="tooltip" href="#" data-bs-original-title="Delete" id="deleteInvoice{{ $invoice->id }}"><span class="fa fa-trash"></span></a>

                                                </td>
                                            </tr>
                                            @endforeach
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{ $incomingInvoices->links() }}
                </div>
            </div>
        </div>
    </div>
    @include('components.inc-invoice-assigned-modal')
    @include('components.inc-invoice-receipt-modal')
</div>
@push('script')
<script>
    $('.select2').select2();
</script>
@endpush