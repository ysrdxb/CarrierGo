<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class Invoice extends Model
{
    use HasFactory, BelongsToTenant;

    protected $guarded = [];

    public function reference()
    {
        return $this->belongsTo(Reference::class, 'reference_id');
    }   
    
    public function bank()
    {
        return $this->belongsTo(BankDetail::class, 'bank_account_id');
    }       

    public function payer()
    {
        return $this->belongsTo(DatabaseEntry::class, 'freight_payer');
    }       

    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_id');
    }
}
