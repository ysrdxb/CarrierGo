<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class Freight extends Model
{
    use HasFactory, BelongsToTenant;

    protected $guarded = [];

    public function freightType()
    {
        return $this->belongsTo(FreightType::class);
    }

    public function reference()
    {
        return $this->belongsTo(Reference::class);
    }

    public function destination()
    {
        return $this->belongsTo(Destination::class, 'destination_id');
    }
}
