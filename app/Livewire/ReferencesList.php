<?php

namespace App\Livewire;

use App\Models\Reference;
use App\Models\ReferenceNumber;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class ReferencesList extends Component
{
    use WithPagination;

    public $selectedRef = null;
    public $selectedRefNo = null;
    public $yearFilter = null;
    public $perPage = 12;
    public $search = ''; 
    protected $listeners = ['updateYearFilter', 'updateSelectedRefNo'];

    public function render()
    {
        $query = Reference::query();
    
        $this->applyYearFilter($query);
    
        $this->applyReferenceNumberFilter($query);
    
        $this->applySearchFilter($query);
    
        $references = $this->applyUserRoleFilter($query)->latest()->paginate($this->perPage);
    
        $referenceNumbers = ReferenceNumber::orderBy('number_range', 'asc')->pluck('number_range', 'id');
    
        return view('livewire.references.index', [
            'references' => $references,
            'referenceNumbers' => $referenceNumbers,
        ]);
    }
    
    private function applyYearFilter($query)
    {
        if ($this->yearFilter) {
            $query->filterByYear($this->yearFilter);
        }
    }
    
    private function applyReferenceNumberFilter($query)
    {
        if ($this->selectedRefNo) {
            $refNumber = ReferenceNumber::find($this->selectedRefNo);
            if ($refNumber) {
                $lastUsedRef = $refNumber->last_used_reference;
                $yearSuffix = substr($refNumber->year, -2);
                $lastUsedRefParts = explode('-', $lastUsedRef);
                $startRef = $lastUsedRefParts[0];
                $query->whereBetween('reference_no', [
                    $refNumber->number_range . '-' . $yearSuffix,
                    $startRef . '-' . $yearSuffix
                ]);
            }
        }
    }
    
    private function applySearchFilter($query)
    {
        if ($this->search) {
            $query->where(function ($query) {
                $query->where('reference_no', 'like', '%' . $this->search . '%')
                    ->orWhereHas('client', function ($query) {
                        $query->where('company_name', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('consignee', function ($query) {
                        $query->where('company_name', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('merchant', function ($query) {
                        $query->where('company_name', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('freights', function ($query) {
                        $query->where('vehicle_fin', 'like', '%' . $this->search . '%');
                    });
            });
        }
    }
    
    private function applyUserRoleFilter($query)
    {
        if (Auth::user()->hasRole('Admin')) {
            return $query;
        } else {
            return $query->where('created_by', Auth::user()->id);
        }
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updateSelectedRefNo($value = null)
    {
        if ($value === "" || $value === "ALL") {
            $this->selectedRefNo = null;
        } else {
            $this->selectedRefNo = $value;
        }
        $this->resetPage();
    }

    public function updatedYearFilter($year = null)
    {
        if ($year) {
            $this->yearFilter = $year;
        } else {
            $this->yearFilter = null;
        }
        $this->resetPage();
    }

    public function deleteReference($referenceId)
    {
        $reference = Reference::find($referenceId);

        if (Auth::user()->hasRole('Admin')) {
            if ($reference) {
                $reference->delete();
                session()->flash('message', 'Reference deleted successfully!');
            }
        } else {
            if ($reference && $reference->status === 'Cancelled') {
                $reference->update(['status' => 'Pending']);
                session()->flash('message', 'Reference status changed to Pending!');
            } elseif ($reference) {
                $reference->update(['status' => 'Cancelled']);
                session()->flash('message', 'Reference status changed to Cancelled!');
            }
        }

        $this->dispatch('referenceDeleted');
    }
}
