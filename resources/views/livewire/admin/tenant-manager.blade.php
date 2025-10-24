<div class="page-wrapper">
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        <i class="fe fe-users"></i> Tenant Management
                    </h2>
                    <div class="text-muted mt-1">Manage all SaaS tenants and their subscriptions</div>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <button wire:click="createTenant" class="btn btn-primary d-none d-sm-inline-block">
                            <i class="fe fe-plus"></i> New Tenant
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <!-- Flash Messages -->
            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible" role="alert">
                    <div class="d-flex">
                        <div>
                            <i class="fe fe-check-circle"></i> {{ session('success') }}
                        </div>
                    </div>
                    <a class="btn-close" data-bs-dismiss="alert" aria-label="Close"></a>
                </div>
            @endif
            @if (session()->has('error'))
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <div class="d-flex">
                        <div>
                            <i class="fe fe-alert-circle"></i> {{ session('error') }}
                        </div>
                    </div>
                    <a class="btn-close" data-bs-dismiss="alert" aria-label="Close"></a>
                </div>
            @endif

            <!-- Filters and Search -->
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row g-2">
                        <!-- Search -->
                        <div class="col-12 col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Search</label>
                                <input
                                    type="text"
                                    wire:model.live="search"
                                    placeholder="Search by name or domain..."
                                    class="form-control"
                                >
                            </div>
                        </div>

                        <!-- Status Filter -->
                        <div class="col-12 col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select
                                    wire:model.live="filterStatus"
                                    class="form-select"
                                >
                                    <option value="all">All Statuses</option>
                                    <option value="active">Active</option>
                                    <option value="suspended">Suspended</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>

                        <!-- Sort -->
                        <div class="col-12 col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Sort By</label>
                                <select
                                    wire:model.live="sortBy"
                                    class="form-select"
                                >
                                    <option value="created_at">Date Created</option>
                                    <option value="name">Name</option>
                                    <option value="subscription_status">Status</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tenants Table -->
            <div class="card">
                <div class="table-responsive">
                    <table class="table table-vcenter card-table">
                        <thead>
                            <tr>
                                <th>
                                    <a href="javascript:void(0)" wire:click="sortBy('name')" class="text-reset">
                                        Tenant Name
                                        @if($sortBy === 'name')
                                            {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                                        @endif
                                    </a>
                                </th>
                                <th>Domain</th>
                                <th>Plan</th>
                                <th>
                                    <a href="javascript:void(0)" wire:click="sortBy('subscription_status')" class="text-reset">
                                        Status
                                        @if($sortBy === 'subscription_status')
                                            {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                                        @endif
                                    </a>
                                </th>
                                <th>
                                    <a href="javascript:void(0)" wire:click="sortBy('created_at')" class="text-reset">
                                        Created
                                        @if($sortBy === 'created_at')
                                            {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                                        @endif
                                    </a>
                                </th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tenants as $tenant)
                                <tr>
                                    <td>
                                        <div class="font-weight-bold">{{ $tenant->name }}</div>
                                    </td>
                                    <td class="text-muted">{{ $tenant->domain }}</td>
                                    <td>
                                        <span class="badge bg-
                                            @if($tenant->subscription_plan === 'enterprise')
                                                purple
                                            @elseif($tenant->subscription_plan === 'professional')
                                                blue
                                            @elseif($tenant->subscription_plan === 'starter')
                                                green
                                            @else
                                                secondary
                                            @endif
                                        ">
                                            {{ ucfirst($tenant->subscription_plan ?? 'free') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-
                                            @if($tenant->subscription_status === 'active')
                                                success
                                            @elseif($tenant->subscription_status === 'suspended')
                                                warning
                                            @else
                                                danger
                                            @endif
                                        ">
                                            {{ ucfirst($tenant->subscription_status ?? 'inactive') }}
                                        </span>
                                    </td>
                                    <td class="text-muted">
                                        {{ $tenant->created_at?->format('M d, Y') ?? 'N/A' }}
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-list flex-nowrap">
                                            <button
                                                wire:click="editTenant({{ $tenant->id }})"
                                                class="btn btn-icon btn-ghost-primary btn-sm"
                                                title="Edit"
                                            >
                                                <i class="fe fe-edit"></i>
                                            </button>

                                            @if($tenant->subscription_status === 'active')
                                                <button
                                                    wire:click="toggleSuspend({{ $tenant->id }}, 'suspend')"
                                                    class="btn btn-icon btn-ghost-warning btn-sm"
                                                    title="Suspend"
                                                >
                                                    <i class="fe fe-pause"></i>
                                                </button>
                                            @else
                                                <button
                                                    wire:click="toggleSuspend({{ $tenant->id }}, 'activate')"
                                                    class="btn btn-icon btn-ghost-success btn-sm"
                                                    title="Activate"
                                                >
                                                    <i class="fe fe-play"></i>
                                                </button>
                                            @endif

                                            <button
                                                wire:click="confirmDeleteTenant({{ $tenant->id }})"
                                                class="btn btn-icon btn-ghost-danger btn-sm"
                                                title="Delete"
                                            >
                                                <i class="fe fe-trash-2"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <p><i class="fe fe-inbox"></i> No tenants found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="card-footer d-flex align-items-center">
                    {{ $tenants->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    @if($showEditModal)
        <div class="modal modal-blur fade show d-block" style="background-color: rgba(0, 0, 0, .5);">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            {{ $form['id'] ? 'Edit Tenant' : 'Create New Tenant' }}
                        </h5>
                        <button type="button" class="btn-close" wire:click="cancelEdit" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <!-- Tenant Information Section -->
                        <div class="mb-3">
                            <h6 class="mb-3"><i class="fe fe-building"></i> Tenant Information</h6>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tenant Name</label>
                            <input
                                type="text"
                                wire:model="form.name"
                                class="form-control @error('form.name') is-invalid @enderror"
                                placeholder="E.g., ABC Logistics"
                            >
                            @error('form.name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Domain</label>
                            <input
                                type="text"
                                wire:model="form.domain"
                                class="form-control @error('form.domain') is-invalid @enderror"
                                placeholder="E.g., abc-logistics"
                            >
                            <small class="text-muted">Domain will be accessible as: <strong>{{ $form['domain'] ?? 'your-domain' }}.carriergo.local</strong></small>
                            @error('form.domain')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Subscription Plan</label>
                            <select
                                wire:model="form.subscription_plan"
                                class="form-select @error('form.subscription_plan') is-invalid @enderror"
                            >
                                <option value="free">Free</option>
                                <option value="starter">Starter ($99/month)</option>
                                <option value="professional">Professional ($299/month)</option>
                                <option value="enterprise">Enterprise (Custom)</option>
                            </select>
                            @error('form.subscription_plan')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select
                                wire:model="form.subscription_status"
                                class="form-select @error('form.subscription_status') is-invalid @enderror"
                            >
                                <option value="active">Active</option>
                                <option value="suspended">Suspended</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                            @error('form.subscription_status')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Employee Information Section -->
                        <hr class="my-4">

                        <div class="mb-3">
                            <h6 class="mb-3"><i class="fe fe-users"></i> First Employee/Admin</h6>
                            <small class="text-muted">This user will receive login credentials via email</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">First Name</label>
                                    <input
                                        type="text"
                                        wire:model="form.employee_firstname"
                                        class="form-control @error('form.employee_firstname') is-invalid @enderror"
                                        placeholder="E.g., John"
                                    >
                                    @error('form.employee_firstname')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Last Name</label>
                                    <input
                                        type="text"
                                        wire:model="form.employee_lastname"
                                        class="form-control @error('form.employee_lastname') is-invalid @enderror"
                                        placeholder="E.g., Doe"
                                    >
                                    @error('form.employee_lastname')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input
                                type="email"
                                wire:model="form.employee_email"
                                class="form-control @error('form.employee_email') is-invalid @enderror"
                                placeholder="E.g., john@example.com"
                            >
                            <small class="text-muted">Login credentials will be sent to this email</small>
                            @error('form.employee_email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input
                                    type="checkbox"
                                    class="form-check-input"
                                    id="autoGenPassword"
                                    wire:model="form.auto_generate_password"
                                >
                                <label class="form-check-label" for="autoGenPassword">
                                    Auto-generate password
                                </label>
                                <small class="text-muted d-block mt-1">System will generate a secure password and send it via email</small>
                            </div>
                        </div>

                        @if(!$form['auto_generate_password'])
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input
                                    type="password"
                                    wire:model="form.employee_password"
                                    class="form-control @error('form.employee_password') is-invalid @enderror"
                                    placeholder="Minimum 8 characters"
                                >
                                <small class="text-muted">Must be at least 8 characters</small>
                                @error('form.employee_password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif
                    </div>

                    <div class="modal-footer">
                        <a href="javascript:void(0)" class="btn btn-link" wire:click="cancelEdit">Cancel</a>
                        <button type="button" class="btn btn-primary" wire:click="saveTenant">
                            <i class="fe fe-save"></i> Save Tenant
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($showDeleteConfirm && $tenantToDelete)
        <div class="modal modal-blur fade show d-block" style="background-color: rgba(0, 0, 0, .5);">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Delete Tenant</h5>
                        <button type="button" class="btn-close" wire:click="$set('showDeleteConfirm', false)" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <p>
                            Are you sure you want to delete <strong>{{ $tenantToDelete->name }}</strong>?
                            This action cannot be undone.
                        </p>
                    </div>

                    <div class="modal-footer">
                        <a href="javascript:void(0)" class="btn btn-link" wire:click="$set('showDeleteConfirm', false)">Cancel</a>
                        <button type="button" class="btn btn-danger" wire:click="deleteTenant">
                            <i class="fe fe-trash-2"></i> Delete Tenant
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
