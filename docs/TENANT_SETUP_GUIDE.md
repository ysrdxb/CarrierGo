# Tenant Setup Guide - Step by Step

## Overview

Your CarrierGo system now uses **database-level multi-tenancy**, which means:
- ✅ **Each tenant gets their own database** → No `tenant_id` column needed
- ✅ **Physical data isolation** → Completely secure
- ✅ **Automatic database switching** → Handled by middleware
- ✅ **Unlimited scalability** → Each tenant scales independently

---

## Step 1: Create a Tenant in Admin Panel

### Visit: `http://localhost/carriergo/admin/tenants`

**Or access the route:**
```
/admin/tenants
```

### Click "+ New Tenant" Button

Fill in the form:

| Field | Example Value | Notes |
|-------|---------------|-------|
| **Tenant Name** | ABC Logistics | Display name for the company |
| **Domain** | abc.carriergo.local | Used for subdomain routing |
| **Subscription Plan** | professional | free/starter/professional/enterprise |
| **Status** | active | active/suspended/cancelled |

**Form Fields Explained:**

```
Tenant Name:
  └─ "ABC Logistics" - What you call the company
     Used in admin dashboard and reports

Domain:
  └─ "abc-logistics.carriergo.local" - How they access the system
     When they visit this domain, system knows it's their tenant
     Middleware uses this to switch databases

Subscription Plan:
  └─ free    = 10 shipments, 2 users
  └─ starter = 100 shipments, 5 users
  └─ professional = 1000 shipments, 20 users
  └─ enterprise = Unlimited

Status:
  └─ active = Tenant can use the system
  └─ suspended = Access blocked (billing issue)
  └─ cancelled = Subscription ended
```

### Click "Save Tenant"

Result:
```
✅ Tenant record created in central database (carrierlab.tenants)
✅ You'll see the new tenant in the list with ID (e.g., ID: 1)
📝 Note the ID - you'll use it in Step 2
```

---

## Step 2: Create Tenant Database

This creates a **separate database** with all their tables.

### Get Tenant ID

From the admin panel, look at the tenant you created:
```
ID | Name             | Domain
1  | ABC Logistics    | abc-logistics.carriergo.local
```

The ID is: `1`

### Run Command in Terminal

```bash
php artisan tenant:create-db 1
```

### Expected Output

```
🔄 Setting up database for tenant: ABC Logistics (ID: 1)
  → Creating database: carriergo_tenant_1
  ✅ Database created
  → Configuring connection
  ✅ Connection verified
  → Running migrations (creating tables)
  ✅ All tables created successfully

╔════════════════════════════════════════╗
║ ✅ TENANT DATABASE SETUP COMPLETE     ║
╚════════════════════════════════════════╝

📊 Tenant Information:
  • Tenant ID: 1
  • Tenant Name: ABC Logistics
  • Database Name: carriergo_tenant_1
  • Tables Created: 30+

🔒 Data Isolation:
  • Tenant data is in separate database
  • Complete isolation from other tenants
  • No risk of data leakage

✨ Ready to use!
```

---

## Step 3: Verify Database Was Created

### Check Database Exists

```bash
# In MySQL, you can verify:
# SHOW DATABASES LIKE 'carriergo_tenant_%';

# Or via PHP/Artisan:
php artisan tinker

# Then type:
>>> DB::connection('mysql')->select("SHOW DATABASES LIKE 'carriergo_tenant_%'")
>>> exit
```

### Check Tables Were Created

```bash
php artisan tinker

# Type:
>>> config(['database.connections.tenant.database' => 'carriergo_tenant_1']);
>>> DB::connection('tenant')->select("SHOW TABLES");

# You should see output like:
# Tables_in_carriergo_tenant_1
# ├── users
# ├── shipments
# ├── invoices
# ├── orders
# ├── documents
# └── ... (30+ tables)

>>> exit
```

---

## Step 4: Test Tenant Access (Optional)

### Add to /etc/hosts (Windows/Mac/Linux)

This makes the domain work locally:

**Windows:**
```
Edit: C:\Windows\System32\drivers\etc\hosts

Add line:
127.0.0.1 abc-logistics.carriergo.local
```

**Mac/Linux:**
```bash
sudo nano /etc/hosts

Add line:
127.0.0.1 abc-logistics.carriergo.local
```

### Visit Tenant Domain

```
http://abc-logistics.carriergo.local/
```

**What Should Happen:**
1. Request comes to `abc-logistics` subdomain
2. TenantMiddleware extracts `abc-logistics`
3. Queries: `SELECT * FROM tenants WHERE domain = 'abc-logistics.carriergo.local'`
4. Finds: Tenant 1
5. Switches database to: `carriergo_tenant_1`
6. Shows tenant's data (empty tables initially)

---

## Step 5: Create Additional Tenants (Repeat)

For each new tenant:

### 1️⃣ Admin Panel
```
Go to: /admin/tenants
Click: "+ New Tenant"
Fill: Name, Domain, Plan, Status
Save: "Save Tenant"
```

### 2️⃣ Setup Database
```bash
# Get the new tenant ID (e.g., 2)
php artisan tenant:create-db 2
```

### 3️⃣ (Optional) Add to hosts
```
127.0.0.1 xyz-transport.carriergo.local
```

---

## Complete Example: Two Tenants

### Tenant 1: ABC Logistics

```
┌─────────────────────────────────────┐
│ carrierlab.tenants                  │
├─────────────────────────────────────┤
│ ID=1                                │
│ Name: ABC Logistics                 │
│ Domain: abc-logistics.local         │
│ Plan: professional                  │
│ Status: active                      │
└─────────────────────────────────────┘
              ↓
    [php artisan tenant:create-db 1]
              ↓
┌─────────────────────────────────────┐
│ carriergo_tenant_1 (NEW DATABASE)   │
├─────────────────────────────────────┤
│ users (ABC employees)               │
│ shipments (ABC shipments)           │
│ invoices (ABC invoices)             │
│ orders (ABC orders)                 │
│ ... (30+ tables)                    │
└─────────────────────────────────────┘
```

### Tenant 2: XYZ Transport

```
┌─────────────────────────────────────┐
│ carrierlab.tenants                  │
├─────────────────────────────────────┤
│ ID=2                                │
│ Name: XYZ Transport                 │
│ Domain: xyz-transport.local         │
│ Plan: starter                       │
│ Status: active                      │
└─────────────────────────────────────┘
              ↓
    [php artisan tenant:create-db 2]
              ↓
┌─────────────────────────────────────┐
│ carriergo_tenant_2 (NEW DATABASE)   │
├─────────────────────────────────────┤
│ users (XYZ employees)               │
│ shipments (XYZ shipments)           │
│ invoices (XYZ invoices)             │
│ orders (XYZ orders)                 │
│ ... (30+ tables)                    │
└─────────────────────────────────────┘
```

### Admin Dashboard (Still Central)

```
┌─────────────────────────────────────┐
│ carrierlab (CENTRAL DATABASE)       │
├─────────────────────────────────────┤
│ tenants (shows both)                │
│ ├─ ID=1: ABC Logistics              │
│ └─ ID=2: XYZ Transport              │
│                                     │
│ users (admin users only)            │
│ sessions (Laravel sessions)         │
│ permissions (shared)                │
│ roles (shared)                      │
└─────────────────────────────────────┘
        ↑           ↑
   Admin accesses  Everything!
```

---

## FAQ

### Q: Do I need a `tenant_id` column?

**A:** No! Database isolation replaces row-level filtering.

```
Instead of:
  SELECT * FROM users WHERE tenant_id = 1;
  SELECT * FROM shipments WHERE tenant_id = 1;

We do:
  DB::connection('tenant')->table('users')->all();
  // Database is already tenant_1, so all data is tenant 1's
```

### Q: What if someone hacks the login?

**A:** They can only access the central database:

```
Hacked user (no tenant context)
  ↓
Database defaults to: carrierlab
  ↓
They can see:
  - Tenant list (public info)
  - Their own admin user
  ↓
They CANNOT see:
  - Any shipment data
  - Any employee data
  - Any invoice data
  - Any private tenant data

Why? All that data is in carriergo_tenant_1,
carriergo_tenant_2, etc. - separate databases!
```

### Q: How do I backup a tenant?

**A:** Backup just that database!

```bash
# Backup tenant 1
mysqldump -u root carriergo_tenant_1 > backup_tenant_1.sql

# Backup all tenants
mysqldump -u root --databases carriergo_tenant_1 carriergo_tenant_2 > backup_all_tenants.sql

# Backup central database
mysqldump -u root carrierlab > backup_central.sql
```

### Q: Can I move a tenant to different server?

**A:** Yes! Just copy the database:

```bash
# Export tenant database
mysqldump -u root carriergo_tenant_1 > tenant_1_export.sql

# Import on new server
mysql -u root < tenant_1_export.sql

# Update DNS/hosts to point to new server
# Done! Tenant completely migrated
```

### Q: What about database growth?

**A:** Each tenant's database grows independently:

```
carriergo_tenant_1: 500 MB (large customer)
carriergo_tenant_2: 50 MB (small customer)
carriergo_tenant_3: 100 MB (medium customer)
carrierlab: 5 MB (admin data only)
```

No bloat in central database!

### Q: Can I convert to shared database later?

**A:** Yes, but not recommended:

```
You would need to:
1. Add tenant_id column to all tables
2. Update all 30+ table schemas
3. Migrate data from separate databases to one
4. Update all queries to filter by tenant_id
5. Test extensively

Better approach:
- Keep separate databases
- It's the gold standard for SaaS
```

---

## Troubleshooting

### Problem: "Unknown database 'carriergo_tenant_1'"

**Cause:** You created a tenant but didn't run `tenant:create-db`

**Solution:**
```bash
# Get the tenant ID from /admin/tenants
php artisan tenant:create-db 1
```

### Problem: "Access denied for user 'root'@'localhost'"

**Cause:** Database credentials in .env are wrong

**Solution:**
1. Check .env file
2. Verify MySQL credentials
3. Test: `mysql -u root` (if password is empty)
4. Run: `php artisan config:clear`
5. Try again: `php artisan tenant:create-db 1`

### Problem: "SQLSTATE[42000]: Syntax error"

**Cause:** Database permission issue

**Solution:**
```bash
# Ensure user can create databases
# In MySQL:
GRANT ALL PRIVILEGES ON *.* TO 'root'@'localhost';
FLUSH PRIVILEGES;
```

### Problem: Tenant visits domain, sees different tenant's data

**Cause:** TenantMiddleware not working

**Solution:**
1. Check `app/Http/Middleware/TenantMiddleware.php` exists
2. Verify it's registered in `bootstrap/app.php`
3. Run: `php artisan config:clear && php artisan route:clear`
4. Check Laravel logs: `storage/logs/laravel.log`

---

## Command Reference

```bash
# Create tenant (admin panel recommended)
php artisan tenant:create "Name" "domain.local" "plan" "status"

# Setup database for tenant
php artisan tenant:create-db {id}

# View database migrations status
php artisan migrate:status --database=mysql

# Verify tenant database
php artisan tinker
>>> config(['database.connections.tenant.database' => 'carriergo_tenant_1']);
>>> DB::connection('tenant')->select("SHOW TABLES");
```

---

## Next Steps

1. ✅ Create tenant in admin panel
2. ✅ Run `php artisan tenant:create-db {id}`
3. ✅ (Optional) Add to /etc/hosts
4. ✅ Visit tenant domain or admin panel
5. ✅ Repeat for more tenants
6. 📋 Ready for Week 2 - UI/UX enhancement!

---

**Architecture:** Database-per-Tenant (Recommended)
**Isolation:** Complete physical separation
**Security:** Enterprise-grade
**Scalability:** Unlimited

You now have true multi-tenant SaaS architecture! 🚀
