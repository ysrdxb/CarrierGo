<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Assign tenant_id to existing records
 *
 * This migration assigns all existing records with NULL tenant_id to tenant_id = 1.
 * This allows the SHARED database mode to work with legacy data.
 *
 * NOTE: This assumes tenant with id=1 exists. If it doesn't, you need to create
 * a default tenant first or change the tenant_id value below.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get the first/default tenant ID
        // If no tenants exist, create a default one
        $defaultTenantId = DB::table('tenants')->orderBy('id')->first()?->id;

        if (!$defaultTenantId) {
            // Create a default tenant if none exists
            $defaultTenantId = DB::table('tenants')->insertGetId([
                'name' => 'Default Tenant',
                'domain' => 'default',
                'subscription_plan' => 'basic',
                'subscription_status' => 'active',
                'tenancy_mode' => 'SHARED',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // List of tables to update
        $tables = [
            'users',
            'companies',
            'orders',
            'deliveries',
            'invoices',
            'freights',
            'documents',
            'destinations',
            'freight_types',
            'bank_details',
            'settings',
            'reference_numbers',
            'registrations',
            'onboarding_emails',
        ];

        // Update all NULL tenant_id values to the default tenant
        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'tenant_id')) {
                DB::table($table)
                    ->whereNull('tenant_id')
                    ->update(['tenant_id' => $defaultTenantId]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse: Set tenant_id back to NULL (be careful!)
        $tables = [
            'users',
            'companies',
            'orders',
            'deliveries',
            'invoices',
            'freights',
            'documents',
            'destinations',
            'freight_types',
            'bank_details',
            'settings',
            'reference_numbers',
            'registrations',
            'onboarding_emails',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'tenant_id')) {
                DB::table($table)->update(['tenant_id' => null]);
            }
        }
    }
};
