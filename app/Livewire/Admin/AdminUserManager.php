<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUserManager extends Component
{
    use WithPagination;

    public $search = '';
    public $filterRole = 'all';
    public $editingUser = null;
    public $showEditModal = false;
    public $showDeleteConfirm = false;
    public $userToDelete = null;
    public $roles = [];

    // Form fields
    public $form = [
        'id' => null,
        'firstname' => '',
        'lastname' => '',
        'email' => '',
        'password' => '',
        'password_confirm' => '',
        'role' => null,
        'is_admin' => false,
    ];

    protected $listeners = ['deleteUserConfirmed' => 'deleteUser'];
    protected $queryString = ['search', 'filterRole'];

    /**
     * Mount the component
     */
    public function mount()
    {
        $this->roles = Role::all();
    }

    /**
     * Render the component
     */
    public function render()
    {
        $query = User::query();

        // Search by name or email
        if ($this->search) {
            $query->where('firstname', 'like', "%{$this->search}%")
                  ->orWhere('lastname', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%");
        }

        // Filter by role (simplified - would need to check role relationships)
        // For now, we'll just show all users

        $users = $query->orderBy('created_at', 'desc')
                       ->paginate(15);

        return view('livewire.admin.admin-user-manager', [
            'users' => $users,
            'roles' => $this->roles,
        ]);
    }

    /**
     * Open edit modal for creating new admin user
     */
    public function createAdminUser()
    {
        $this->editingUser = null;
        $this->form = [
            'id' => null,
            'firstname' => '',
            'lastname' => '',
            'email' => '',
            'password' => '',
            'password_confirm' => '',
            'role' => null,
            'is_admin' => false,
        ];
        $this->showEditModal = true;
    }

    /**
     * Edit existing admin user
     */
    public function editAdminUser($userId)
    {
        try {
            $user = User::findOrFail($userId);
            $this->editingUser = $user;
            $this->form = [
                'id' => $user->id,
                'firstname' => $user->firstname,
                'lastname' => $user->lastname,
                'email' => $user->email,
                'password' => '',
                'password_confirm' => '',
                'role' => optional($user->roles->first())->id,
                'is_admin' => $user->hasRole('admin'),
            ];
            $this->showEditModal = true;
        } catch (\Exception $e) {
            Log::error('Error loading user for edit: ' . $e->getMessage());
            session()->flash('error', 'Failed to load user details');
        }
    }

    /**
     * Save admin user
     */
    public function saveAdminUser()
    {
        // Validation rules
        $rules = [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . ($this->form['id'] ?? 'NULL'),
            'role' => 'required|exists:roles,id',
        ];

        // Password validation
        if (!$this->form['id']) {
            // Creating new user
            $rules['password'] = 'required|string|min:8|confirmed';
            $rules['password_confirm'] = 'required';
        } elseif ($this->form['password']) {
            // Updating user password
            $rules['password'] = 'required|string|min:8|confirmed';
            $rules['password_confirm'] = 'required';
        }

        $this->validate($rules);

        try {
            if ($this->form['id']) {
                // Update existing user
                $user = User::findOrFail($this->form['id']);
                $user->update([
                    'firstname' => $this->form['firstname'],
                    'lastname' => $this->form['lastname'],
                    'email' => $this->form['email'],
                ]);

                if ($this->form['password']) {
                    $user->password = Hash::make($this->form['password']);
                    $user->save();
                }

                $user->syncRoles([(int)$this->form['role']]);
                session()->flash('success', 'Admin user updated successfully!');
            } else {
                // Create new user
                $user = User::create([
                    'firstname' => $this->form['firstname'],
                    'lastname' => $this->form['lastname'],
                    'email' => $this->form['email'],
                    'phone' => '000-0000',
                    'password' => Hash::make($this->form['password']),
                    'otp' => rand(100000, 999999),
                    'otp_expiry' => now()->addHours(1)->toDateTimeString(),
                    'image' => '',
                ]);

                $user->assignRole((int)$this->form['role']);
                session()->flash('success', 'Admin user created successfully!');
            }

            $this->resetForm();
            $this->showEditModal = false;
        } catch (\Exception $e) {
            Log::error('Error saving admin user: ' . $e->getMessage());
            session()->flash('error', 'Failed to save admin user: ' . $e->getMessage());
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
     * Confirm delete user
     */
    public function confirmDeleteUser($userId)
    {
        try {
            $this->userToDelete = User::findOrFail($userId);
            $this->showDeleteConfirm = true;
        } catch (\Exception $e) {
            Log::error('Error loading user for delete: ' . $e->getMessage());
            session()->flash('error', 'User not found');
        }
    }

    /**
     * Delete user
     */
    public function deleteUser()
    {
        if (!$this->userToDelete) {
            session()->flash('error', 'No user selected');
            return;
        }

        try {
            $this->userToDelete->delete();
            $this->showDeleteConfirm = false;
            $this->userToDelete = null;
            session()->flash('success', 'Admin user deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Error deleting user: ' . $e->getMessage());
            session()->flash('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }

    /**
     * Reset form
     */
    private function resetForm()
    {
        $this->form = [
            'id' => null,
            'firstname' => '',
            'lastname' => '',
            'email' => '',
            'password' => '',
            'password_confirm' => '',
            'role' => null,
            'is_admin' => false,
        ];
        $this->editingUser = null;
    }

    /**
     * Get user's role name
     */
    public function getUserRole($user)
    {
        return optional($user->roles->first())->name ?? 'No Role';
    }
}
