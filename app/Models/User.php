<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\SoftDeletesTrait;
use App\Traits\BelongsToTenant;

class User extends Authenticatable
{
    // TEMPORARILY DISABLED BelongsToTenant to test if it causes memory leak
    use HasFactory, Notifiable, HasRoles, SoftDeletes, SoftDeletesTrait; // BelongsToTenant;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tenant_id',
        'firstname',
        'lastname',
        'email',
        'password',
        'phone',
        'otp',
        'otp_expiry',
        'start_date',
        'end_date',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function get_roles()
    {
        $roles = [];
        foreach ($this->getRoleNames() as $key => $role) {
            $roles[$key] = $role;
        }

        return $roles;
    }

    public function reference()
    {
        return $this->hasOne(ReferenceNumber::class);
    }

    public function referenceNumbers()
    {
        return $this->hasMany(ReferenceNumber::class);
    }  
}
