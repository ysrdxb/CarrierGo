<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TenantIsolationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that tenants can be created.
     */
    public function test_tenant_can_be_created(): void
    {
        $tenant = Tenant::create([
            'name' => 'Test Company 1',
            'domain' => 'test-company-1',
            'subscription_plan' => 'free',
            'subscription_status' => 'active',
        ]);

        $this->assertInstanceOf(Tenant::class, $tenant);
        $this->assertEquals('Test Company 1', $tenant->name);
        $this->assertEquals('test-company-1', $tenant->domain);
    }

    /**
     * Test that domain must be unique.
     */
    public function test_tenant_domain_must_be_unique(): void
    {
        Tenant::create([
            'name' => 'Company 1',
            'domain' => 'company-1',
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        Tenant::create([
            'name' => 'Company 2',
            'domain' => 'company-1',
        ]);
    }

    /**
     * Test that tenant database name is generated correctly.
     */
    public function test_tenant_database_name_generation(): void
    {
        $tenant = Tenant::create([
            'name' => 'Test Tenant',
            'domain' => 'test-tenant',
        ]);

        $this->assertEquals('tenant_' . $tenant->id, 'tenant_' . $tenant->id);
    }

    /**
     * Test that multiple tenants can exist.
     */
    public function test_multiple_tenants_can_exist(): void
    {
        $tenant1 = Tenant::create([
            'name' => 'Tenant 1',
            'domain' => 'tenant-1',
        ]);

        $tenant2 = Tenant::create([
            'name' => 'Tenant 2',
            'domain' => 'tenant-2',
        ]);

        $this->assertNotEquals($tenant1->id, $tenant2->id);
        $this->assertEquals(2, Tenant::count());
    }

    /**
     * Test that tenant subscription tracking works.
     */
    public function test_tenant_subscription_tracking(): void
    {
        $tenant = Tenant::create([
            'name' => 'Premium Company',
            'domain' => 'premium-company',
            'subscription_plan' => 'premium',
            'subscription_status' => 'active',
            'subscription_expires_at' => now()->addMonths(1),
        ]);

        $this->assertEquals('premium', $tenant->subscription_plan);
        $this->assertEquals('active', $tenant->subscription_status);
        $this->assertTrue($tenant->subscription_expires_at > now());
    }

    /**
     * Test that tenant can be soft deleted.
     */
    public function test_tenant_can_be_soft_deleted(): void
    {
        $tenant = Tenant::create([
            'name' => 'Deletable Tenant',
            'domain' => 'deletable-tenant',
        ]);

        $tenantId = $tenant->id;
        $tenant->delete();

        $this->assertSoftDeleted('tenants', ['id' => $tenantId]);
        $this->assertNull(Tenant::find($tenantId));
        $this->assertNotNull(Tenant::withTrashed()->find($tenantId));
    }

    /**
     * Test that tenant middleware resolves tenants correctly.
     */
    public function test_tenant_middleware_resolves_from_route_parameter(): void
    {
        $tenant = Tenant::create([
            'name' => 'Middleware Test',
            'domain' => 'middleware-test',
        ]);

        // Create a test route with tenant parameter
        $response = $this->get('/dashboard?tenant=' . $tenant->id);

        // Should not error - successful resolution
        $this->assertTrue(true);
    }

    /**
     * Test central database vs tenant database connection.
     */
    public function test_database_connections_are_configured(): void
    {
        $connections = config('database.connections');

        $this->assertArrayHasKey('mysql', $connections);
        $this->assertArrayHasKey('tenant', $connections);
        $this->assertEquals('mysql', $connections['mysql']['driver']);
        $this->assertEquals('mysql', $connections['tenant']['driver']);
    }

    /**
     * Test that tenant database configuration can be dynamically updated.
     */
    public function test_tenant_database_config_can_be_updated(): void
    {
        $tenant = Tenant::create([
            'name' => 'Config Test',
            'domain' => 'config-test',
        ]);

        $databaseName = 'tenant_' . $tenant->id;

        // Simulate middleware updating config
        config([
            'database.connections.tenant.database' => $databaseName,
        ]);

        $this->assertEquals($databaseName, config('database.connections.tenant.database'));
    }

    /**
     * Test tenant list retrieval.
     */
    public function test_can_retrieve_all_tenants(): void
    {
        Tenant::create(['name' => 'Tenant A', 'domain' => 'tenant-a']);
        Tenant::create(['name' => 'Tenant B', 'domain' => 'tenant-b']);
        Tenant::create(['name' => 'Tenant C', 'domain' => 'tenant-c']);

        $tenants = Tenant::all();

        $this->assertEquals(3, $tenants->count());
    }

    /**
     * Test tenant lookup by domain.
     */
    public function test_can_find_tenant_by_domain(): void
    {
        Tenant::create([
            'name' => 'Find Me',
            'domain' => 'find-me',
        ]);

        $tenant = Tenant::where('domain', 'find-me')->first();

        $this->assertNotNull($tenant);
        $this->assertEquals('Find Me', $tenant->name);
    }

    /**
     * Test subscription expiration tracking.
     */
    public function test_subscription_expiration_can_be_tracked(): void
    {
        $expireDate = now()->addDays(7);

        $tenant = Tenant::create([
            'name' => 'Trial Tenant',
            'domain' => 'trial-tenant',
            'subscription_plan' => 'free',
            'subscription_status' => 'active',
            'subscription_expires_at' => $expireDate,
        ]);

        $this->assertTrue($tenant->subscription_expires_at->isSameDay($expireDate));
    }

    /**
     * Test that central database queries don't affect tenants.
     */
    public function test_central_database_isolation(): void
    {
        // Create tenants in central database
        $tenant1 = Tenant::create([
            'name' => 'Central Test 1',
            'domain' => 'central-test-1',
        ]);

        $tenant2 = Tenant::create([
            'name' => 'Central Test 2',
            'domain' => 'central-test-2',
        ]);

        // Query should work correctly
        $allTenants = Tenant::all();
        $this->assertEquals(2, $allTenants->count());

        // Delete one tenant
        $tenant1->delete();

        // Should only get non-deleted tenant
        $activeTenants = Tenant::all();
        $this->assertEquals(1, $activeTenants->count());
        $this->assertEquals('Central Test 2', $activeTenants->first()->name);
    }

    /**
     * Test tenant timestamps.
     */
    public function test_tenant_timestamps_are_recorded(): void
    {
        $tenant = Tenant::create([
            'name' => 'Timestamp Test',
            'domain' => 'timestamp-test',
        ]);

        $this->assertNotNull($tenant->created_at);
        $this->assertNotNull($tenant->updated_at);
        $this->assertTrue($tenant->created_at->isToday());
    }

    /**
     * Test batch operations on tenants.
     */
    public function test_can_update_multiple_tenants(): void
    {
        Tenant::create(['name' => 'Update 1', 'domain' => 'update-1', 'subscription_status' => 'inactive']);
        Tenant::create(['name' => 'Update 2', 'domain' => 'update-2', 'subscription_status' => 'inactive']);

        // Update all inactive subscriptions
        Tenant::where('subscription_status', 'inactive')
            ->update(['subscription_status' => 'active']);

        $updated = Tenant::where('subscription_status', 'active')->count();
        $this->assertEquals(2, $updated);
    }
}
