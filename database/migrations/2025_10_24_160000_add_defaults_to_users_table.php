<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Sets explicit default values for users table columns
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Set explicit defaults using raw SQL for better compatibility
            DB::statement('ALTER TABLE users MODIFY firstname VARCHAR(255) NULL DEFAULT ""');
            DB::statement('ALTER TABLE users MODIFY lastname VARCHAR(255) NULL DEFAULT ""');
            DB::statement('ALTER TABLE users MODIFY otp INT NULL DEFAULT 0');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            DB::statement('ALTER TABLE users MODIFY firstname VARCHAR(255) NULL');
            DB::statement('ALTER TABLE users MODIFY lastname VARCHAR(255) NULL');
            DB::statement('ALTER TABLE users MODIFY otp INT NULL');
        });
    }
};
