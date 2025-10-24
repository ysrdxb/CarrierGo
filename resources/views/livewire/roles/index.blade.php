<div>
    <div class="main-content mt-0 hor-content">
        <div class="side-app">
            <div class="main-container container-fluid" style="max-width:85% !important;">
                <div class="page-header">
                    <h1 class="page-title">Roles</h1>
                </div>
                <div class="row">
                    @include('components.message')                              
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <button wire:click="addNewRole" class="btn btn-primary mb-4">Add New Role</button>
                                @csrf
                                <div class="table-responsive">
                                    <table class="table table-bordered border text-nowrap mb-0" id="roles_table">
                                        <thead>
                                            <tr>
                                                <th>Role Name</th>
                                                <th>Assigned Permissions</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($this->roles as $role)
                                                <tr wire:key="role-{{ $role->id }}">
                                                    <td>
                                                        @if($editingRoleId === $role->id)
                                                            <input type="text" class="form-control" wire:model="editingRole.name">
                                                        @else
                                                            {{ $role->name }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($editingRoleId === $role->id)
                                                            <select class="select2 form-control" wire:model="editingRole.permissions" multiple>
                                                                @foreach($permissions as $permissionId => $permissionName)
                                                                    <option value="{{ $permissionId }}">{{ $permissionName }}</option>
                                                                @endforeach
                                                            </select>
                                                        @else
                                                            @foreach($role->permissions as $permission)
                                                                {{ $permission->name }},
                                                            @endforeach
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($editingRoleId === $role->id)
                                                            <button wire:click.defer="saveRole({{ $role->id }})" class="btn btn-primary btn-sm"><span class="fe fe-check-circle"></span></button>
                                                            <button wire:click="cancelEdit" class="btn btn-danger btn-sm"><span class="fe fe-x-circle"></span></button>
                                                        @else
                                                            <button wire:click="editRole({{ $role->id }})" class="btn btn-primary btn-sm"><span class="fe fe-edit"></span></button>
                                                            <button wire:click.prevent="confirmDelete({{ $role->id }})" class="btn btn-danger btn-sm"><span class="fe fe-trash-2"></span></button>
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
