<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add tenant_id to users table
        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'tenant_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->unsignedBigInteger('tenant_id')->nullable()->after('id');
                $table->index('tenant_id');
            });
        }

        // Add tenant_id to companies table
        if (Schema::hasTable('companies') && !Schema::hasColumn('companies', 'tenant_id')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->unsignedBigInteger('tenant_id')->nullable()->after('id');
                $table->index('tenant_id');
            });
        }

        // Add tenant_id to orders table
        if (Schema::hasTable('orders') && !Schema::hasColumn('orders', 'tenant_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->unsignedBigInteger('tenant_id')->nullable()->after('id');
                $table->index('tenant_id');
            });
        }

        // Add tenant_id to deliveries table
        if (Schema::hasTable('deliveries') && !Schema::hasColumn('deliveries', 'tenant_id')) {
            Schema::table('deliveries', function (Blueprint $table) {
                $table->unsignedBigInteger('tenant_id')->nullable()->after('id');
                $table->index('tenant_id');
            });
        }

        // Add tenant_id to invoices table
        if (Schema::hasTable('invoices') && !Schema::hasColumn('invoices', 'tenant_id')) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->unsignedBigInteger('tenant_id')->nullable()->after('id');
                $table->index('tenant_id');
            });
        }

        // Add tenant_id to freights table
        if (Schema::hasTable('freights') && !Schema::hasColumn('freights', 'tenant_id')) {
            Schema::table('freights', function (Blueprint $table) {
                $table->unsignedBigInteger('tenant_id')->nullable()->after('id');
                $table->index('tenant_id');
            });
        }

        // Add tenant_id to documents table
        if (Schema::hasTable('documents') && !Schema::hasColumn('documents', 'tenant_id')) {
            Schema::table('documents', function (Blueprint $table) {
                $table->unsignedBigInteger('tenant_id')->nullable()->after('id');
                $table->index('tenant_id');
            });
        }

        // Add tenant_id to destinations table
        if (Schema::hasTable('destinations') && !Schema::hasColumn('destinations', 'tenant_id')) {
            Schema::table('destinations', function (Blueprint $table) {
                $table->unsignedBigInteger('tenant_id')->nullable()->after('id');
                $table->index('tenant_id');
            });
        }

        // Add tenant_id to freight_types table
        if (Schema::hasTable('freight_types') && !Schema::hasColumn('freight_types', 'tenant_id')) {
            Schema::table('freight_types', function (Blueprint $table) {
                $table->unsignedBigInteger('tenant_id')->nullable()->after('id');
                $table->index('tenant_id');
            });
        }

        // Add tenant_id to bank_details table
        if (Schema::hasTable('bank_details') && !Schema::hasColumn('bank_details', 'tenant_id')) {
            Schema::table('bank_details', function (Blueprint $table) {
                $table->unsignedBigInteger('tenant_id')->nullable()->after('id');
                $table->index('tenant_id');
            });
        }

        // Add tenant_id to settings table
        if (Schema::hasTable('settings') && !Schema::hasColumn('settings', 'tenant_id')) {
            Schema::table('settings', function (Blueprint $table) {
                $table->unsignedBigInteger('tenant_id')->nullable()->after('id');
                $table->index('tenant_id');
            });
        }

        // Add tenant_id to reference_numbers table
        if (Schema::hasTable('reference_numbers') && !Schema::hasColumn('reference_numbers', 'tenant_id')) {
            Schema::table('reference_numbers', function (Blueprint $table) {
                $table->unsignedBigInteger('tenant_id')->nullable()->after('id');
                $table->index('tenant_id');
            });
        }
    }

    public function down(): void
    {
        $tables = ['users', 'companies', 'orders', 'deliveries', 'invoices', 'freights', 'documents', 'destinations', 'freight_types', 'bank_details', 'settings', 'reference_numbers'];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'tenant_id')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->dropIndex([$table->getTable() . '_tenant_id_index']);
                    $table->dropColumn('tenant_id');
                });
            }
        }
    }
};
