<?php

namespace App\Livewire;

use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;
use Livewire\WithPagination;

use Log;

class Permissions extends Component
{
    use WithPagination;
    
    public $permissions;
    public $editingPermission;
    public $roles;
    public $editingPermissionId;

    protected $listeners = ['deleteConfirmed' => 'delete'];
    public $permissionId;

    public function mount()
    {
        // Fetch employee reference numbers data from the database (paginated)
        
        $this->editingPermission = [];
        $this->permissions = Permission::all();
        // Get roles
        $this->roles = Role::pluck('name', 'id');
    }

    public function render()
    {
        $data = Permission::paginate(12);
        return view('livewire.permissions.index', compact('data'));
    }

    public function addNewPermission()
    {
        $newPermission = Permission::make();
        $this->editingPermission = $newPermission->toArray();
        $this->editingPermissionId = null; 
        $this->permissions->push($newPermission);        
    }
    
    public function editPermission($permissionId = null)
    {
        $permission = $this->permissions->where('id', $permissionId)->first();
    
        if ($permission) {
            $this->editingPermission = $permission->toArray();
            $this->editingPermissionId = $permissionId; // Set the editing permission id
        } else {
            // Create a new permission with empty values
            $newPermission = Permission::make();
            $this->editingPermission = $newPermission->toArray();
            $this->editingPermissionId = null; // Set the editing permission id
        }
    }

    public function savePermission($permissionId = null)
    {
        $rules = [
            'name' => 'required|string|max:255',
        ];

        if ($permissionId == null) {
            $rules['roles'] = 'required|array';
        }   

        $validator = Validator::make($this->editingPermission, $rules);
    
        if ($validator->fails()) {
            session()->flash('errors', $validator->errors()->toArray());
            return;
        }
    
        try {
            $permission = $permissionId ? Permission::findOrFail($permissionId) : new Permission();
    
            $permission->fill([
                'name' => $this->editingPermission['name'],
            ]);
    
            if ($permissionId) {
                $permission->update();
            } else {
                $permission->save();
            }
    
            // Convert role IDs to role names
            if ($permissionId !== null && !empty($this->editingPermission['roles'])) {
                $roleNames = [];
                foreach ($this->editingPermission['roles'] as $roleId) {
                    $roleNames[] = $this->roles[$roleId];
                }
    
                // Sync roles using role names
                $permission->syncRoles($roleNames);
            }
    
            $this->editingPermission = [];
            $this->permissions = Permission::all();
            Self::cancelEdit();
            session()->flash('success', 'Permission saved successfully!');
        } catch (\Exception $e) {
            // Log the exception message or stack trace for debugging
            Log::error('Failed to save permission: ' . $e->getMessage());
            session()->flash('error', 'Failed to save permission.');
        }
        
    }
       

    public function cancelEdit()
    {
        $this->editingPermissionId = null; // Reset the editing permission id
        $this->editingPermission = [];
    }

    public function confirmDelete($permissionId = null)
    {
        $this->permissionId = $permissionId;
        $this->dispatch('show-confirm-delete');
    }

    public function delete()
    {
        $permission = Permission::findOrFail($this->permissionId);
        $permission->delete();
        $permission->roles()->detach();
        session()->flash('success', 'Permission deleted successfully!');
        $this->permissions = Permission::all();
    }
}
