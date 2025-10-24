<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class ReferenceNumber extends Model
{
    use HasFactory, BelongsToTenant;

    protected $guarded = [];

    public function reference()
    {
        return $this->belongsTo(Reference::class);
    }     

    public function user()
    {
        return $this->belongsTo(User::class);
    }  
}
