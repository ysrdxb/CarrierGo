<?php 

namespace App\Livewire;

use App\Models\EmployeeNumberRange;
use App\Models\ReferenceNumber;
use App\Models\EmployeeReferenceNumber;
use App\Services\ReferenceNumberService;

use Livewire\Component;
use Illuminate\Support\Facades\Validator;

class EmployeeNumberRanges extends Component
{
    public $employeeNumberRanges;
    public $editingEmployeeNumberRange;
    public $employeeReferenceNumbers;


    protected $rules = [
        'editingEmployeeNumberRange.start_range' => 'required|integer',
        'editingEmployeeNumberRange.year' => 'required|integer',
    ];

    public function mount()
    {
        $this->employeeNumberRanges = EmployeeNumberRange::all();
        $this->editingEmployeeNumberRange = [];
        $this->employeeReferenceNumbers = ReferenceNumber::all();
    }

    public function render()
    {
        return view('livewire.reference_numbers.employee_number_ranges');
    }

    public function edit($id)
    {
        $this->editingEmployeeNumberRange = EmployeeNumberRange::find($id);
    }

    public function save()
    {
        $this->validate();

        if (isset($this->editingEmployeeNumberRange['id'])) {
            EmployeeNumberRange::find($this->editingEmployeeNumberRange['id'])
                ->update($this->editingEmployeeNumberRange);
        } else {
            EmployeeNumberRange::create($this->editingEmployeeNumberRange);
        }

        $this->editingEmployeeNumberRange = [];
        $this->employeeNumberRanges = EmployeeNumberRange::all();
    }

    public function delete($id)
    {
        EmployeeNumberRange::find($id)->delete();
        $this->employeeNumberRanges = EmployeeNumberRange::all();
    }

    public function cancel()
    {
        $this->editingEmployeeNumberRange = [];
    }

    public function addNewEmployeeReferenceNumber()
    {
        $employeeId = 1; // Example employee ID, you should set this dynamically based on your application's logic
        $referenceNumber = app(ReferenceNumberService::class)->generateReferenceNumber($employeeId);
    
        if ($referenceNumber) {
            EmployeeReferenceNumber::create([
                'employee_id' => $employeeId,
                'reference_number_id' => $referenceNumber->id, // Assuming this is how you link reference numbers to employees
            ]);
        } else {
            // Handle error or display a message that a reference number couldn't be generated
        }
    
        $this->employeeReferenceNumbers = EmployeeReferenceNumber::all(); // Update the list of employee reference numbers
    }
    
}
