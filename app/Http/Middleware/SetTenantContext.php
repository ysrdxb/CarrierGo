<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * SetTenantContext Middleware
 *
 * Sets the current tenant context from the authenticated user's tenant_id.
 * This allows the BelongsToTenant trait to automatically filter queries.
 */
class SetTenantContext
{
    public function handle(Request $request, Closure $next)
    {
        // SHARED database mode: just set tenant_id in app container
        // No database switching needed - all tenants use same 'carrierlab' database
        // Data isolation is handled by BelongsToTenant trait (adds tenant_id filter to queries)

        try {
            if (auth()->check() && auth()->user() && auth()->user()->tenant_id) {
                \Log::debug('SetTenantContext: tenant_id = ' . auth()->user()->tenant_id);
                app()->instance('tenant_id', auth()->user()->tenant_id);
            }
        } catch (\Exception $e) {
            \Log::error('SetTenantContext error: ' . $e->getMessage());
            // Continue without tenant context
        }

        return $next($request);
    }
}
