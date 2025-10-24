<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransportOrder extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function reference()
    {
        return $this->belongsTo(Reference::class, 'reference_id');
    }   
	
    public function merchant()
    {
        return $this->belongsTo(DatabaseEntry::class, 'merchant_id');
    } 	
    
    public function unloadingAddress()
    {
        return $this->belongsTo(UnloadingAddress::class, 'unloading_address_id');
    }
}
