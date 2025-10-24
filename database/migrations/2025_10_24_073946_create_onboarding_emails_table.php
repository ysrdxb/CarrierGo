<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('onboarding_emails', function (Blueprint $table) {
            $table->id();

            // References
            $table->unsignedBigInteger('registration_id')->nullable();
            $table->unsignedBigInteger('tenant_id')->nullable();

            // Email Details
            $table->enum('email_type', [
                'welcome',           // Welcome email when registration created
                'verify_email',      // Email verification link
                'approval_required', // Notify admin of pending registration
                'approved',          // Registration approved email
                'rejected',          // Registration rejected email
                'credentials',       // Credentials email (admin-created or auto-provisioned)
                'trial_ending',      // Trial ending notification
                'trial_expired',     // Trial expired notification
            ]);

            // Email Status
            $table->enum('status', ['pending', 'sent', 'failed'])->default('pending');
            $table->text('error_message')->nullable();

            // Timestamps
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('registration_id');
            $table->index('tenant_id');
            $table->index('email_type');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('onboarding_emails');
    }
};
