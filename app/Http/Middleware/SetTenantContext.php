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
            // Always clear any cached tenant_id to prevent stale context
            app()->forgetInstance('tenant_id');

            // If user is authenticated, set fresh tenant context from their tenant_id
            if (auth()->check()) {
                $user = auth()->user();

                // Verify user has a valid tenant_id
                if ($user && $user->tenant_id) {
                    app()->instance('tenant_id', $user->tenant_id);
                    \Log::info('SetTenantContext: Set tenant_id = ' . $user->tenant_id . ' for user ' . $user->email);
                } else {
                    // User is authenticated but has no tenant_id - log and continue
                    \Log::warning('Authenticated user without tenant_id', [
                        'user_id' => $user->id ?? 'unknown',
                        'email' => $user->email ?? 'unknown',
                    ]);
                }
            }
            // If not authenticated, tenant_id remains unset (no queries will be made)
        } catch (\Exception $e) {
            \Log::error('Error in SetTenantContext middleware: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
        }

        return $next($request);
    }
}
