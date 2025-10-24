<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\ReferenceNumber;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Log;
use Auth;

class UserReference extends Component
{
    public $users;
    public $editingUser;
    public $roles;
    protected $listeners = ['deleteConfirmed' => 'deleteUser'];
    public $userId;
    
    public function mount($id = null)
    {
        // Fetch users data from the database
        $this->users = User::where('id', Auth::user()->id)
            ->withTrashed()
            ->get();
        $this->roles = Role::all();
        $this->editingUser = [];
    }

    public function render()
    {        
        return view('livewire.employees.reference');
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
                    'reference' => $user->reference ? $user->reference->number_range : null,
                ];
            }
        }
    }
    
    public function saveUser($userId = null)
    {
        $rules = [
            'reference' => 'required|numeric',
        ];

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
            $user = User::findOrFail($userId);
    
            $referenceNumber = ReferenceNumber::updateOrCreate(
                ['user_id' => $user->id],
                ['number_range' => $this->editingUser['reference']]
            );
    
            if (!$userId || $referenceNumber->wasChanged('number_range')) {
                $referenceNumber->last_used_reference = 0;
                $referenceNumber->save();
            }
    
            $this->editingUser = [];
            $this->users = User::where('id', Auth::user()->id)
                ->get();
            session()->flash('success', 'Reference information saved successfully!');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            session()->flash('error', 'Failed to save reference information.'.$e->getMessage());
        }
    }
    
    public function cancelEdit($userId = null)
    {
        // Find the user and set editing mode to false
        if($userId) {
            $this->users->find($userId)->editing = false;
        }
    }

}
