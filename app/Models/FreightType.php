<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class FreightType extends Model
{
    use HasFactory, BelongsToTenant;

    protected $guarded = [];

    public function freights()
    {
        return $this->hasMany(Freight::class);
    }
}
