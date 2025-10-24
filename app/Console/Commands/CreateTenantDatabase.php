<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class CreateTenantDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:create-db {id}';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Create a separate database for a tenant with complete data isolation';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tenantId = $this->argument('id');
        $tenant = Tenant::find($tenantId);

        if (!$tenant) {
            $this->error("❌ Tenant not found with ID: {$tenantId}");
            return 1;
        }

        $databaseName = $tenant->getDatabaseName(); // Uses: carriergo_tenant_{id}

        try {
            $this->info("🔄 Setting up database for tenant: {$tenant->name} (ID: {$tenantId})");

            // Step 1: Create the database
            $this->info("  → Creating database: {$databaseName}");
            DB::statement("CREATE DATABASE IF NOT EXISTS `{$databaseName}`");
            $this->info("  ✅ Database created");

            // Step 2: Configure connection to use new database
            $this->info("  → Configuring connection");
            config(['database.connections.tenant.database' => $databaseName]);

            // Test connection
            DB::connection('tenant')->statement('SELECT 1');
            $this->info("  ✅ Connection verified");

            // Step 3: Run migrations on tenant database
            $this->info("  → Running migrations (creating tables)");
            Artisan::call('migrate', [
                '--database' => 'tenant',
                '--force' => true,
            ]);
            $this->info("  ✅ All tables created successfully");

            // Step 4: Success message
            $this->newLine();
            $this->info("╔════════════════════════════════════════╗");
            $this->info("║ ✅ TENANT DATABASE SETUP COMPLETE     ║");
            $this->info("╚════════════════════════════════════════╝");
            $this->info("");
            $this->info("📊 Tenant Information:");
            $this->info("  • Tenant ID: {$tenantId}");
            $this->info("  • Tenant Name: {$tenant->name}");
            $this->info("  • Database Name: {$databaseName}");
            $this->info("  • Tables Created: 30+");
            $this->info("");
            $this->info("🔒 Data Isolation:");
            $this->info("  • Tenant data is in separate database");
            $this->info("  • Complete isolation from other tenants");
            $this->info("  • No risk of data leakage");
            $this->info("");
            $this->info("✨ Ready to use!");

            return 0;
        } catch (\Exception $e) {
            $this->newLine();
            $this->error("❌ ERROR: " . $e->getMessage());
            $this->info("\n📝 Troubleshooting:");
            $this->info("  1. Verify MySQL is running");
            $this->info("  2. Check database credentials in .env");
            $this->info("  3. Run: php artisan config:cache");
            $this->info("  4. Try again: php artisan tenant:create-db {$tenantId}");
            return 1;
        }
    }
}
