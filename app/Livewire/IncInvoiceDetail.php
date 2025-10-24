<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\IncomingInvoice;
use App\Models\IncInvoiceItem;
use Illuminate\Validation\Rule;

class IncInvoiceDetail extends Component
{
    public $invoice;
    public $item_price;
    public $item_name;
    public $item_description;
    public $item_quantity;
    public $editMode = false;
    public $itemId;

    protected $rules = [
        'item_name' => 'required|string|max:250',
        'item_price' => 'required|numeric',
        'item_description' => 'required|string|max:500',
        'item_quantity' => 'required|numeric',
    ];

    public function mount($id)
    {
        $this->invoice = IncomingInvoice::findOrFail($id);
    }

    public function render()
    {
        $invoice = $this->invoice;
        return view('livewire.incoming_invoices.detail', compact('invoice'));
    }

    public function addItem()
    {
        $this->editMode = false;
        self::resetForm();
    }

    public function submitForm()
    {
        $this->validate();

        if ($this->editMode) {
            $item = IncInvoiceItem::findOrFail($this->itemId);
            $item->update([
                'name' => $this->item_name,
                'description' => $this->item_description,
                'price' => $this->item_price,
                'quantity' => $this->item_quantity,
            ]);
        } else {
            IncInvoiceItem::create([
                'invoice_id' => $this->invoice->id,
                'name' => $this->item_name,
                'description' => $this->item_description,
                'price' => $this->item_price,
                'quantity' => $this->item_quantity,
            ]);
        }

        self::resetForm();
        session()->flash('add_item_message', $this->editMode ? 'Service updated successfully!' : 'Service added successfully!');
        $this->invoice = $this->invoice->fresh();
    }

    public function editItem($id)
    {
        $item = IncInvoiceItem::findOrFail($id);
        $this->editMode = true;
        $this->itemId = $item->id;
        $this->item_name = $item->name;
        $this->item_description = $item->description;
        $this->item_price = $item->price;
        $this->item_quantity = $item->quantity;
    }

    protected function resetForm()
    {
        $this->reset(['item_price', 'item_name', 'item_description', 'item_quantity', 'editMode', 'itemId']);
    }
}
