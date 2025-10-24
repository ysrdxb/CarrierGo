<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

/**
 * TenantScope
 *
 * Global scope that automatically filters all queries to only return
 * records for the current tenant.
 *
 * This scope is applied by the BelongsToTenant trait.
 */
class TenantScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * Automatically adds WHERE tenant_id = current_tenant to all queries.
     * Only filters if tenant context exists.
     */
    public function apply(Builder $builder, Model $model)
    {
        try {
            // Get current tenant ID
            $tenantId = $model->getCurrentTenantId();

            // Only apply filter if we have a valid tenant context
            if ($tenantId) {
                $builder->where($model->getTable() . '.tenant_id', '=', $tenantId);
            }
            // If no tenant context, don't filter (allows public pages to work)
        } catch (\Exception $e) {
            // Log the error but don't fail - allow the query to proceed
            \Log::debug('Error applying TenantScope: ' . $e->getMessage());
        }
    }
}
