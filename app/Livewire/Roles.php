<?php 

namespace App\Livewire;

use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;
use Livewire\WithPagination;
use Log;

class Roles extends Component
{
    use WithPagination;

    public $roles;
    public $editingRole;
    public $permissions;
    public $editingRoleId;

    protected $listeners = ['deleteConfirmed' => 'delete'];
    public $roleId;

    public function mount()
    {
        $this->permissions = Permission::pluck('name', 'id');
        $this->roles = Role::all();
    }

    public function render()
    {
        $data = Role::with('permissions')->paginate(12);
        return view('livewire.roles.index', compact('data'));
    }

    public function addNewRole()
    {
        $newRole = Role::make();
        $this->roles->push($newRole);
    }

    public function editRole($roleId = null)
    {
        $role = $this->roles->where('id', $roleId)->first();

        if ($role) {
            $this->editingRole = $role->toArray();
            $this->editingRoleId = $roleId;
        } else {
            $newRole = Role::make();
            $this->editingRole = $newRole->toArray();
            $this->editingRoleId = null;
        }
    }

    public function saveRole($roleId = null)
    {
        // Validation rules
        $rules = [
            'name' => 'required|string|max:255',
            'permissions' => 'array',
        ];

        // Apply rules based on whether it's a new role or editing an existing one
        if ($roleId === null) {
            $rules['name'] .= '|unique:roles';
        } else {
            $rules['name'] .= '|unique:roles,name,' . $roleId;
        }

        // Validate the data
        $validator = Validator::make($this->editingRole, $rules);

        if ($validator->fails()) {
            session()->flash('errors', $validator->errors()->toArray());
            return;
        }

        //try {
            $role = $roleId ? Role::findOrFail($roleId) : new Role();

            $role->fill([
                'name' => $this->editingRole['name'],
            ]);

            if ($roleId) {
                $role->update();
            } else {
                $role->save();
            }

            // Sync role permissions
            if (!empty($this->editingRole['permissions'])) {
                // Filter out non-existent permissions
                $permissions = Permission::whereIn('id', $this->editingRole['permissions'])->pluck('id');
                $role->syncPermissions($permissions);
            }

            // Reset the editingRole and roles data
            $this->editingRole = [];
            $this->roles = Role::with('permissions')->get();
            $this->editingRoleId = null;

            session()->flash('success', 'Role saved successfully!');
        // } catch (\Exception $e) {
        //     Log::error('Failed to save role: ' . $e->getMessage());
        //     session()->flash('error', 'Failed to save role.');
        // }
    }

    public function cancelEdit()
    {
        $this->editingRoleId = null;
        $this->editingRole = [];
    }

    public function confirmDelete($roleId = null)
    {
        $this->roleId = $roleId;
        $this->dispatch('show-confirm-delete');
    }

    public function delete()
    {
        $role = Role::findOrFail($this->roleId);
        $role->delete();
        $role->permissions()->detach();
        session()->flash('success', 'Role deleted successfully!');
        $this->roles = Role::with('permissions')->get();
    }
}
