<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenant extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tenants';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'domain',
        'subscription_plan',
        'subscription_status',
        'subscription_expires_at',
        'created_by_admin',      // Track if created by admin
        'approval_status',       // For registration approval
        'trial_days',            // Trial period in days
        'trial_expires_at',      // When trial expires
        'tenancy_mode',          // 'SHARED' or 'SEPARATE'
        'database_connection',   // Database config name (for SEPARATE mode)
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'subscription_expires_at' => 'datetime',
        'trial_expires_at' => 'datetime',
        'created_by_admin' => 'boolean',
    ];

    /**
     * Get the database name for this tenant.
     *
     * @return string
     */
    public function getDatabaseName(): string
    {
        return 'carriergo_tenant_' . $this->id;
    }

    /**
     * Get the database connection for this tenant.
     *
     * @return string
     */
    public function getDatabaseConnection(): string
    {
        if ($this->tenancy_mode === 'SEPARATE' && $this->database_connection) {
            return $this->database_connection;
        }
        return 'mysql'; // Default to central database
    }

    /**
     * Check if tenant uses shared database mode.
     *
     * @return bool
     */
    public function usesSharedDatabase(): bool
    {
        return $this->tenancy_mode === 'SHARED' || $this->tenancy_mode === null;
    }

    /**
     * Check if tenant uses separate database mode.
     *
     * @return bool
     */
    public function usesSeparateDatabase(): bool
    {
        return $this->tenancy_mode === 'SEPARATE';
    }
}
