<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Stancl\Tenancy\Tenancy;
use App\Models\Tenant;

class TenancyServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // DISABLED: Stancl\Tenancy automatic database switching
        // We use SHARED database mode - all tenants in one database with tenant_id filtering
        // Database switching was causing 500 errors on every request

        // Tenancy::configure()
        //     ->defaultDatabase('tenant')
        //     ->bootstrapUsing(function () {
        //         // This is where the database connection is switched
        //         // to the current tenant's database
        //     })
        //     ->run();
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }
}
