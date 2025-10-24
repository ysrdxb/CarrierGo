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
        Schema::create('unloading_addresses', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('street');
            $table->string('zip_city');
            $table->string('contact_name');
            $table->string('contact_phone');
            $table->date('latest_date');            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unloading_adresses');
    }
};
