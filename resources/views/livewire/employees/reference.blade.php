<div>
    <div class="main-content mt-0 hor-content">
        <div class="side-app">
            <div class="main-container container-fluid" style="max-width:85% !important;">
                <div class="page-header">
                    <h1 class="page-title">Edit Reference Numbers</h1>
                </div>
                <div class="row">
                    @include('components.message')                              
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                @csrf
                                <div class="table-responsive">
                                    <table class="table table-bordered border text-nowrap mb-0" id="new-edit">
                                        <thead>
                                            <tr>
                                                <th>Reference</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($users as $user)
                                                <tr wire:key="user-{{ $user->id }}">
                                                   
                                                    <td>
                                                        @if($user->editing)
                                                            <input class="form-control" type="text" wire:model="editingUser.reference">                                                            
                                                        @else
                                                            {{ $user->reference ? $user->reference->number_range : '' }}
                                                        @endif                                                        
                                                    </td>
                                                   

                                                    <td>
                                                        @if($user->editing)
                                                            <button wire:click.defer="saveUser({{ $user->id }})" class="btn btn-primary btn-sm"><span class="fe fe-check-circle"></span></button>
                                                            <button wire:click="cancelEdit({{ $user->id }})" class="btn btn-danger btn-sm"><span class="fe fe-x-circle"></span></button>
                                                        @else
                                                            <button wire:click="editUser({{ $user->id }})" class="btn btn-primary btn-sm"><span class="fe fe-edit"></span></button>
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
