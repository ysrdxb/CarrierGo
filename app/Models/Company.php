<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\SoftDeletesTrait;
use App\Traits\BelongsToTenant;

class Company extends Model
{
    use HasFactory, SoftDeletes, SoftDeletesTrait, BelongsToTenant;

    protected $guarded = [];

    public function bankDetails()
    {
        return $this->hasMany(BankDetail::class);
    }    
}
