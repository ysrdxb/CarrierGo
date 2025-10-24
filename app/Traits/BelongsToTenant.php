<?php

namespace App\Traits;

use App\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Builder;

/**
 * BelongsToTenant Trait
 *
 * Automatically isolates model data by tenant_id.
 * - Auto-filters queries to only return data for current tenant
 * - Auto-sets tenant_id when creating records
 * - Prevents accidental data leaks between tenants
 *
 * Usage: Add to any model that should be tenant-isolated
 *   use BelongsToTenant;
 */
trait BelongsToTenant
{
    /**
     * Boot the trait.
     * Adds global scope to auto-filter by tenant_id.
     */
    protected static function bootBelongsToTenant()
    {
        // Add global scope to all queries
        // Automatically filters all queries to only return records for the current tenant
        static::addGlobalScope(new TenantScope);

        // Auto-set tenant_id when creating records
        static::creating(function ($model) {
            if (!isset($model->tenant_id) && !$model->tenant_id) {
                $model->tenant_id = static::getCurrentTenantId();
            }
        });
    }

    /**
     * Get the current tenant ID from context.
     *
     * Priority:
     * 1. From app('tenant_id') if set in middleware
     * 2. From authenticated user's tenant_id
     * 3. From request()->tenant_id parameter
     * 4. Return null (will be set manually)
     */
    public static function getCurrentTenantId()
    {
        try {
            // Check if tenant_id is stored in app container (set by middleware)
            if (app()->has('tenant_id')) {
                $tenantId = app('tenant_id');
                if ($tenantId) {
                    return $tenantId;
                }
            }

            // Check if user is authenticated and has tenant_id
            if (auth()->check()) {
                $user = auth()->user();
                if ($user && isset($user->tenant_id) && $user->tenant_id) {
                    return $user->tenant_id;
                }
            }

            // Check request parameter
            if (request()->has('tenant_id')) {
                $tenantId = request('tenant_id');
                if ($tenantId) {
                    return $tenantId;
                }
            }
        } catch (\Exception $e) {
            // Log error but don't fail
            \Log::debug('Error getting current tenant ID: ' . $e->getMessage());
        }

        // No tenant context found
        return null;
    }

    /**
     * Get the name of the tenant column.
     */
    public function getTenantKeyName()
    {
        return 'tenant_id';
    }

    /**
     * Get the value of the tenant key for this model.
     */
    public function getTenantId()
    {
        return $this->{$this->getTenantKeyName()};
    }

    /**
     * Set the tenant ID for this model.
     */
    public function setTenantId($tenantId)
    {
        $this->{$this->getTenantKeyName()} = $tenantId;
        return $this;
    }

    /**
     * Get the relationship query builder constraint.
     * Allows querying records for a specific tenant.
     */
    public function scopeForTenant(Builder $query, $tenantId = null)
    {
        $tenantId = $tenantId ?? static::getCurrentTenantId();

        if ($tenantId) {
            return $query->where('tenant_id', $tenantId);
        }

        return $query;
    }

    /**
     * Query records without tenant scope (careful!).
     * Only use when absolutely necessary.
     */
    public function scopeWithoutTenantScope(Builder $query)
    {
        return $query->withoutGlobalScope(TenantScope::class);
    }

    /**
     * Get the tenant relationship.
     */
    public function tenant()
    {
        return $this->belongsTo(\App\Models\Tenant::class, 'tenant_id');
    }
}
