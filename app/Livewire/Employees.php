<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\ReferenceNumber;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Log;
use Auth;

class Employees extends Component
{
    public $users;
    public $editingUser;
    public $roles;
    protected $listeners = ['deleteConfirmed' => 'deleteUser'];
    public $userId;
    
    public function mount()
    {
        // Fetch users data from the database
        $this->users = User::where('id', '!=', Auth::user()->id)
            ->withTrashed()
            ->get();
        $this->roles = Role::all();
        $this->editingUser = [];
    }

    public function render()
    {        
        return view('livewire.employees.index');
    }    

    public function addNewUser()
    {
        $newUser = User::make();
        $newUser->editing = false;
        $this->users->push($newUser);
    }

    public function editUser($userId = null)
    {
        if ($userId) {
            // Find the user and set editing mode to true
            $user = $this->users->where('id', $userId)->first();
            $user->editing = true;
    
            // Set the old values for editing if editingUser is not already set
            if (empty($this->editingUser)) {
                $this->editingUser = [
                    'id' => $user->id,
                    'firstname' => $user->firstname,
                    'lastname' => $user->lastname,
                    'reference' => $user->reference ? $user->reference->number_range : null,
                    'email' => $user->email,
                    'role' => optional($user->roles->first())->id,
                    'start_date' => $user->start_date,
                    'end_date' => $user->end_date,
                    'password' => '',
                ];
            }
        } else {
            // Add a new user
            $newUser = User::make();
            $newUser->editing = true;
            $this->users->push($newUser);
    
            // Set the new user as the editing user
            $this->editingUser = [
                'id' => '',
                'firstname' => '',
                'lastname' => '',
                'reference' => '',
                'email' => '',
                'role' => '',
                'start_date' => '',
                'end_date' => '',
                'password' => '',
            ];
        }
    }
    
    public function saveUser($userId = null)
    {
        $rules = [
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email|unique:users,email,' . ($userId ?? 'NULL'),
            'role' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'reference' => 'required|numeric',
        ];
    
        if (!$userId) {
            $rules['password'] = 'required';
        }

        if (!empty($this->editingUser['reference'])) {
            // Check if the reference starts with a number in the thousands
            if (!preg_match('/^(1000|2000|3000|4000|5000|6000|7000|8000|9000)\d*$/', $this->editingUser['reference'])) {
                session()->flash('errors', ['reference' => ['The selected reference is invalid.']]);
                return;
            }
            if($userId) {
                $checkRefNo = ReferenceNumber::where('number_range', $this->editingUser['reference'])
                    ->where('year', date('Y'))
                    ->where('user_id', '!=', $userId)
                    ->first();
            } else {
                $checkRefNo = ReferenceNumber::where('number_range', $this->editingUser['reference'])
                    ->where('year', date('Y'))
                    ->first();
            }
            if ($checkRefNo) {
                session()->flash('errors', ['reference' => ['The selected reference already exists.']]);
                return;
            }
        }

        if($checkRefNo)
        {
            session()->flash('error', 'Reference Number already used.');
            return;            
        }

        $validator = Validator::make($this->editingUser, $rules);
    
        if ($validator->fails()) {
            session()->flash('errors', $validator->errors()->toArray());
            return;
        }
    
        try {
            $user = $userId ? User::findOrFail($userId) : new User();
    
            $user->fill([
                'firstname' => $this->editingUser['firstname'],
                'lastname' => $this->editingUser['lastname'],
                'email' => $this->editingUser['email'],
                'start_date' => $this->editingUser['start_date'],
                'end_date' => $this->editingUser['end_date'],
            ]);
    
            if (!$userId || $this->editingUser['password']) {
                $user->password = bcrypt($this->editingUser['password']);
            }
    
            if ($userId) {
                $user->update();
                $user->syncRoles([(int)$this->editingUser['role']]);
            } else {
                $user->save();
                $user->assignRole((int)$this->editingUser['role']);
            }
    
            $referenceNumber = ReferenceNumber::updateOrCreate(
                ['user_id' => $user->id],
                ['number_range' => $this->editingUser['reference']]
            );
    
            if (!$userId || $referenceNumber->wasChanged('number_range')) {
                $referenceNumber->last_used_reference = 0;
                $referenceNumber->save();
            }
    
            $this->editingUser = [];
            $this->users = User::where('id', '!=', Auth::user()->id)
                ->withTrashed()
                ->get();
            session()->flash('success', 'Employee information saved successfully!');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            session()->flash('error', 'Failed to save employee information.');
        }
    }
    
    
    public function cancelEdit($userId = null)
    {
        // Find the user and set editing mode to false
        if($userId) {
            $this->users->find($userId)->editing = false;
        } else {
            $newUser = User::make();
            $newUser->editing = false;
        }
    }


    public function confirmDelete($userId =  null)
    {
        if(!$userId) {
            $newUser = User::make();
            $newUser->editing = false;
            return;
        }
        $this->userId = $userId;
        $this->dispatch('show-confirm-delete');
    }    

    public function deleteUser()
    {
        if(!$this->userId) {
            $newUser = User::make();
            $newUser->editing = false;
            return;
        }    

        $user = User::find($this->userId);
        if (!$user) {
            session()->flash('error', 'Employee not found');
            return;
        }
    
        if ($user->role === 'Admin' || $user->role === 'Super Admin') {
            session()->flash('error', 'Cannot delete admin or super admin');
            return;
        }
    
        $user->delete();
        session()->flash('success', 'Employee deleted successfully!');
    
        // Refresh the users list
        $this->users = User::withTrashed()->get();
    }    
    
    public function restoreUser($userId)
    {
        $user = User::withTrashed()->find($userId);
        if (!$user) {
            session()->flash('success', 'Employee restored successfully!');
            return;
        }
    
        $user->restore();
        session()->flash('success', 'Employee restored successfully!');
        $this->users = User::withTrashed()->get();
    }
}
