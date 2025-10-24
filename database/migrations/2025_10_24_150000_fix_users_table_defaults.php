<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Fixes missing default values in users table
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Make these columns nullable with proper defaults
            $table->string('firstname')->nullable()->default('')->change();
            $table->string('lastname')->nullable()->default('')->change();
            $table->string('phone')->nullable()->default('000-0000')->change();
            $table->integer('otp')->nullable()->default(0)->change();
            $table->string('otp_expiry')->nullable()->default(now())->change();
            $table->text('image')->nullable()->default('')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('firstname')->nullable(false)->change();
            $table->string('lastname')->nullable(false)->change();
            $table->string('phone')->nullable(false)->change();
            $table->integer('otp')->nullable(false)->change();
            $table->string('otp_expiry')->nullable(false)->change();
            $table->text('image')->nullable(false)->change();
        });
    }
};
