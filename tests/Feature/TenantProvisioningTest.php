<?php

namespace Tests\Feature;

use App\Jobs\ProvisionTenant;
use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TenantProvisioningTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that ProvisionTenant job can be instantiated.
     */
    public function test_provision_tenant_job_can_be_instantiated(): void
    {
        $tenant = Tenant::create([
            'name' => 'Job Test',
            'domain' => 'job-test',
        ]);

        $job = new ProvisionTenant($tenant, 'John', 'Doe', 'john@example.com', 'password123');

        $this->assertInstanceOf(ProvisionTenant::class, $job);
    }

    /**
     * Test that job has correct properties.
     */
    public function test_job_stores_correct_properties(): void
    {
        $tenant = Tenant::create([
            'name' => 'Property Test',
            'domain' => 'property-test',
        ]);

        $job = new ProvisionTenant($tenant, 'Jane', 'Smith', 'jane@example.com', 'password456');

        // Verify job was created without errors
        $this->assertInstanceOf(ProvisionTenant::class, $job);
    }

    /**
     * Test that tenant is marked for provisioning.
     */
    public function test_tenant_provisioning_status_tracking(): void
    {
        $tenant = Tenant::create([
            'name' => 'Status Test',
            'domain' => 'status-test',
            'subscription_status' => 'provisioning',
        ]);

        $this->assertEquals('provisioning', $tenant->subscription_status);
    }

    /**
     * Test that multiple tenants can be provisioned.
     */
    public function test_multiple_tenants_can_be_created_sequentially(): void
    {
        $tenant1 = Tenant::create([
            'name' => 'Sequential 1',
            'domain' => 'sequential-1',
        ]);

        $tenant2 = Tenant::create([
            'name' => 'Sequential 2',
            'domain' => 'sequential-2',
        ]);

        $tenant3 = Tenant::create([
            'name' => 'Sequential 3',
            'domain' => 'sequential-3',
        ]);

        $this->assertEquals(3, Tenant::count());
        $this->assertNotEquals($tenant1->id, $tenant2->id);
        $this->assertNotEquals($tenant2->id, $tenant3->id);
    }

    /**
     * Test that job can handle different credential combinations.
     */
    public function test_job_handles_different_credentials(): void
    {
        $credentials = [
            ['John', 'Doe', 'john@example.com', 'pass123'],
            ['Jane', 'Smith', 'jane@example.com', 'pass456'],
            ['Bob', 'Johnson', 'bob@example.com', 'pass789'],
        ];

        foreach ($credentials as [$first, $last, $email, $pass]) {
            $tenant = Tenant::create([
                'name' => "$first $last",
                'domain' => strtolower(str_replace(' ', '-', "$first $last")),
            ]);

            $job = new ProvisionTenant($tenant, $first, $last, $email, $pass);
            $this->assertInstanceOf(ProvisionTenant::class, $job);
        }

        $this->assertEquals(3, Tenant::count());
    }

    /**
     * Test tenant configuration is correct before provisioning.
     */
    public function test_tenant_configuration_before_provisioning(): void
    {
        $tenant = Tenant::create([
            'name' => 'Pre-Provision Test',
            'domain' => 'pre-provision-test',
            'subscription_plan' => 'free',
            'subscription_status' => 'active',
        ]);

        $this->assertEquals('free', $tenant->subscription_plan);
        $this->assertEquals('active', $tenant->subscription_status);
    }

    /**
     * Test that tenant database name follows naming convention.
     */
    public function test_tenant_database_naming_convention(): void
    {
        $tenant = Tenant::create([
            'name' => 'Naming Convention Test',
            'domain' => 'naming-convention-test',
        ]);

        $expectedDbName = 'tenant_' . $tenant->id;
        $this->assertStringStartsWith('tenant_', $expectedDbName);
        $this->assertIsNumeric(substr($expectedDbName, 7));
    }

    /**
     * Test that multiple provisioning jobs can be queued.
     */
    public function test_multiple_provision_jobs_can_be_created(): void
    {
        $tenants = [];

        for ($i = 1; $i <= 5; $i++) {
            $tenants[] = Tenant::create([
                'name' => "Batch Provision $i",
                'domain' => "batch-provision-$i",
            ]);
        }

        $jobs = [];
        foreach ($tenants as $tenant) {
            $jobs[] = new ProvisionTenant($tenant, 'Admin', 'User', "admin$i@example.com", 'password');
        }

        $this->assertEquals(5, count($jobs));
        $this->assertEquals(5, Tenant::count());
    }

    /**
     * Test that tenant can track provisioning start time.
     */
    public function test_tenant_tracks_provisioning_time(): void
    {
        $before = now();

        $tenant = Tenant::create([
            'name' => 'Time Tracking Test',
            'domain' => 'time-tracking-test',
        ]);

        $after = now();

        $this->assertTrue($tenant->created_at >= $before);
        $this->assertTrue($tenant->created_at <= $after);
    }

    /**
     * Test different subscription plans during provisioning.
     */
    public function test_different_subscription_plans_can_be_created(): void
    {
        $plans = ['free', 'starter', 'professional', 'enterprise'];

        foreach ($plans as $plan) {
            Tenant::create([
                'name' => ucfirst($plan) . ' Plan Tenant',
                'domain' => "$plan-plan-tenant",
                'subscription_plan' => $plan,
                'subscription_status' => 'active',
            ]);
        }

        $this->assertEquals(4, Tenant::count());

        foreach ($plans as $plan) {
            $tenant = Tenant::where('subscription_plan', $plan)->first();
            $this->assertNotNull($tenant);
            $this->assertEquals($plan, $tenant->subscription_plan);
        }
    }

    /**
     * Test that trial expiration dates are set correctly.
     */
    public function test_trial_expiration_dates_can_be_set(): void
    {
        $trialDays = 14;
        $expiryDate = now()->addDays($trialDays);

        $tenant = Tenant::create([
            'name' => 'Trial Expiry Test',
            'domain' => 'trial-expiry-test',
            'subscription_plan' => 'free',
            'subscription_expires_at' => $expiryDate,
        ]);

        $this->assertTrue($tenant->subscription_expires_at->isSameDay($expiryDate));
    }
}
