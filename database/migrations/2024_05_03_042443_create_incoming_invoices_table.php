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
        Schema::create('incoming_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no');
            $table->string('file_path');
            $table->string('file_type');
            $table->string('receipt_file');
            $table->string('receive_date')->nullable();
            $table->string('invoice_date')->nullable();
            $table->string('payment_date')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->integer('client_id');
            $table->enum('status', ['Unpaid', 'Paid'])->default('Unpaid');
            $table->enum('assigned', ['No', 'Yes'])->default('No');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incoming_invoices');
    }
};
