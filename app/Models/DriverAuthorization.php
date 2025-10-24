<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverAuthorization extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function reference()
    {
        return $this->belongsTo(Reference::class, 'reference_id');
    }

    public function freight()
    {
        return $this->belongsTo(Freight::class, 'freight_id');
    }
}
