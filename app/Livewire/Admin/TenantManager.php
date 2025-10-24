<?php

namespace App\Livewire\Admin;

use App\Models\Tenant;
use App\Mail\TenantCredentialsMailable;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class TenantManager extends Component
{
    use WithPagination;

    public $search = '';
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';
    public $filterStatus = 'all'; // all, active, inactive, suspended
    public $editingTenant = null;
    public $tenantToDelete = null;
    public $showEditModal = false;
    public $showDeleteConfirm = false;

    // Form fields
    public $form = [
        'id' => null,
        'name' => '',
        'domain' => '',
        'subscription_plan' => 'free',
        'subscription_status' => 'active',
        // Employee fields (NEW)
        'employee_firstname' => '',
        'employee_lastname' => '',
        'employee_email' => '',
        'employee_password' => '',
        'auto_generate_password' => true,
    ];

    protected $listeners = ['deleteTenantConfirmed' => 'deleteTenant'];
    protected $queryString = ['search', 'sortBy', 'sortDirection'];

    /**
     * Mount the component
     */
    public function mount()
    {
        // Initialize
    }

    /**
     * Render the component with tenants
     */
    public function render()
    {
        $query = Tenant::query();

        // Search by name or domain
        if ($this->search) {
            $query->where('name', 'like', "%{$this->search}%")
                  ->orWhere('domain', 'like', "%{$this->search}%");
        }

        // Filter by status
        if ($this->filterStatus !== 'all') {
            if ($this->filterStatus === 'suspended') {
                $query->where('subscription_status', 'suspended');
            } elseif ($this->filterStatus === 'inactive') {
                $query->where('subscription_status', 'cancelled');
            } else {
                $query->where('subscription_status', 'active');
            }
        }

        // Sort
        $tenants = $query->orderBy($this->sortBy, $this->sortDirection)
                         ->paginate(15);

        return view('livewire.admin.tenant-manager', [
            'tenants' => $tenants,
        ]);
    }

    /**
     * Update sort column
     */
    public function sortBy($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    /**
     * Open edit modal
     */
    public function editTenant($tenantId)
    {
        try {
            $tenant = Tenant::findOrFail($tenantId);
            $this->editingTenant = $tenant;
            $this->form = [
                'id' => $tenant->id,
                'name' => $tenant->name,
                'domain' => $tenant->domain,
                'subscription_plan' => $tenant->subscription_plan ?? 'free',
                'subscription_status' => $tenant->subscription_status ?? 'active',
            ];
            $this->showEditModal = true;
        } catch (\Exception $e) {
            Log::error('Error loading tenant for edit: ' . $e->getMessage());
            session()->flash('error', 'Failed to load tenant details');
        }
    }

    /**
     * Create new tenant form
     */
    public function createTenant()
    {
        $this->editingTenant = null;
        $this->form = [
            'id' => null,
            'name' => '',
            'domain' => '',
            'subscription_plan' => 'free',
            'subscription_status' => 'active',
            'employee_firstname' => '',
            'employee_lastname' => '',
            'employee_email' => '',
            'employee_password' => '',
            'auto_generate_password' => true,
        ];
        $this->showEditModal = true;
    }

    /**
     * Save tenant
     */
    public function saveTenant()
    {
        $rules = [
            'form.name' => 'required|string|max:255',
            'form.domain' => 'required|string|max:255|unique:tenants,domain,' . ($this->form['id'] ?? 'NULL'),
            'form.subscription_plan' => 'required|in:free,starter,professional,enterprise',
            'form.subscription_status' => 'required|in:active,suspended,cancelled',
            // NEW: Employee validation
            'form.employee_firstname' => 'required|string|max:100',
            'form.employee_lastname' => 'required|string|max:100',
            'form.employee_email' => 'required|email|max:255',
            'form.auto_generate_password' => 'boolean',
            'form.employee_password' => 'required_if:form.auto_generate_password,false|min:8',
        ];

        $this->validate($rules);

        try {
            if ($this->form['id']) {
                // Only allow update of tenant info, not employee (for existing tenants)
                $tenant = Tenant::findOrFail($this->form['id']);
                $tenant->update([
                    'name' => $this->form['name'],
                    'domain' => $this->form['domain'],
                    'subscription_plan' => $this->form['subscription_plan'],
                    'subscription_status' => $this->form['subscription_status'],
                ]);
                session()->flash('success', 'Tenant updated successfully!');
            } else {
                // Create new tenant with employee
                $tenant = Tenant::create([
                    'name' => $this->form['name'],
                    'domain' => $this->form['domain'],
                    'subscription_plan' => $this->form['subscription_plan'],
                    'subscription_status' => $this->form['subscription_status'],
                    'created_by_admin' => true, // NEW: Mark as admin-created
                ]);

                // Generate password if needed
                $password = $this->form['auto_generate_password']
                    ? Str::random(12)
                    : $this->form['employee_password'];

                // Dispatch provisioning job with employee data
                \App\Jobs\ProvisionTenant::dispatch(
                    $tenant,
                    $this->form['employee_firstname'],
                    $this->form['employee_lastname'],
                    $this->form['employee_email'],
                    $password,
                    true // Flag indicating password is already in plain text, not hashed
                );

                // Send credentials email
                try {
                    Mail::send(
                        new TenantCredentialsMailable(
                            $this->form['employee_firstname'],
                            $this->form['employee_lastname'],
                            $this->form['name'],
                            $this->form['domain'],
                            $this->form['employee_email'],
                            $password
                        )
                    );
                } catch (\Exception $e) {
                    Log::warning('Failed to send credentials email: ' . $e->getMessage());
                }

                session()->flash('success', 'Tenant created successfully! Credentials email sent to ' . $this->form['employee_email']);
            }

            $this->resetForm();
            $this->showEditModal = false;
        } catch (\Exception $e) {
            Log::error('Error saving tenant: ' . $e->getMessage());
            session()->flash('error', 'Failed to save tenant: ' . $e->getMessage());
        }
    }

    /**
     * Cancel edit
     */
    public function cancelEdit()
    {
        $this->resetForm();
        $this->showEditModal = false;
    }

    /**
     * Confirm delete tenant
     */
    public function confirmDeleteTenant($tenantId)
    {
        try {
            $this->tenantToDelete = Tenant::findOrFail($tenantId);
            $this->showDeleteConfirm = true;
        } catch (\Exception $e) {
            Log::error('Error loading tenant for delete: ' . $e->getMessage());
            session()->flash('error', 'Tenant not found');
        }
    }

    /**
     * Delete tenant
     */
    public function deleteTenant()
    {
        if (!$this->tenantToDelete) {
            session()->flash('error', 'No tenant selected');
            return;
        }

        try {
            $this->tenantToDelete->delete();
            $this->showDeleteConfirm = false;
            $this->tenantToDelete = null;
            session()->flash('success', 'Tenant deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Error deleting tenant: ' . $e->getMessage());
            session()->flash('error', 'Failed to delete tenant: ' . $e->getMessage());
        }
    }

    /**
     * Suspend/Activate tenant
     */
    public function toggleSuspend($tenantId, $action)
    {
        try {
            $tenant = Tenant::findOrFail($tenantId);
            $tenant->subscription_status = $action === 'suspend' ? 'suspended' : 'active';
            $tenant->save();
            session()->flash('success', 'Tenant ' . ($action === 'suspend' ? 'suspended' : 'activated') . ' successfully!');
        } catch (\Exception $e) {
            Log::error('Error toggling tenant status: ' . $e->getMessage());
            session()->flash('error', 'Failed to update tenant status');
        }
    }

    /**
     * Reset form
     */
    private function resetForm()
    {
        $this->form = [
            'id' => null,
            'name' => '',
            'domain' => '',
            'subscription_plan' => 'free',
            'subscription_status' => 'active',
            'employee_firstname' => '',
            'employee_lastname' => '',
            'employee_email' => '',
            'employee_password' => '',
            'auto_generate_password' => true,
        ];
        $this->editingTenant = null;
    }
}
