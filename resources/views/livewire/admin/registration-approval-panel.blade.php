<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3 fw-bold">Pending Registration Approvals</h1>
            <p class="text-muted">Review and approve customer registrations</p>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Pending Registrations</h5>
                </div>
                <div class="card-body p-0">
                    @if ($registrations->isEmpty())
                        <div class="alert alert-info mb-0 m-3">
                            All registrations reviewed!
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach ($registrations as $reg)
                                <button 
                                    type="button" 
                                    wire:click="selectRegistration({{ $reg->id }})"
                                    class="list-group-item list-group-item-action p-3 @if($selectedRegistration && $selectedRegistration->id === $reg->id) active @endif"
                                >
                                    <div class="d-flex w-100 justify-content-between align-items-start">
                                        <div class="text-start flex-grow-1">
                                            <h6 class="mb-1"><strong>{{ $reg->company_name }}</strong></h6>
                                            <p class="mb-1 text-muted small">
                                                {{ $reg->firstname }} {{ $reg->lastname }}
                                            </p>
                                            <p class="mb-0 text-muted small">
                                                {{ $reg->email }}
                                            </p>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-warning">{{ ucfirst($reg->subscription_plan) }}</span>
                                            <small class="d-block text-muted mt-1">
                                                {{ $reg->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            @if ($selectedRegistration)
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Registration Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3">Company</h6>
                            <div class="row mb-2">
                                <div class="col-5 text-muted">Name:</div>
                                <div class="col-7"><strong>{{ $selectedRegistration->company_name }}</strong></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-5 text-muted">Domain:</div>
                                <div class="col-7"><strong>{{ $selectedRegistration->domain }}.carriergo.local</strong></div>
                            </div>
                            <div class="row">
                                <div class="col-5 text-muted">Plan:</div>
                                <div class="col-7"><span class="badge bg-info">{{ ucfirst($selectedRegistration->subscription_plan) }}</span></div>
                            </div>
                        </div>

                        <hr>

                        <div class="mb-4">
                            <h6 class="fw-bold mb-3">Contact</h6>
                            <div class="row mb-2">
                                <div class="col-5 text-muted">Name:</div>
                                <div class="col-7"><strong>{{ $selectedRegistration->firstname }} {{ $selectedRegistration->lastname }}</strong></div>
                            </div>
                            <div class="row">
                                <div class="col-5 text-muted">Email:</div>
                                <div class="col-7"><strong>{{ $selectedRegistration->email }}</strong></div>
                            </div>
                        </div>

                        <hr>

                        @if (!$showRejectionForm)
                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-success" wire:click="approveRegistration()">
                                    Approve
                                </button>
                                <button type="button" class="btn btn-outline-danger" wire:click="toggleRejectionForm()">
                                    Reject
                                </button>
                            </div>
                        @else
                            <div class="alert alert-warning mb-3">
                                Provide reason for rejection
                            </div>
                            <div class="mb-3">
                                <textarea 
                                    class="form-control" 
                                    rows="3" 
                                    placeholder="Reason..."
                                    wire:model="rejectionReason"
                                ></textarea>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-danger" wire:click="rejectRegistration()">
                                    Confirm
                                </button>
                                <button type="button" class="btn btn-secondary" wire:click="toggleRejectionForm()">
                                    Cancel
                                </button>
                            </div>
                        @endif

                        @if ($errors->has('form'))
                            <div class="alert alert-danger mt-3">
                                {{ $errors->first('form') }}
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="card shadow-sm text-center py-5">
                    <div class="card-body">
                        <p class="text-muted">Select a registration to view</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
