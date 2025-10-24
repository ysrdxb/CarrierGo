<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\SoftDeletesTrait;

class BankDetail extends Model
{
    use HasFactory, SoftDeletes, SoftDeletesTrait, BelongsToTenant;

    protected $guarded = [];
 
}
