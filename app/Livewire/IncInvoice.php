<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\IncomingInvoice;
use App\Models\IncInvoiceReference;
use App\Models\Reference;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Auth;

class IncInvoice extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $selectedInvoiceId;
    public $showAssignModal = false;
    public $showReceiptModal = false;
    public $selectedReferences = [];
    public $file;

    public function render()
    {
        $incomingInvoices = IncomingInvoice::where('created_by', Auth::user()->id)->paginate(12);
        $references = Reference::where('created_by', Auth::user()->id)->get();
        return view('livewire.incoming_invoices.index', compact('incomingInvoices', 'references'));
    }

    public function uploadReceipt($invoiceId)
    {
        $rules = [
            'file' => 'required|file|max:10240',
        ];
    
        $this->validate($rules);    

        $invoice = IncomingInvoice::findOrFail($invoiceId);

        $invoice->update([
            'receipt_file' => $this->file->store('invoices', 'public'),
        ]);

        $this->reset(['file']);

        session()->flash('upload_message', 'Receipt uploaded successfully!');        
    }

    public function toggleAssignModal($invoiceId)
    {
        $incInvoice = IncomingInvoice::findOrFail($invoiceId);
        $this->selectedInvoiceId = $incInvoice->id;
        $this->selectedReferences = $incInvoice->references->pluck('reference_id')->toArray();
        $this->showAssignModal = true;
    }
    
    public function toggleReceiptModal($invoiceId)
    {
        $incInvoice = IncomingInvoice::findOrFail($invoiceId);
        $this->selectedInvoiceId = $incInvoice->id;
        $this->selectedReferences = $incInvoice->references->pluck('reference_id')->toArray();
        $this->showReceiptModal = true;
    }

    public function assignReferences()
    {
        if (!$this->selectedInvoiceId) {
            return;
        }
        foreach ($this->selectedReferences as $referenceId) {
            IncInvoiceReference::updateOrCreate(
                [
                    'invoice_id' => $this->selectedInvoiceId,
                    'reference_id' => $referenceId,
                ],
                []
            );
        }
        $this->selectedReferences = [];
        $this->showAssignModal = false;
        $incInvoice = IncomingInvoice::findOrFail($this->selectedInvoiceId);
        if($incInvoice->assigned === 'No') {
            $incInvoice->assigned = 'Yes';
            $incInvoice->save();
        }
        session()->flash('success', 'References assigned successfully!');
    }

    public function toggleStatus($id)
    {
        $invoice = IncomingInvoice::findOrFail($id);
        $invoice->status = $invoice->status === 'Paid' ? 'Unpaid' : 'Paid';
        $invoice->save();
        session()->flash($invoice->status == 'Paid' ? 'success' : 'error', 'Invoice status set to '.$invoice->status.' successfully!');
    }    

    public function saveEntry()
    {
        $this->validate();

        session()->flash('success', 'Freight Type added successfully!');
    }

    public function edit($id)
    {
        // Logic for editing an incoming invoice
    }

    public function delete($id)
    {
        $invoice = IncomingInvoice::findOrFail($id);
        IncInvoiceReference::where('invoice_id', $invoice->id)->delete();
        $invoice->delete();
        session()->flash('success', 'Invoice deleted successfully!');
    }
}
