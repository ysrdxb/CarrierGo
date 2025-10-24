<?php

namespace App\Jobs;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

class ProvisionTenant implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Tenant $tenant;
    protected string $firstname;
    protected string $lastname;
    protected string $email;
    protected string $password;
    protected bool $isPlainText; // Is password plain text (not hashed)?
    protected string $tenancyMode; // 'SHARED' or 'SEPARATE'

    /**
     * Create a new job instance.
     *
     * @param Tenant $tenant
     * @param string $firstname
     * @param string $lastname
     * @param string $email
     * @param string $password - Plain text password (will be hashed)
     * @param bool $isPlainText - Set to true if password is plain text (default: true)
     * @param string $tenancyMode - 'SHARED' (default) or 'SEPARATE'
     */
    public function __construct(Tenant $tenant, string $firstname, string $lastname, string $email, string $password, bool $isPlainText = true, string $tenancyMode = 'SHARED')
    {
        $this->tenant = $tenant;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->email = $email;
        $this->password = $password;
        $this->isPlainText = $isPlainText;
        $this->tenancyMode = strtoupper($tenancyMode);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Update tenant with tenancy mode
            $this->tenant->update(['tenancy_mode' => $this->tenancyMode]);

            if ($this->tenancyMode === 'SEPARATE') {
                // SEPARATE MODE: Create separate database for tenant
                $this->provisionSeparateDatabase();
            } else {
                // SHARED MODE: Just create user in central database
                $this->provisionSharedDatabase();
            }

            // Mark tenant as ready
            $this->tenant->update(['subscription_status' => 'active']);

            \Log::info("Successfully provisioned tenant {$this->tenant->id} in {$this->tenancyMode} mode");

        } catch (\Exception $e) {
            dd("Failed to provision tenant {$this->tenant->id}: " . $e->getMessage());
            $this->tenant->update(['subscription_status' => 'failed']);
            throw $e;
        }
    }

    /**
     * Provision tenant in SEPARATE database mode.
     */
    private function provisionSeparateDatabase(): void
    {
        // Create tenant database
        $this->createTenantDatabase();

        // Run migrations for tenant
        $this->runMigrations();

        // Seed roles and permissions
        $this->seedData();

        // Create admin user for tenant
        $this->createAdminUser();
    }

    /**
     * Provision tenant in SHARED database mode.
     * Creates admin user in central database with tenant_id for data isolation.
     */
    private function provisionSharedDatabase(): void
    {
        try {
            // Make sure we're using the central database
            DB::setDefaultConnection('mysql');

            // Hash password if it's plain text
            $hashedPassword = $this->isPlainText ? Hash::make($this->password) : $this->password;

            // Create admin user in central database with tenant_id
            $user = User::create([
                'tenant_id' => $this->tenant->id, // <-- Key: Isolate by tenant_id
                'firstname' => $this->firstname,
                'lastname' => $this->lastname,
                'email' => $this->email,
                'phone' => '+1-555-0000',
                'email_verified_at' => now(),
                'password' => $hashedPassword,
                'otp' => rand(100000, 999999),
                'otp_expiry' => now()->addHours(1)->toDateTimeString(),
                'image' => '',
                'start_date' => now()->toDateString(),
                'end_date' => null,
            ]);

            // Assign Super Admin role
            $user->assignRole('Super Admin');

            \Log::info("Successfully created admin user {$this->email} for tenant {$this->tenant->id} in SHARED mode");

        } catch (\Exception $e) {
            \Log::error("Failed to create admin user in SHARED mode for tenant {$this->tenant->id}: " . $e->getMessage());
            throw new \Exception("Failed to create admin user: " . $e->getMessage());
        }
    }

    /**
     * Create the tenant database.
     */
    protected function createTenantDatabase(): void
    {
        $databaseName = 'carriergo_tenant_' . $this->tenant->id;

        try {
            DB::statement("CREATE DATABASE IF NOT EXISTS `{$databaseName}`");
            \Log::info("Successfully created database {$databaseName} for tenant {$this->tenant->id}");
        } catch (\Exception $e) {
            throw new \Exception("Failed to create database {$databaseName}: " . $e->getMessage());
        }
    }

    /**
     * Run migrations for the tenant.
     */
    protected function runMigrations(): void
    {
        try {
            // Get current connection config
            $tenantDb = config('database.connections.tenant');
            $tenantDb['database'] = 'carriergo_tenant_' . $this->tenant->id;

            // Temporarily update the tenant connection
            config(['database.connections.tenant' => $tenantDb]);

            // Run migrations on tenant database
            Artisan::call('migrate', [
                '--database' => 'tenant',
                '--path' => 'database/migrations/2025_10_24_065000_create_tenant_business_tables.php',
            ]);

            \Log::info("Successfully ran migrations for tenant {$this->tenant->id}");

        } catch (\Exception $e) {
            throw new \Exception("Failed to run migrations: " . $e->getMessage());
        }
    }

    /**
     * Seed roles and permissions for the tenant.
     */
    protected function seedData(): void
    {
        try {
            // Set tenant database connection temporarily
            DB::setDefaultConnection('tenant');

            // Run seeders
            Artisan::call('db:seed', [
                '--class' => 'PermissionSeeder',
            ]);

            Artisan::call('db:seed', [
                '--class' => 'RoleSeeder',
            ]);

            Artisan::call('db:seed', [
                '--class' => 'RolePermissionSeeder',
            ]);

            // Reset to default connection
            DB::setDefaultConnection('mysql');

        } catch (\Exception $e) {
            DB::setDefaultConnection('mysql');
            throw new \Exception("Failed to seed data: " . $e->getMessage());
        }
    }

    /**
     * Create the admin user for the tenant.
     */
    protected function createAdminUser(): void
    {
        try {
            // Switch to tenant database
            $tenantDb = config('database.connections.tenant');
            $tenantDb['database'] = 'carriergo_tenant_' . $this->tenant->id;
            config(['database.connections.tenant' => $tenantDb]);

            DB::setDefaultConnection('tenant');

            // Hash password if it's plain text
            $hashedPassword = $this->isPlainText ? Hash::make($this->password) : $this->password;

            // Create admin user
            $user = User::create([
                'firstname' => $this->firstname,
                'lastname' => $this->lastname,
                'email' => $this->email,
                'phone' => '+1-555-0000',
                'email_verified_at' => now(),
                'password' => $hashedPassword,
                'otp' => rand(100000, 999999),
                'otp_expiry' => now()->addHours(1)->toDateTimeString(),
                'image' => '',
                'start_date' => now()->toDateString(),
                'end_date' => null,
            ]);

            // Assign Super Admin role
            $user->assignRole('Super Admin');

            \Log::info("Successfully created admin user {$this->email} for tenant {$this->tenant->id}");

            // Reset to default connection
            DB::setDefaultConnection('mysql');

        } catch (\Exception $e) {
            DB::setDefaultConnection('mysql');
            \Log::error("Failed to create admin user for tenant {$this->tenant->id}: " . $e->getMessage());
            throw new \Exception("Failed to create admin user: " . $e->getMessage());
        }
    }
}
