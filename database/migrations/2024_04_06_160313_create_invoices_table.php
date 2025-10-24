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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->integer('reference_id');
            $table->string('invoice_number');
            $table->decimal('amount', 10, 2);
            $table->string('language');
            $table->unsignedBigInteger('bank_account_id');
            $table->unsignedBigInteger('freight_payer');
            $table->decimal('tax_rate', 5, 2);
            $table->timestamps();
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
