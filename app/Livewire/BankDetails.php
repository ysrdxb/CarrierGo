<?php

namespace App\Livewire;

use App\Models\BankDetail;
use Livewire\Component;
use Illuminate\Support\Facades\Validator;
use Livewire\WithPagination;

use Log;

class BankDetails extends Component
{
    use WithPagination;

    public $bankDetails;
    public $editingBankDetail;

    protected $listeners = ['deleteConfirmed' => 'deleteBankDetail'];
    public $bankDetailId;

    public function mount()
    {
        $this->editingBankDetail = [];
        $this->bankDetails = BankDetail::all();
    }

    public function render()
    {
        $data = BankDetail::withTrashed()->paginate(12);
        return view('livewire.bank_details.index', [
            'data' => $data
        ]);
    }

    public function addNewBankDetail()
    {
        $newBankDetail = BankDetail::make();
        $newBankDetail->editing = false;
        $this->bankDetails->push($newBankDetail);
    }

    public function editBankDetail($bankDetailId = null)
    {
        if ($bankDetailId) {
            // Find the bank detail and set editing mode to true
            $bankDetail = $this->bankDetails->where('id', $bankDetailId)->first();
            $bankDetail->editing = true;
        
            // Set the old values for editing if editingBankDetail is not already set
            if (empty($this->editingBankDetail)) {
                $this->editingBankDetail = [
                    'id' => $bankDetail->id,
                    'company_name' => $bankDetail->company_name,
                    'bank_name' => $bankDetail->bank_name,
                    'iban' => $bankDetail->iban,
                    'swift_code' => $bankDetail->swift_code,
                ];
            }
        } else {
            // Add a new bank detail
            $newBankDetail = BankDetail::make();
            $newBankDetail->editing = true;
            $this->bankDetails->push($newBankDetail);
        
            // Set the new bank detail as the editing bank detail
            $this->editingBankDetail = [
                'id' => '',
                'company_name' => '',
                'bank_name' => '',
                'iban' => '',
                'swift_code' => '',
            ];
        }
    }
    
    public function saveBankDetail($bankDetailId = null)
    {
        $rules = [
            'company_name' => 'required',
            'bank_name' => 'required',
            'iban' => 'required',
            'swift_code' => 'required',
        ];
    
        $validator = Validator::make($this->editingBankDetail, $rules);
    
        if ($validator->fails()) {
            session()->flash('errors', $validator->errors()->toArray());
            return;
        }
    
        try {
            $bankDetail = $bankDetailId ? BankDetail::findOrFail($bankDetailId) : new BankDetail();
    
            $bankDetail->fill([
                'company_name' => $this->editingBankDetail['company_name'],
                'bank_name' => $this->editingBankDetail['bank_name'],
                'iban' => $this->editingBankDetail['iban'],
                'swift_code' => $this->editingBankDetail['swift_code']
            ]);
    
            if ($bankDetailId) {
                $bankDetail->update();  
            } else {
                $bankDetail->save();
            }
    
            $this->editingBankDetail = [];
            $this->bankDetails = BankDetail::withTrashed()->get();
            session()->flash('success', 'Bank detail saved successfully!');
        } catch (\Exception $e) {
            // Log the exception message or stack trace for debugging
            Log::error('Failed to save bank detail: ' . $e->getMessage());
            session()->flash('error', 'Failed to save bank detail.');
        }
    }      

    public function cancelEdit($bankDetailId = null)
    {
        // Find the bank detail and set editing mode to false
        if ($bankDetailId) {
            $this->bankDetails->find($bankDetailId)->editing = false;
        } else {
            $newBankDetail = BankDetail::make();
            $newBankDetail->editing = false;
        }
    }

    public function confirmDelete($bankDetailId = null)
    {
        if (!$bankDetailId) {
            $newBankDetail = BankDetail::make();
            $newBankDetail->editing = false;
            return;
        }
        $this->bankDetailId = $bankDetailId;
        $this->dispatch('show-confirm-delete');
    }

    public function deleteBankDetail()
    {
        if (!$this->bankDetailId) {
            $newBankDetail = BankDetail::make();
            $newBankDetail->editing = false;
            return;
        }

        $bankDetail = BankDetail::find($this->bankDetailId);
        if (!$bankDetail) {
            session()->flash('error', 'Bank detail not found');
            return;
        }

        $bankDetail->delete();
        session()->flash('success', 'Bank detail deleted successfully!');

        // Refresh the bank details list
        $this->bankDetails = BankDetail::withTrashed()->get();
    }

    public function restoreBankDetail($bankDetailId)
    {
        $bankDetail = BankDetail::withTrashed()->find($bankDetailId);
        if (!$bankDetail) {
            session()->flash('success', 'Bank detail restored successfully!');
            return;
        }

        $bankDetail->restore();
        session()->flash('success', 'Bank detail restored successfully!');
        $this->bankDetails = BankDetail::withTrashed()->get();
    }
}
