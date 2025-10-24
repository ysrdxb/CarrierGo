<div>
    <div class="main-content mt-0 hor-content">
        <div class="side-app">
            <div class="main-container container-fluid" style="max-width:85% !important;">
                <div class="page-header">
                    <h1 class="page-title">Permissions</h1>
                </div>
                <div class="row">
                    @include('components.message')                              
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <button wire:click="addNewPermission" class="btn btn-primary mb-4">Add New Permission</button>
                                @csrf
                                <div class="table-responsive">
                                    <table class="table table-bordered border text-nowrap mb-0" id="new-edit">
                                        <thead>
                                            <tr>
                                                <th>Permission Name</th>
                                                <th>Assigned Roles</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($this->permissions as $permission)
                                                <tr wire:key="permission-{{ $permission->id }}">
                                                    <td>
                                                        @if($editingPermissionId === $permission->id)
                                                            <input type="text" class="form-control" wire:model="editingPermission.name">
                                                        @else
                                                            {{ $permission->name }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($editingPermissionId === $permission->id)
                                                            <select class="select2 form-control" wire:model="editingPermission.roles" multiple>
                                                                @foreach($roles as $roleId => $roleName)
                                                                    <option value="{{ $roleId }}">{{ $roleName }}</option>
                                                                @endforeach
                                                            </select>
                                                        @else
                                                            @foreach($permission->roles as $role)
                                                                {{ $role->name }},
                                                            @endforeach
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($editingPermissionId === $permission->id)
                                                            <button wire:click.defer="savePermission({{ $permission->id }})" class="btn btn-primary btn-sm"><span class="fe fe-check-circle"></span></button>
                                                            <button wire:click="cancelEdit" class="btn btn-danger btn-sm"><span class="fe fe-x-circle"></span></button>
                                                        @else
                                                            <button wire:click="editPermission({{ $permission->id }})" class="btn btn-primary btn-sm"><span class="fe fe-edit"></span></button>
                                                            <button wire:click.prevent="confirmDelete({{ $permission->id }})" class="btn btn-danger btn-sm"><span class="fe fe-trash-2"></span></button>
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
