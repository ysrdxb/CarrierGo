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
        Schema::create('database_entries', function (Blueprint $table) {
            $table->id();
            $table->string('firstname', 250)->nullable();
            $table->string('lastname', 250)->nullable();
            $table->enum('database_type', ['client', 'consignee', 'merchant', 'agent', 'carrier']);
            $table->string('company_name', 250);
            $table->string('email', 250);
            $table->string('email_2', 250)->nullable();
            $table->string('phone', 250);
            $table->string('phone_2', 250)->nullable();
            $table->string('country', 250)->nullable();
            $table->string('city', 250)->nullable();
            $table->string('vat_no', 250)->nullable();
            $table->integer('zip_code')->nullable();
            $table->string('street', 500)->nullable();
            $table->string('street_no', 500)->nullable();
            $table->string('status', 250)->nullable();
            $table->string('entry_gruop', 250)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('database_entries');
    }
};
