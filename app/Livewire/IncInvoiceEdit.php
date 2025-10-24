<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\IncomingInvoice;
use App\Models\DatabaseEntry;
use Auth;

class IncInvoiceEdit extends Component
{
    use WithFileUploads;

    public $invoice, $file, $client_ref_no, $doc_type, $invoice_date, $receive_date, $payment_date, $client;

    protected $rules = [
        'file' => 'required|file|max:10240',
        'client_ref_no' => 'required|string|max:50',
        'doc_type' => 'required|in:Invoice,Proforma,Credit note',
        'invoice_date' => 'required|date',
        'receive_date' => 'required|date',
        'payment_date' => 'required|date',
        'client' => 'required|numeric|exists:database_entries,id',
    ];

    public function mount($id)
    {
        $invoice = IncomingInvoice::findOrFail($id);
        $this->invoice = $invoice;
        $this->client_ref_no = $invoice->reference_no;
        $this->doc_type = $invoice->file_type;
        $this->receive_date = $invoice->receive_date;
        $this->invoice_date = $invoice->invoice_date;
        $this->payment_date = $invoice->payment_date;
        $this->client = $invoice->client_id;
    }
    public function render()
    {
        $clients = DatabaseEntry::where('database_type', 'client')
            ->orWhere('database_type', 'agent')
            ->get();
        return view('livewire.incoming_invoices.create', compact('clients'));
    }

    public function saveEntry()
    {
        $this->validate();
    
        $this->invoice->update([
            'reference_no' => $this->client_ref_no,
            'file_type' => $this->doc_type,
            'invoice_date' => $this->invoice_date,
            'receive_date' => $this->receive_date,
            'payment_date' => $this->payment_date,
            'client_id' => $this->client,
        ]);
    
        if ($this->file) {
            $this->invoice->update([
                'file_path' => $this->file->store('invoices', 'public'),
            ]);
        }
        
        session()->flash('success', 'Incoming invoice updated successfully!');
    }
    
}
