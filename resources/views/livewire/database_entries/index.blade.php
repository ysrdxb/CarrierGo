<div>
    <div class="main-content mt-0 hor-content">
        <div class="side-app">
            <div class="main-container container"><br><br>
                <div class="row row-sm">
                    <div class="col-xl-6 col-lg-6">
                        <div class="card-header">
                            <h3 class="card-title">Database</h3>
                        </div>
                    </div>
                </div>

                <div class="row row-sm">
                    <div class="col-xl-6 col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group" wire:ignore>
                                    <select class="form-control form-select select2" wire:model="selectedEntry">
                                        <option value="">Choose</option>
                                        <option value="client">Clients</option>
                                        <option value="consignee">Consignees</option>
                                        <option value="merchant">Merchants</option>
                                        <option value="agent">Agents</option>
                                        <option value="carrier">Carriers</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <a class="btn btn-primary" wire:click="createEntry" data-bs-toggle="modal" data-bs-target="#fullscreenmodal" href="#" role="button" wire:click="create">+ Add new data</a>
                                </div>
                                <div class="form-group"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9"></div>
                    <div class="col-lg-3 pull-right">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Search..." wire:model.live="search">
                        </div>
                    </div>
                </div>

                <div class="row row-sm">
                    @include('components.message')
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered text-nowrap border-bottom" id="responsive-datatable">
                                        <thead>
                                            <tr>
                                                <th class="wd-15p border-bottom-0">#</th>
                                                <th class="wd-15p border-bottom-0">Entry ID</th>
                                                <th class="wd-15p border-bottom-0">Group ID</th>
                                                <th class="wd-15p border-bottom-0">Type</th>
                                                <th class="wd-15p border-bottom-0">Name</th>
                                                <th class="wd-15p border-bottom-0">Street</th>
                                                <th class="wd-20p border-bottom-0">Zip Code</th>
                                                <th class="wd-15p border-bottom-0">City</th>
                                                <th class="wd-10p border-bottom-0">E-Mail</th>
                                                <th class="wd-25p border-bottom-0">Phone No.</th>
                                                <th class="wd-25p border-bottom-0">Details</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $i = 1; @endphp
                                            @foreach($data as $row)
                                            <tr>
                                                <td>{{ $i++ }}</td>
                                                <td>{{ $row->id }}</td>
                                                <td>{{ $row->entry_group }}</td>
                                                <td>{{ ucfirst($row->database_type) }}</td>
                                                <td>{{ $row->company_name }}</td>
                                                <td>{{ $row->street }}</td>
                                                <td>{{ $row->zip_code }}</td>
                                                <td>{{ $row->city }}</td>
                                                <td>{{ $row->email }}</td>
                                                <td>{{ $row->phone }}</td>
                                                <td>
                                                    <a class="btn text-primary" wire:click="edit({{$row->id}})" data-bs-toggle="modal" data-bs-target="#fullscreenmodal" href="#" role="button"><span class="fa fa-edit"></span></a>
                                                    <a class="btn text-danger btn-sm" data-bs-toggle="tooltip" data-bs-original-title="Delete" x-on:click="
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
                                                            $wire.delete({{ $row->id }});
                                                        }
                                                    });
                                                "><span data-bs-toggle="modal" data-bs-target="#smallmodal" class="fa fa-trash"></span></a>
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

                <!-- End Row -->
            </div>
        </div>
    </div>

    @include('components.add-database-entries-modal')
    @include('components.add-destination-freighttype-modal')
</div>
@push('script')
<script>
    $('.select2').select2().on('change', function (e) {
        @this.set('selectedEntry', $(this).val());
    });
</script>
@endpush
