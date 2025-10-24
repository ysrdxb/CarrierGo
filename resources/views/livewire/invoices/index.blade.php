<div>
    <br> 
    <div class="main-content mt-0 hor-content">
        <div class="side-app">
            <div class="main-container container">
                <div class="row row-sm">             
                    <div class="col-xl-6 col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group" wire:ignore>
                                    <select class="form-control form-select select2" wire:model="selectedRefs" 
                                    onchange="Livewire.dispatch('updateSelectedRefNo', {ref_no:this.value})">
                                        <option value="">ALL</option>
                                        @foreach($referenceNumbers as $ref)
                                            <option value="{{ $ref }}">{{ $ref->reference_no }}</option>
                                        @endforeach
                                    </select>
                                </div>                                        
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group" wire:ignore>
                                    <select class="form-control form-select select2" wire:model="selectedEntry" onchange="Livewire.dispatch('updateSelectedRef', { value: this.value })">
                                        <option value="">Choose</option>
                                        @php
                                            use Carbon\Carbon;
                                            $currentYear = Carbon::now()->year;
                                            $numYears = 5;
                                            $years = range($currentYear, $currentYear - $numYears);
                                            foreach ($years as $year) {
                                                echo '<option value="'.$year.'">'.$year.'</option>';
                                            }
                                        @endphp
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
                                <div class="table-responsive">
                                    <table class="table table-bordered text-nowrap border-bottom" id="responsive-datatable">
                                        <thead>
                                            <tr>
                                                <th class="wd-15p border-bottom-0">Date</th>
                                                <th class="wd-15p border-bottom-0">Created by</th>
                                                <th class="wd-15p border-bottom-0">Invoice No.</th>
                                                <th class="wd-15p border-bottom-0">Ref. No.</th>
                                                <th class="wd-15p border-bottom-0">Client</th>
                                                <th class="wd-15p border-bottom-0">Destination</th>
                                                <th class="wd-15p border-bottom-0">Status</th>
                                                <th class="wd-25p border-bottom-0">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
										@if($invoices->isNotEmpty())
											@foreach($invoices as $invoice)
												@if(optional($invoice)->reference)
												<tr>
													<td>{{ $invoice->created_at ?? '' }}</td>
													<td>
														{{ optional(optional($invoice->reference)->creator)->firstname ?? '' }}
														{{ optional(optional($invoice->reference)->creator)->lastname ?? '' }}
													</td>
													<td>{{ $invoice->invoice_number ?? '' }}</td>
													<td>{{ optional($invoice->reference)->reference_no ?? '' }}</td>
													<td>
														{{ optional(optional($invoice->reference)->client)->firstname ?? '' }}
														{{ optional(optional($invoice->reference)->client)->lastname ?? '' }}
													</td>
													<td>{{ optional(optional(optional($invoice->reference->freights)->first())->destination)->name ?? '' }}</td>
													<td>{{ optional($invoice->reference)->status ?? '' }}</td>
													<td>
														<a class="btn text-primary btn-sm" data-bs-toggle="tooltip" href="{{ route('invoice.detail', ['id' => $invoice->id]) }}" data-bs-original-title="View"><span class="fa fa-eye"></span></a>
														<a class="btn text-primary btn-sm" data-bs-toggle="tooltip" href="{{ route('invoice.download', ['id' => $invoice->id]) }}" data-bs-original-title="Download"><span class="fa fa-download"></span></a>  
													</td>
												</tr>
												@endif
											@endforeach
										@endif
										
											
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{ $invoices->links() }}
            </div>
        </div>
    </div>
    
</div>
@push('script')
<script>
    $('.select2').select2();
</script>
@endpush