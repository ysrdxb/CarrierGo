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
        try {
            // Only process authenticated users
            if (auth()->check()) {
                $user = auth()->user();

                // Verify user has a valid tenant_id
                if ($user && $user->tenant_id) {
                    app()->instance('tenant_id', $user->tenant_id);
                    \Log::info('SetTenantContext: tenant_id=' . $user->tenant_id . ' for user=' . $user->email);
                } else {
                    // User is authenticated but has no tenant_id - log warning
                    \Log::warning('Authenticated user without tenant_id', [
                        'user_id' => $user->id ?? 'unknown',
                        'email' => $user->email ?? 'unknown',
                    ]);
                }
            }
            // If not authenticated, tenant_id remains unset (no queries will be made)
        } catch (\Throwable $e) {
            \Log::error('SetTenantContext ERROR: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
        }

        return $next($request);
    }
}
