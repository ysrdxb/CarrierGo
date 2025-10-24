<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class CreateTenant extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:create
                            {name : The tenant company name}
                            {--domain= : The tenant domain/subdomain}';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Create a new tenant and provision their database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $domain = $this->option('domain') ?: strtolower(str_replace(' ', '-', $name));

        // Create tenant record in central database
        $tenant = Tenant::create([
            'name' => $name,
            'domain' => $domain,
            'subscription_plan' => 'free',
            'subscription_status' => 'active',
        ]);

        $this->info("✓ Tenant '{$name}' created with ID: {$tenant->id}");

        // Create tenant database
        try {
            $databaseName = 'tenant_' . $tenant->id;
            DB::statement("CREATE DATABASE IF NOT EXISTS `{$databaseName}`");
            $this->info("✓ Database '{$databaseName}' created");
        } catch (\Exception $e) {
            $this->error("✗ Failed to create database: {$e->getMessage()}");
            $tenant->delete();
            return 1;
        }

        // Run migrations on tenant database
        try {
            $this->call('migrate', [
                '--database' => 'tenant',
                '--path' => 'database/migrations/2025_10_23_000100_create_tenant_tables.php',
            ]);
            $this->info("✓ Migrations completed for tenant {$tenant->id}");
        } catch (\Exception $e) {
            $this->error("✗ Failed to run migrations: {$e->getMessage()}");
            return 1;
        }

        // Seed roles and permissions
        try {
            // Switch to tenant database temporarily
            DB::setDefaultConnection('tenant');

            // Run permission and role seeders
            $this->call('db:seed', [
                '--class' => 'PermissionSeeder',
            ]);
            $this->call('db:seed', [
                '--class' => 'RoleSeeder',
            ]);
            $this->call('db:seed', [
                '--class' => 'RolePermissionSeeder',
            ]);

            // Switch back to default connection
            DB::setDefaultConnection('mysql');
            $this->info("✓ Roles and permissions seeded");
        } catch (\Exception $e) {
            $this->error("✗ Failed to seed data: {$e->getMessage()}");
            DB::setDefaultConnection('mysql');
            return 1;
        }

        $this->info("\n✓ Tenant '{$name}' provisioned successfully!");
        $this->info("Domain: {$domain}");
        $this->info("Database: tenant_{$tenant->id}");

        return 0;
    }
}
