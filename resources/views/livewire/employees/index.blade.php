<div>
    <div class="main-content mt-0 hor-content">
        <div class="side-app">
            <div class="main-container container-fluid" style="max-width:85% !important;">
                <div class="page-header">
                    <h1 class="page-title">Employee settings</h1>
                </div>
                <div class="row">
                    @include('components.message')                              
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <button wire:click="addNewUser" class="btn btn-primary mb-4"><i class="fe fe-plus"></i> Add New Employee</button>
                                @csrf
                                <div class="table-responsive">
                                    <table class="table table-bordered border text-nowrap mb-0" id="new-edit">
                                        <thead>
                                            <tr>
                                                <th>Firstname</th>
                                                <th>Lastname</th>
                                                <th>Reference</th>
                                                <th>Role</th>
                                                <th>Start date</th>
                                                <th>End date</th>
                                                <th>E-mail</th>
                                                <th>Password</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($users as $user)
                                                <tr wire:key="user-{{ $user->id }}">
                                                    <td>
                                                        @if($user->editing)
                                                            <input class="form-control" type="text" wire:model="editingUser.firstname">
                                                        @else
                                                            {{ $user->firstname }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($user->editing)
                                                            <input class="form-control" type="text" wire:model="editingUser.lastname">                                                            
                                                        @else
                                                            {{ $user->lastname }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($user->editing)
                                                            <input class="form-control" type="text" wire:model="editingUser.reference">                                                            
                                                        @else
                                                            {{ $user->reference ? $user->reference->number_range : '' }}
                                                        @endif                                                        
                                                    </td>
                                                    <td wire:ignore>
                                                        @if($user->editing)
                                                            <select class="select2 form-control select2" wire:model="editingUser.role">
                                                                <option value="">Select</option>
                                                                @foreach($roles as $role)
                                                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        @else
                                                            {{ optional($user->roles->first())->name }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($user->editing)
                                                            <input class="form-control" type="date" wire:model="editingUser.start_date">
                                                        @else
                                                            {{ $user->start_date }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($user->editing)
                                                            <input class="form-control" type="date" wire:model="editingUser.end_date">
                                                        @else
                                                            {{ $user->end_date }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($user->editing)
                                                            <input class="form-control" type="email" wire:model="editingUser.email">
                                                        @else
                                                            {{ $user->email }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($user->editing)
                                                            <input type="password" wire:model="editingUser.password">
                                                        @else
                                                            *****
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($user->trashed())
                                                            <button wire:click="restoreUser({{ $user->id }})" class="btn btn-success btn-sm"><span class="fe fe-refresh-cw"></span></button>
                                                        @else
                                                            @if($user->editing)
                                                                <button wire:click.defer="saveUser({{ $user->id }})" class="btn btn-primary btn-sm"><span class="fe fe-check-circle"></span></button>
                                                                <button wire:click="cancelEdit({{ $user->id }})" class="btn btn-danger btn-sm"><span class="fe fe-x-circle"></span></button>
                                                            @else
                                                                <button wire:click="editUser({{ $user->id }})" class="btn btn-primary btn-sm"><span class="fe fe-edit"></span></button>
                                                                <button wire:click.prevent="confirmDelete({{ $user->id }})" class="btn btn-danger btn-sm"><span class="fe fe-trash-2"></span></button>
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