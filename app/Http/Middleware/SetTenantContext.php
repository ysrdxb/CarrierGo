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
    public function handle($request, Closure $next)
    {
        // Only initialize tenant if user is logged in AND has a tenant_id
        if (auth()->check() && auth()->user()->tenant_id) {
            try {
                tenancy()->initialize(auth()->user()->tenant_id);
            } catch (\Exception $e) {
                \Log::error('Tenant context error: ' . $e->getMessage());
                // Continue without tenant context
            }
        }
        
        return $next($request);
    }
}
