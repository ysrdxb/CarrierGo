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
        Schema::create('transport_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->foreign('reference_id')->references('id')->on('reference')->onDelete('cascade');
            $table->string('transport_type');
            $table->integer('merchant_id')->nullable();
            $table->string('loading_company_name')->nullable();
            $table->string('loading_street')->nullable();
            $table->string('loading_zip_city')->nullable();
            $table->string('loading_contact_name')->nullable();
            $table->string('loading_contact_phone')->nullable();
            $table->date('loading_latest_date')->nullable();
            $table->integer('unloading_address_id');

            $table->decimal('transport_price_eur', 10, 2);
            $table->date('add_date')->default('2024-03-20');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transport_orders');
    }
};
