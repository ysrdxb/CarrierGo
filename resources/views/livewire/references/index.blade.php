<div>
    <!--app-content open-->
    <div class="main-content mt-0 hor-content">
        <div class="side-app">

            <!-- CONTAINER -->

            <div class="main-container container"><br><br>
                @include('components.message')

                <div class="row row-sm">
                    <div class="col-xl-6 col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group" wire:ignore>
                                    <select class="form-control form-select select2" wire:model="selectedRefs"
                                    onchange="Livewire.dispatch('updateSelectedRefNo', {value:this.value})">
                                        <option value="">ALL</option>
                                        @foreach($referenceNumbers as $key => $ref)
                                            <option value="{{ $key }}">{{ $ref }}</option>
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
                                            // $currentYear = date('Y');
                                            $currentYearTwoDigits = substr($currentYear, -2);
                                            // $numYears = 5;
                                            // $years = range($currentYear, $currentYear - $numYears);
                                            // foreach ($years as $year) {
                                                echo '<option value="'.$currentYear.'">'.$currentYearTwoDigits.'</option>';
                                            // }
                                        @endphp
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 pull-right">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Search..." wire:model.live="search">
                            </div>
                        </div>
                    </div>
                </div>           
                
                <!-- Row -->
                <div class="row row-sm">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="responsive-datatable" class="table table-bordered text-nowrap border-bottom">
                                        <thead>
                                            <tr>
                                                <th class="wd-15p border-bottom-0">Ref#</th>
                                                <th class="wd-15p border-bottom-0">Created</th>
                                                <th class="wd-15p border-bottom-0">Creator</th>
                                                <th class="wd-15p border-bottom-0">Status</th>
                                                <th class="wd-15p border-bottom-0">Client</th>
                                                <th class="wd-20p border-bottom-0">Consignee</th>
                                                <th class="wd-20p border-bottom-0">Merchant</th>
                                                <th class="wd-15p border-bottom-0">Type</th>
                                                <th class="wd-10p border-bottom-0">FIN No. </th>
                                                <th class="wd-25p border-bottom-0">Destination</th>
                                                <th class="wd-25p border-bottom-0">Action</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($references  as $reference)
                                            <tr>
                                                <td>{{ $reference->reference_no }}</td>
                                                <td>{{ $reference->created_at->format('d-m-Y') }}</td>
                                                <td>{{ $reference->creator->firstname . ' ' .$reference->creator->lastname }}</td>
                                                <td>
                                                    @switch($reference->status)
                                                        @case('New')
                                                            <span class="badge bg-secondary badge-sm me-1 mb-1 mt-1">{{ $reference->status }}</span>
                                                            @break
                                                        @case('Booked')
                                                            <span class="badge bg-success badge-sm me-1 mb-1 mt-1">{{ $reference->status }}</span>
                                                            @break
                                                        @case('Pickup scheduled')
                                                            <span class="badge bg-warning badge-sm me-1 mb-1 mt-1">{{ $reference->status }}</span>
                                                            @break
                                                        @case('Picked up')
                                                            <span class="badge bg-purple badge-sm me-1 mb-1 mt-1">{{ $reference->status }}</span>
                                                            @break
                                                        @case('Port delivered')
                                                            <span class="badge bg-orange badge-sm me-1 mb-1 mt-1">{{ $reference->status }}</span>
                                                            @break
                                                        @case('Ready to ship')
                                                            <span class="badge bg-green badge-sm me-1 mb-1 mt-1">{{ $reference->status }}</span>
                                                            @break
                                                        @case('Shipped')
                                                            <span class="badge bg-blue badge-sm me-1 mb-1 mt-1">{{ $reference->status }} <i class="fas fa-check"></i></span>
                                                            @break
                                                        @case('Arrived')
                                                            <span class="badge bg-red badge-sm me-1 mb-1 mt-1">{{ $reference->status }}</span>
                                                            @break
                                                        @case('Paid')
                                                            <span class="badge bg-red badge-sm me-1 mb-1 mt-1">{{ $reference->status }}</span>
                                                            @break
                                                        @case('Released')
                                                            <span class="badge bg-red badge-sm me-1 mb-1 mt-1">{{ $reference->status }}</span>
                                                            @break
                                                        @default
                                                            <span class="badge bg-gray badge-sm me-1 mb-1 mt-1">{{ $reference->status }}</span>
                                                    @endswitch

                                                </td>
                                                <td>{{ optional($reference->client)->company_name }}</td>
                                                <td>{{ optional($reference->consignee)->company_name }}</td>
                                                <td>{{ optional($reference->merchant)->company_name }}</td>
                                                <td>{{ optional(optional($reference->freights->first())->freightType)->name }}</td>
                                                <td>
                                                    @foreach($reference->freights as $freight)
                                                    {{ $freight->vehicle_fin }}<br>
                                                    @endforeach
                                                </td>
                                                <td>{{ optional(optional($reference->freights->first())->destination)->name }}</td>

                                                <td>
                                                    <a href="{{ route('references.edit', ['referenceId' => encrypt($reference->id)]) }}">
                                                        <span class="fa fa-edit"></span>
                                                    </a>

                                                    <a class="btn text-danger btn-sm" x-on:click="
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
                                                                $wire.deleteReference({{ $reference->id }});
                                                            }
                                                        });
                                                    ">
                                                        @if ($reference->status === 'Cancelled')
                                                            <span class="fa fa-times"></span>
                                                        @else
                                                            <span class="fa fa-trash"></span>
                                                        @endif
                                                    </a>

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
				@if(empty($data))
                {{ $references ->links() }}
				@endif
                <!-- End Row -->

            </div>


        </div>
        <!-- CONTAINER END -->
    </div>
</div>
@push('script')
<script>
    $('.select2').select2();
</script>
@endpush
