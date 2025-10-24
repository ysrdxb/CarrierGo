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
        Schema::create('driver_authorizations', function (Blueprint $table) {
            $table->id();
            $table->integer('reference_id');
            $table->integer('freight_id');
            $table->string('driver_name');
            $table->string('plate_no');
            $table->string('add_date');
            $table->timestamps();
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driver_authorizations');
    }
};
