<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\IncomingInvoice;
use App\Models\DatabaseEntry;
use Auth;
use Exception;

class CreateIncInvoice extends Component
{
    use WithFileUploads;

    public $file, $client_ref_no, $doc_type, $invoice_date, $receive_date, $payment_date, $client;

    protected $rules = [
        'file' => 'required|file|max:10240',
        'client_ref_no' => 'required|string|max:50',
        'doc_type' => 'required|in:Invoice,Proforma,Credit note',
        'invoice_date' => 'required|date',
        'receive_date' => 'required|date',
        'payment_date' => 'required|date',
        'client' => 'required|numeric|exists:database_entries,id',
    ];

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

        try {
    
            IncomingInvoice::create([
                'file_path' => $this->file->store('invoices', 'public'),
                'reference_no' => $this->client_ref_no,
                'file_type' => $this->doc_type,
                'invoice_date' => $this->invoice_date,
                'receive_date' => $this->receive_date,
                'payment_date' => $this->payment_date,
                'client_id' => $this->client,
                'created_by' => Auth::user()->id,
            ]);
    
            $this->reset(['file', 'client_ref_no', 'doc_type', 'invoice_date', 'receive_date', 'payment_date']);
    
            session()->flash('success', 'Incoming invoice added successfully!');
        } catch (Exception $e) {    
            session()->flash('error', $e->getMessage());
        }
    }
}
