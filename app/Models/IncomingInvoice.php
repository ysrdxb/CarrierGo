<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomingInvoice extends Model
{
    use HasFactory;

    protected $guarded = [];      

    public function references()
    {
        return $this->hasMany(IncInvoiceReference::class, 'invoice_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function client()
    {
        return $this->belongsTo(DatabaseEntry::class, 'client_id');
    }

    public function items()
    {
        return $this->hasMany(IncInvoiceItem::class, 'invoice_id');
    }

}
