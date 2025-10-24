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
        Schema::create('reference', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no');
            $table->dateTime('last_edited_at');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['New', 'Booked', 'Pickup scheduled', 'Picked up', 'Port delivered', 'Ready to ship', 'Shipped', 'Arrived', 'Paid', 'Released'])->default('New');
            $table->string('vessel_name')->nullable();
            $table->string('estimated_time_shipment')->nullable();
            $table->string('estimated_time_arrival')->nullable();
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('consignee_id');
            $table->unsignedBigInteger('merchant_id')->nullable();
            $table->unsignedBigInteger('agent_id')->nullable();
            $table->unsignedBigInteger('carrier_id')->nullable();
            $table->string('carrier_fees')->nullable();
            $table->string('agent_fees')->nullable();
            $table->string('extra_fees')->nullable();
            $table->string('price')->nullable();
            $table->string('extra_fees_eur')->nullable();
            $table->string('payment');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reference_numbers');
    }
};
