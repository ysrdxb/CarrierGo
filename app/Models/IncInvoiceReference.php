<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncInvoiceReference extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function reference()
    {
        return $this->belongsTo(Reference::class, 'reference_id');
    }

    public function invoice()
    {
        return $this->belongsTo(IncomingInvoice::class, 'invoice_id');
    }
}
