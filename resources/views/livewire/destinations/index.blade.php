<div>
    <div class="main-content mt-0 hor-content">
        <div class="side-app">
            <div class="main-container container-fluid" style="max-width:85% !important;">
                <div class="page-header">
                    <h1 class="page-title">Destinations</h1>
                </div>
                <div class="row">
                    @include('components.message')                              
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <button wire:click="create" class="btn btn-primary mb-4"><i class="fe fe-plus"></i> Add New Destination</button>
                                @csrf
                                <div class="table-responsive">
                                    <table class="table table-bordered border text-nowrap mb-0" id="new-edit">
                                        <thead>
                                            <tr>
                                                <th>Name</th>                                                
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($this->destinations as $destination)
                                                <tr wire:key="destination-{{ $destination->id }}">
                                                    <td>
                                                        @if($destination->editing)
                                                            <input class="form-control" type="text" wire:model="editingDestination.name">
                                                        @else
                                                            {{ $destination->name }}
                                                        @endif                                                        
                                                    </td>                                                                                                       
                                                    <td>
                                                        @if($destination->editing)
                                                            <button wire:click.defer="saveDestination({{ $destination->id }})" class="btn btn-primary btn-sm"><span class="fe fe-check-circle"></span></button>
                                                            <button wire:click="cancelEdit({{ $destination->id }})" class="btn btn-danger btn-sm"><span class="fe fe-x-circle"></span></button>
                                                        @else
                                                            <button wire:click="editDestination({{ $destination->id }})" class="btn btn-primary btn-sm"><span class="fe fe-edit"></span></button>
                                                            <button wire:click.prevent="confirmDelete({{ $destination->id }})" class="btn btn-danger btn-sm"><span class="fe fe-trash-2"></span></button>
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