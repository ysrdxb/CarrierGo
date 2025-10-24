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
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();

            // Tenant Information
            $table->string('company_name');
            $table->string('domain')->unique();
            $table->enum('subscription_plan', ['free', 'starter', 'professional', 'enterprise'])->default('free');

            // User Information
            $table->string('firstname');
            $table->string('lastname');
            $table->string('email')->unique();
            $table->string('password_hash');

            // Registration Status
            $table->enum('status', ['pending', 'verified', 'approved', 'rejected', 'completed'])->default('pending');
            $table->text('rejection_reason')->nullable();

            // Email Verification
            $table->string('verification_token')->unique()->nullable();
            $table->timestamp('verification_token_expires_at')->nullable();
            $table->timestamp('verified_at')->nullable();

            // Payment Information (for paid plans)
            $table->string('payment_method')->nullable(); // 'credit_card', 'paypal', etc.
            $table->enum('payment_status', ['pending', 'completed', 'failed'])->nullable();
            $table->string('stripe_customer_id')->nullable();
            $table->string('stripe_payment_intent_id')->nullable();

            // Trial Information
            $table->integer('trial_days')->default(14);
            $table->timestamp('trial_expires_at')->nullable();

            // Reference to Tenant (after provisioned)
            $table->unsignedBigInteger('tenant_id')->nullable();
            $table->timestamp('tenant_database_created_at')->nullable();

            // Timestamps
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('status');
            $table->index('email');
            $table->index('domain');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
