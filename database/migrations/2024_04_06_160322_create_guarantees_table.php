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
        Schema::create('guarantees', function (Blueprint $table) {
            $table->id();
            $table->integer('reference_id');
            $table->string('issuer');
            $table->string('order_placed_by');
            $table->date('date_displayed');
            $table->timestamps();
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guarantees');
    }
};
