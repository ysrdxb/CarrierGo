<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Force set image column default using raw SQL
        DB::statement("ALTER TABLE users MODIFY COLUMN image LONGTEXT NULL DEFAULT ''");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
