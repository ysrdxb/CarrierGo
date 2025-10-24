<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Properly fixes the image column default without literal quotes
     */
    public function up(): void
    {
        // Set image to empty string without quotes as default
        DB::statement("ALTER TABLE users CHANGE COLUMN image image LONGTEXT NULL");
        DB::statement("ALTER TABLE users MODIFY COLUMN image LONGTEXT NULL DEFAULT ''");

        // Also ensure all other columns have proper non-quoted defaults
        DB::statement("ALTER TABLE users MODIFY COLUMN firstname VARCHAR(255) NULL DEFAULT ''");
        DB::statement("ALTER TABLE users MODIFY COLUMN lastname VARCHAR(255) NULL DEFAULT ''");
        DB::statement("ALTER TABLE users MODIFY COLUMN phone VARCHAR(255) NULL DEFAULT '000-0000'");
        DB::statement("ALTER TABLE users MODIFY COLUMN otp INT NULL DEFAULT 0");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
