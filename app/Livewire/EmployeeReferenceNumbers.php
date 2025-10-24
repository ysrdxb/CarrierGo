<?php

namespace App\Livewire;

use App\Models\EmployeeReferenceNumber;
use Livewire\Component;
use Illuminate\Support\Facades\Validator;
use Log;

class EmployeeReferenceNumbers extends Component
{
    public $employeeReferenceNumbers;
    public $editingEmployeeReferenceNumber;

    protected $listeners = ['deleteConfirmed' => 'delete'];
    public $employeeReferenceNumberId;

    public function mount()
    {
        // Fetch employee reference numbers data from the database (paginated)
        $this->employeeReferenceNumbers = EmployeeReferenceNumber::all();
        $this->editingEmployeeReferenceNumber = [];
    }

    public function render()
    {
        return view('livewire.reference_numbers.employee_reference_numbers');
    }

    public function addNewEmployeeReferenceNumber()
    {
        $newEmployeeReferenceNumber = EmployeeReferenceNumber::make();
        $newEmployeeReferenceNumber->editing = false;
        $this->employeeReferenceNumbers->push($newEmployeeReferenceNumber);
    }

    public function editEmployeeReferenceNumber($employeeReferenceNumberId = null)
    {
        $employeeReferenceNumber = $this->employeeReferenceNumbers->where('id', $employeeReferenceNumberId)->first();
    
        if ($employeeReferenceNumber) {
            $this->editingEmployeeReferenceNumber = $employeeReferenceNumber->toArray();
            $employeeReferenceNumber->editing = true;
        }
    }
    

    public function saveEmployeeReferenceNumber($employeeReferenceNumberId = null)
    {
        $rules = [
            'employee_id' => 'required',
            'reference_number_id' => 'required',
        ];

        $validator = Validator::make($this->editingEmployeeReferenceNumber, $rules);

        if ($validator->fails()) {
            session()->flash('errors', $validator->errors()->toArray());
            return;
        }

        try {
            $employeeReferenceNumber = $employeeReferenceNumberId ? EmployeeReferenceNumber::findOrFail($employeeReferenceNumberId) : new EmployeeReferenceNumber();

            $employeeReferenceNumber->fill([
                'employee_id' => $this->editingEmployeeReferenceNumber['employee_id'],
                'reference_number_id' => $this->editingEmployeeReferenceNumber['reference_number_id'],
            ]);

            if ($employeeReferenceNumberId) {
                $employeeReferenceNumber->update();
            } else {
                $employeeReferenceNumber->save();
            }

            $this->editingEmployeeReferenceNumber = [];
            $this->employeeReferenceNumbers = EmployeeReferenceNumber::all();
            session()->flash('success', 'Employee reference number saved successfully!');
        } catch (\Exception $e) {
            // Log the exception message or stack trace for debugging
            Log::error('Failed to save employee reference number: ' . $e->getMessage());
            session()->flash('error', 'Failed to save employee reference number.');
        }
    }

    public function cancelEdit($employeeReferenceNumberId = null)
    {
        $this->employeeReferenceNumbers->find($employeeReferenceNumberId)->editing = false;
        $this->editingEmployeeReferenceNumber = [];
    }

    public function confirmDelete($employeeReferenceNumberId = null)
    {
        $this->employeeReferenceNumberId = $employeeReferenceNumberId;
        $this->dispatch('show-confirm-delete');
    }

    public function delete()
    {
        EmployeeReferenceNumber::find($this->employeeReferenceNumberId)->delete();
        session()->flash('success', 'Employee reference number deleted successfully!');
        $this->employeeReferenceNumbers = EmployeeReferenceNumber::all();
    }

    public function restoreEmployeeReferenceNumber($employeeReferenceNumberId)
    {
        EmployeeReferenceNumber::withTrashed()->find($employeeReferenceNumberId)->restore();
        session()->flash('success', 'Employee reference number restored successfully!');
        $this->employeeReferenceNumbers = EmployeeReferenceNumber::all();
    }
}
