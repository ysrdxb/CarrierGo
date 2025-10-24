<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\BelongsToTenant;

class OnboardingEmail extends Model
{
    use BelongsToTenant;
    /**
     * The table associated with the model.
     */
    protected $table = 'onboarding_emails';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'registration_id',
        'tenant_id',
        'email_type',
        'status',
        'error_message',
        'sent_at',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'sent_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the registration associated with this email.
     */
    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }

    /**
     * Get the tenant associated with this email.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Mark email as sent.
     */
    public function markAsSent(): void
    {
        $this->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);
    }

    /**
     * Mark email as failed.
     */
    public function markAsFailed($errorMessage = null): void
    {
        $this->update([
            'status' => 'failed',
            'error_message' => $errorMessage,
        ]);
    }

    /**
     * Check if email was sent successfully.
     */
    public function wasSent(): bool
    {
        return $this->status === 'sent' && $this->sent_at !== null;
    }
}
