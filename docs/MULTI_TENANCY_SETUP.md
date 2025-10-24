# Multi-Tenancy Setup Guide

## Architecture Overview

CarrierGo uses a **hybrid multi-tenancy model** with the following structure:

### Database Organization

**Central Database (`carrierlab`):**
- `tenants` - Tenant records and subscription info
- `users` - Admin/system users (optional: can be tenant-specific)
- `sessions` - Laravel sessions
- `cache` - Laravel cache
- `jobs` - Laravel queue jobs
- `migrations` - Migration tracking
- `permissions`, `roles`, `model_has_roles` - Spatie Permission tables
- All business logic tables (for default/primary tenant)

**Tenant Databases (Individual per tenant):**
- `carriergo_tenant_1`, `carriergo_tenant_2`, etc.
- Contains complete copy of all business logic tables
- Completely isolated from other tenants

## Database Naming Convention

- **Central DB:** `carrierlab`
- **Tenant DBs:** `carriergo_tenant_<tenant_id>`

Example: If a tenant has ID=5, its database is `carriergo_tenant_5`

## Setup Instructions

### 1. Initial Central Database Setup (Already Done)

```bash
php artisan migrate --database=mysql
```

This creates:
- Sessions table (fixes the "sessions doesn't exist" error)
- Users table for admin authentication
- Permissions/roles tables
- All business logic tables

### 2. Creating a New Tenant

The tenant is created via the registration form or admin panel:

```bash
# Via Admin Panel: /admin/tenants -> Create New Tenant
# Or via Artisan:
php artisan tenant:create {name} {domain} {plan} {status}
```

### 3. Setting Up Tenant Database

After creating a tenant in the admin panel:

```bash
# Create and provision the tenant database
php artisan tenant:create-db {tenant_id}
```

**Example:**
```bash
php artisan tenant:create-db 1
```

This will:
1. Create `carriergo_tenant_1` database
2. Run all migrations on that database
3. Set up complete isolation for that tenant

## Tenant Resolution

### How Tenants Are Identified

The system resolves the current tenant using:

1. **Subdomain:** `tenant-name.carriergo.local` → resolves to tenant
2. **Route Parameter:** `/tenant/1/dashboard` → resolves to tenant with ID=1
3. **Session:** If already set, uses session tenant

### Middleware Flow

```
Request → TenantMiddleware
    ↓
Resolve tenant from subdomain/route/session
    ↓
Switch database connection to tenant's database
    ↓
Store tenant in session
    ↓
Process request with tenant context
```

## Configuration

### `.env` File

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=carrierlab
DB_USERNAME=root
DB_PASSWORD=

TENANT_DB_CONNECTION=tenant
TENANT_DB_HOST=127.0.0.1
TENANT_DB_PORT=3306
TENANT_DB_USERNAME=root
TENANT_DB_PASSWORD=
```

### `config/database.php`

**Central Connection (mysql):**
```php
'mysql' => [
    'driver' => 'mysql',
    'host' => env('DB_HOST', '127.0.0.1'),
    'database' => env('DB_DATABASE', 'laravel'),
    'username' => env('DB_USERNAME', 'root'),
    // ...
],
```

**Tenant Connection:**
```php
'tenant' => [
    'driver' => 'mysql',
    'host' => env('DB_HOST', '127.0.0.1'),
    'database' => env('TENANT_DB_DATABASE', 'tenant'),
    'username' => env('DB_USERNAME', 'root'),
    // ...
],
```

## Data Isolation Guarantees

✅ **Complete isolation between tenants:**
- Each tenant has its own database
- No way to query another tenant's data
- Session-based tenant context ensures correct database switching

✅ **Admin panel remains separate:**
- Admin users authenticate against central database
- Admin can manage all tenants
- Never accidentally accesses tenant data directly

✅ **Automatic database switching:**
- Middleware automatically switches connection based on tenant
- Eloquent queries use correct database automatically

## Common Operations

### Access Tenant from Request

```php
// In a Livewire component or controller
$tenant = session('tenant');
// or
$tenant = auth()->user()->tenant; // if users have tenant relationship
```

### Query Tenant Data

```php
// Switch to tenant database
Auth::setDefaultDriver('tenant');
DB::setDefaultConnection('tenant');

// Or specify connection
Shipment::on('tenant')->where('status', 'delivered')->get();
```

### Run Artisan Command for Specific Tenant

```bash
php artisan tinker --database=tenant
```

Then in Tinker:
```php
config(['database.connections.tenant.database' => 'carriergo_tenant_1']);
\App\Models\Shipment::count();
```

## Troubleshooting

### Error: "Table 'carrierlab.sessions' doesn't exist"

**Solution:** Run migrations on central database
```bash
php artisan migrate --database=mysql
```

### Error: "Unknown database 'carriergo_tenant_1'"

**Solution:** Create the tenant database
```bash
php artisan tenant:create-db 1
```

### Accessing Wrong Tenant's Data

**Solution:** Verify middleware is configured correctly
- Check `bootstrap/app.php` for `TenantMiddleware` registration
- Ensure tenant is being resolved from subdomain/route/session
- Verify database connection is switched before queries

### Sessions Not Working

**Solution:** Ensure sessions table exists in central database
```bash
php artisan migrate:refresh --database=mysql
```

## Testing Multi-Tenancy

### Test in Code

```php
// Create test tenant
$tenant = Tenant::create([
    'name' => 'Test Company',
    'domain' => 'test.carriergo.local',
]);

// Create test database
Artisan::call('tenant:create-db', ['id' => $tenant->id]);

// Access tenant data
Config::set('database.connections.tenant.database', $tenant->getDatabaseName());
DB::setDefaultConnection('tenant');

$shipment = Shipment::create([...]);
```

### Test via Browser

1. Add to `/etc/hosts`:
   ```
   127.0.0.1 test.carriergo.local
   ```

2. Visit: `http://test.carriergo.local/dashboard`
   - Should automatically resolve to tenant
   - Should use `carriergo_tenant_<id>` database

## Best Practices

✅ **Do:**
- Always run migrations on both central and tenant databases
- Use middleware to handle automatic database switching
- Store tenant context in session
- Use Tenant model as source of truth for tenant info
- Test with multiple tenants regularly

❌ **Don't:**
- Query central database when you need tenant data
- Hardcode database names - use Tenant model methods
- Skip the TenantMiddleware in routes
- Store tenant-specific data in central database
- Forget to switch database before querying tenant tables

## Migration Strategy

For **large deployments:**
1. Maintain separate migration paths for central vs tenant databases
2. Use `--path` flag: `php artisan migrate --path=database/migrations/tenant`
3. Automate tenant database creation via event listeners

For **development:**
1. Use single command: `php artisan migrate`
2. Manually run `tenant:create-db {id}` for each test tenant
3. Use `.env.testing` for test database configuration

## Performance Considerations

- Database switching happens per-request (minimal overhead)
- Connection pooling recommended for production
- Consider caching tenant resolution in Redis
- Monitor slow queries per-tenant basis

---

**For support:** See docs/CURRENT_TASK.md and docs/PROGRESS_TRACKER.md
