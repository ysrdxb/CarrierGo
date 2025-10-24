<?php

namespace App\Livewire;

use App\Models\Reference;
use App\Models\DatabaseEntry;
use App\Models\Invoice;
use App\Models\ReferenceNumber;
use Livewire\Component;
use Livewire\Attributes\On; 
use Auth;
use Livewire\WithPagination;
use App\Services\ReferenceNumberService;

class Invoices extends Component
{
    use WithPagination;

    public $selectedRef = null;
    public $selectedRefNo = null;
    public $yearFilter = null;
	public $search = null;
	
    protected $listeners = ['updateSelectedRef', 'updateSelectedRefNo'];

	public function render()
	{
		$query = Invoice::query();

		if ($this->selectedRefNo) {
			$query->whereHas('reference', function ($query) {
				$query->where('reference_no', $this->selectedRefNo);
			});
		}

        if ($this->search) {
            $query->where(function ($query) {
                $query->where('invoices.created_at', 'like', '%' . $this->search . '%')
                    ->orWhere('invoice_number', 'like', '%' . $this->search . '%')
                    ->orWhereHas('reference', function ($query) {
                        $query->where('reference_no', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('payer', function ($query) {
                        $query->where('firstname', 'like', '%' . $this->search . '%')
                            ->orWhere('lastname', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('reference.freights', function ($query) {
                        $query->whereHas('destination', function ($query) {
                            $query->where('name', 'like', '%' . $this->search . '%');
                        });
                    });
            });
        }

		$invoices = $query->orderBy('id', 'desc')->paginate(12);
		$referenceNumbers = Auth::user()->hasRole('Admin') ? Reference::all() : Reference::where('created_by', Auth::user()->id)->get();

		return view('livewire.invoices.index', compact('invoices', 'referenceNumbers'));
	}

  
    public function updatedSearch()
    {
        $this->resetPage();
    }

	public function updateSelectedRef($value = null)
	{
		try {
			if ($value) {
				$this->yearFilter = $value;
				$referenceNumberService = new ReferenceNumberService();

				$referenceNumbers = ReferenceNumber::whereYear('year', $value)->get();

				$validNumberRanges = [];

				try {
					$lastUsedReference = $referenceNumberService->getLastUsedReference($value);
					$lastUsedReferenceParts = explode('-', $lastUsedReference);
					if (count($lastUsedReferenceParts) < 2) {
						throw new \Exception('Invalid format for last used reference');
					}
					$endValue = $lastUsedReferenceParts[1];
				} catch (\Exception $e) {
					// Handle the case where the last used reference is not in the expected format
					$endValue = null;
					// Log the error message if needed
					error_log('Error getting last used reference: ' . $e->getMessage());
				}

				foreach ($referenceNumbers as $number) {
					$numberRangeParts = explode('-', $number->number_range);
					if (count($numberRangeParts) < 1) {
						continue; // Skip invalid number ranges
					}
					$startValue = $numberRangeParts[0];

					if ($endValue !== null && $startValue <= $endValue) {
						$validNumberRanges[] = $number->number_range;
					}
				}

				$references = Reference::whereIn('reference_no', $validNumberRanges)->pluck('id');

				$this->invoices = Invoice::whereIn('reference_id', $references)
					->paginate(12);

				$this->dispatch('livewire:load');
			} else {
				$this->render();
			}
		} catch (\Exception $e) {
			// Log the error message if needed
			error_log('Error in updateSelectedRef: ' . $e->getMessage());
			// Optionally, set a flash message or take other actions to handle the error gracefully
		}
	}
   
    
    
    public function updateSelectedRefNo($ref_no = null)
    {
        if ($ref_no) {
            $ref_no_obj = json_decode($ref_no);
    
            if (is_object($ref_no_obj)) {
                $this->selectedRefNo = $ref_no_obj->reference_no;
            }
        } else {
            $this->selectedRefNo = null;
        }
    }       
    
    public function deleteInvoice($invoiceId)
    {
        $invoice = Invoice::find($invoiceId);
    
        if ($invoice) {
            $invoice->items()->delete();
            $invoice->delete();
            session()->flash('message', 'Invoice deleted successfully!');
        }
    
        $this->render();
    }            
    
}
