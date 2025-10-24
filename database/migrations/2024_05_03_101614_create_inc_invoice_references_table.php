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
        Schema::create('inc_invoice_references', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reference_id')->constrained('reference')->onDelete('cascade');
            $table->foreignId('invoice_id')->constrained('incoming_invoices')->onDelete('cascade');
            $table->timestamps();
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inc_invoice_references');
    }
};
