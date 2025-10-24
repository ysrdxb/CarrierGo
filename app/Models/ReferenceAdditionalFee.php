<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferenceAdditionalFee extends Model
{
    use HasFactory;

    protected $guarded = [];
    public $timestamps = false;

    public function reference()
    {
        return $this->belongsTo(Reference::class, 'reference_id');
    }
}
