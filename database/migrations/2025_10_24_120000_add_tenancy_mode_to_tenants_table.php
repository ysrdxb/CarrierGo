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
        Schema::table('tenants', function (Blueprint $table) {
            // Add tenancy mode column (SHARED = central DB, SEPARATE = dedicated DB)
            $table->enum('tenancy_mode', ['SHARED', 'SEPARATE'])->default('SHARED')->after('domain');
            
            // Add database connection name for SEPARATE mode (e.g., 'tenant_5')
            $table->string('database_connection')->nullable()->after('tenancy_mode')->comment('Database config for SEPARATE mode');
            
            // Add index for easier lookups
            $table->index('tenancy_mode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropIndex(['tenancy_mode']);
            $table->dropColumn('database_connection');
            $table->dropColumn('tenancy_mode');
        });
    }
};
