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
        Schema::create('freights', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('freight_type_id');
            $table->string('type');
            $table->string('vehicle_model')->nullable();
            $table->string('vehicle_type')->nullable();
            $table->string('vehicle_fin')->nullable();
            $table->string('description')->nullable();
            $table->unsignedBigInteger('destination_id');
            $table->unsignedBigInteger('reference_id');
            $table->timestamps();
        });      
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('freights');
    }
};
