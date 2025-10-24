<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\BelongsToTenant;

class Registration extends Model
{
    use BelongsToTenant;
    /**
     * The table associated with the model.
     */
    protected $table = 'registrations';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'company_name',
        'domain',
        'subscription_plan',
        'firstname',
        'lastname',
        'email',
        'password_hash',
        'status',
        'rejection_reason',
        'verification_token',
        'verification_token_expires_at',
        'verified_at',
        'payment_method',
        'payment_status',
        'stripe_customer_id',
        'stripe_payment_intent_id',
        'trial_days',
        'trial_expires_at',
        'tenant_id',
        'tenant_database_created_at',
        'approved_at',
        'completed_at',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'verification_token_expires_at' => 'datetime',
        'verified_at' => 'datetime',
        'trial_expires_at' => 'datetime',
        'tenant_database_created_at' => 'datetime',
        'approved_at' => 'datetime',
        'completed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the tenant associated with this registration.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the onboarding emails sent for this registration.
     */
    public function emails(): HasMany
    {
        return $this->hasMany(OnboardingEmail::class);
    }

    /**
     * Mark registration as verified.
     */
    public function markAsVerified(): void
    {
        $this->update([
            'status' => 'verified',
            'verified_at' => now(),
            'verification_token' => null,
            'verification_token_expires_at' => null,
        ]);
    }

    /**
     * Mark registration as approved.
     */
    public function markAsApproved(): void
    {
        $this->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);
    }

    /**
     * Mark registration as completed.
     */
    public function markAsCompleted($tenantId): void
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
            'tenant_id' => $tenantId,
            'tenant_database_created_at' => now(),
        ]);
    }

    /**
     * Mark registration as rejected.
     */
    public function markAsRejected($reason): void
    {
        $this->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
        ]);
    }

    /**
     * Check if registration can be auto-provisioned (free tier).
     */
    public function canAutoProvision(): bool
    {
        return $this->subscription_plan === 'free'
            && $this->status === 'verified'
            && !$this->tenant_id;
    }

    /**
     * Check if registration is pending approval (paid tier).
     */
    public function isPendingApproval(): bool
    {
        return in_array($this->subscription_plan, ['starter', 'professional', 'enterprise'])
            && $this->status === 'verified'
            && $this->payment_status === 'completed'
            && !$this->tenant_id;
    }

    /**
     * Check if verification token is expired.
     */
    public function isVerificationTokenExpired(): bool
    {
        return $this->verification_token_expires_at &&
               $this->verification_token_expires_at->isPast();
    }
}
