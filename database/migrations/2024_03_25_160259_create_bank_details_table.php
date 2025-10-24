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
        Schema::create('bank_details', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('bank_name');
            $table->string('account_name')->nullable();;
            $table->string('account_number')->nullable();;
            $table->string('iban')->nullable();
            $table->string('swift_code')->nullable();
            $table->string('currency')->nullable();;
            $table->string('branch')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('account_type')->nullable();;
            $table->boolean('is_default')->default(false)->nullable();;
            $table->timestamps();
            $table->softDeletes();  
        });               
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_details');
    }
};
