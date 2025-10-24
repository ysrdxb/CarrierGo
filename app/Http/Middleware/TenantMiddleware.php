<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class TenantMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get tenant from request (subdomain or parameter)
        $tenantId = $this->getTenantId($request);

        if (!$tenantId) {
            return $next($request);
        }

        // Find tenant
        $tenant = Tenant::find($tenantId);

        if (!$tenant) {
            abort(404, 'Tenant not found');
        }

        // Switch database connection to tenant database
        $databaseName = 'tenant_' . $tenant->id;

        config([
            'database.connections.tenant.database' => $databaseName,
        ]);

        // Set the current tenant in session/context
        $request->attributes->set('tenant', $tenant);
        session(['tenant_id' => $tenant->id]);

        return $next($request);
    }

    /**
     * Get tenant ID from request.
     * Can be from subdomain, domain, or route parameter.
     */
    protected function getTenantId(Request $request): ?int
    {
        // Check route parameter first
        if ($request->route('tenant')) {
            return $request->route('tenant');
        }

        // Check from subdomain
        $subdomain = $this->getSubdomain($request);
        if ($subdomain && $subdomain !== 'www') {
            $tenant = Tenant::where('domain', $subdomain)->first();
            if ($tenant) {
                return $tenant->id;
            }
        }

        return null;
    }

    /**
     * Get subdomain from request.
     */
    protected function getSubdomain(Request $request): ?string
    {
        $host = $request->getHost();
        $parts = explode('.', $host);

        if (count($parts) > 2) {
            return $parts[0];
        }

        return null;
    }
}
