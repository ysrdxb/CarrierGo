<?php

namespace App\Livewire;

use App\Models\Company;
use Livewire\Component;
use Livewire\WithPagination;
use Log;

class Companies extends Component
{
    use WithPagination;

    public $companies;
    public $editingCompany;
    protected $listeners = ['deleteConfirmed' => 'delete'];
    public $companyId;

    public function mount()
    {
        // Fetch companies data from the database
        $this->editingCompany = [];
        $this->companies = Company::all();
    }

    public function render()
    {
        $data = Company::paginate(12);

        return view('livewire.companies.index', compact('data'));
    }

    public function addNewCompany()
    {
        $newCompany = Company::make();
        $newCompany->editing = false;
        $this->companies->push($newCompany);
    }

    public function editCompany($companyId = null)
    {
        if ($companyId) {
            // Find the company and set editing mode to true
            $company = Company::where('id', $companyId)->first();
            $company->editing = true;

            // Set the old values for editing if editingCompany is not already set
            if (empty($this->editingCompany)) {
                $this->editingCompany = [
                    'id' => $company->id,
                    'name' => $company->name,
                    'address' => $company->address,
                    'city' => $company->city,
                    'zip_code' => $company->zip_code,
                ];
            }
        } else {
            // Add a new company
            $newCompany = Company::make();
            $newCompany->editing = true;
            $this->companies->push($newCompany);

            // Set the new company as the editing company
            $this->editingCompany = [
                'id' => '',
                'name' => '',
                'address' => '',
                'city' => '',
                'zip_code' => '',
            ];
        }
    }

    public function saveCompany($companyId = null)
    {
        $rules = [
            'editingCompany.name' => 'required',
            'editingCompany.address' => 'required',
            'editingCompany.city' => 'required',
            'editingCompany.zip_code' => 'required',
        ];

        $this->validate($rules);

        try {
            $company = $companyId ? Company::findOrFail($companyId) : new Company();

            $company->fill([
                'name' => $this->editingCompany['name'],
                'address' => $this->editingCompany['address'],
                'city' => $this->editingCompany['city'],
                'zip_code' => $this->editingCompany['zip_code'],
            ]);

            if ($companyId) {
                $company->update();
            } else {
                $company->save();
            }

            $this->editingCompany = [];
            $this->companies = Company::all();
            session()->flash('success', 'Company information saved successfully!');
        } catch (\Exception $e) {
            // Log the exception message or stack trace for debugging
            Log::error($e->getMessage());
            session()->flash('error', 'Failed to save company information.');
        }
    }

    public function cancelEdit($companyId = null)
    {
        // Find the company and set editing mode to false
        if ($companyId) {
            $this->companies->find($companyId)->editing = false;
        } else {
            $newCompany = Company::make();
            $newCompany->editing = false;
        }
    }

    public function confirmDelete($companyId = null)
    {
        if (!$companyId) {
            $newCompany = Company::make();
            $newCompany->editing = false;
            return;
        }
        $this->companyId = $companyId;
        $this->dispatch('show-confirm-delete');
    }

    public function delete()
    {
        if (!$this->companyId) {
            $newCompany = Company::make();
            $newCompany->editing = false;
            return;
        }

        $company = Company::find($this->companyId);
        if (!$company) {
            session()->flash('error', 'Company not found');
            return;
        }

        $company->delete();
        session()->flash('success', 'Company deleted successfully!');

        // Refresh the companies list
        $this->companies = Company::all();
    }

    public function restoreCompany($companyId)
    {
        $company = Company::withTrashed()->find($companyId);
        if (!$company) {
            session()->flash('success', 'Company restored successfully!');
            return;
        }

        $company->restore();
        session()->flash('success', 'Company restored successfully!');
        $this->companies = Company::withTrashed()->get();
    }
}
