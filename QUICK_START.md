# Quick Start Guide - CarrierGo MVP

## 🎯 The Problem (SOLVED)

**Error you were seeing:**
```
SQLSTATE[42S02]: Base table or view not found: 1146
Table 'carrierlab.sessions' doesn't exist
```

**Why it happened:**
- Your app was configured to store sessions in the database
- The sessions table didn't exist in the `carrierlab` database

**What we did:**
- ✅ Created the sessions table
- ✅ Fixed multi-tenancy database structure
- ✅ Verified all admin panel routes work

---

## ✅ Current Status

| Component | Status |
|-----------|--------|
| Central Database | ✅ Ready |
| Sessions Table | ✅ Created |
| Admin Panel | ✅ Complete |
| Multi-Tenancy | ✅ Configured |
| Routes | ✅ All 4 registered |

---

## 🚀 Start Using It Now

### Step 1: Open Your Website

```
http://localhost/carriergo/
```

You should NOT see the sessions error anymore!

### Step 2: Access Admin Panel

Login with your existing admin credentials and go to:

**Main Admin Routes:**
- `/admin/tenants` - Manage tenants
- `/admin/subscriptions` - Manage subscriptions
- `/admin/analytics` - View system analytics
- `/admin/users` - Manage admin users

### Step 3: Try The Features

**Tenant Manager** (`/admin/tenants`):
```
- Click "+ New Tenant"
- Enter: Name (e.g., "ABC Logistics")
- Enter: Domain (e.g., "abc-logistics.carriergo.com")
- Select: Plan (Free/Starter/Professional/Enterprise)
- Select: Status (Active)
- Click: "Save Tenant"
```

**Subscription Manager** (`/admin/subscriptions`):
```
- View all tenant subscriptions
- Click "Manage" on any tenant
- View subscription details
- Change plan
- Renew or cancel subscription
```

**Analytics Dashboard** (`/admin/analytics`):
```
- See total tenants count
- View plan distribution
- Check revenue metrics (MRR/ARR)
- See churn rate and alerts
```

**Admin Users** (`/admin/users`):
```
- Click "+ Add Admin User"
- Enter user details
- Assign role
- Save user
```

---

## 📊 Database Structure

**Your Central Database (carrierlab):**
- Tenants table
- Users, Sessions, Permissions, Roles
- All business logic tables (Shipments, Invoices, etc.)
- Everything is isolated by tenant context

**Example:**
When you create a tenant, it works within the central database.
(Advanced setup: Can create separate databases per tenant later)

---

## 📁 Important Files

**Admin Panel Components:**
- `app/Livewire/Admin/TenantManager.php`
- `app/Livewire/Admin/SubscriptionManager.php`
- `app/Livewire/Admin/AnalyticsDashboard.php`
- `app/Livewire/Admin/AdminUserManager.php`

**Documentation:**
- `docs/CURRENT_STATUS.md` - Full status report
- `docs/MULTI_TENANCY_SETUP.md` - Complete setup guide
- `docs/DATABASE_FIX_SUMMARY.md` - What we fixed
- `docs/PROGRESS_TRACKER.md` - Week-by-week progress

---

## 🐛 If You Get Errors

### Error: "Unknown database 'carriergo_tenant_1'"

This means you created a tenant but didn't set up its database.

```bash
# Find the tenant ID from /admin/tenants
# Then run:
php artisan tenant:create-db {ID}

# Example:
php artisan tenant:create-db 1
```

### Error: Sessions issues persist

```bash
# Refresh migrations
php artisan migrate --database=mysql --refresh
```

### Error: Admin panel route not found

```bash
# Clear cached routes
php artisan route:clear
php artisan config:clear
```

---

## 📈 What's Complete (Week 1)

✅ Multi-tenancy infrastructure
✅ Tenant database schema
✅ Tenant registration flow
✅ Tenant isolation (tested)
✅ Admin control panel (4 complete interfaces)
✅ Database sessions (FIXED!)

**Progress: 6/44 tasks (13.6%)**

---

## 🎯 What's Coming (Week 2+)

- UI/UX enhancement with Filament or WireUI
- Customer portal (login, dashboard, tracking)
- Payment processing (Stripe)
- Automated billing
- Public signup flow
- Full launch polish

---

## 💡 Key Concepts

**Tenants:**
- Each customer/company is a separate tenant
- Tenants are managed in the `tenants` table
- Data isolation is handled by the TenantMiddleware

**Sessions:**
- Now stored in `carrierlab.sessions` table
- Automatically created when users login
- Automatically cleaned up by Laravel

**Admin Panel:**
- 4 complete management interfaces
- Beautiful Tailwind CSS design
- Full CRUD operations for tenants
- Subscription & revenue management
- System analytics & insights

---

## 📞 Need Help?

1. Check the docs folder:
   - `docs/MULTI_TENANCY_SETUP.md` - Full architecture
   - `docs/CURRENT_STATUS.md` - Complete status
   - `docs/PROGRESS_TRACKER.md` - Task progress

2. Review the code:
   - `app/Livewire/Admin/*.php` - Component logic
   - `resources/views/livewire/admin/*.blade.php` - UI templates
   - `routes/web.php` - Route definitions

3. Check logs:
   - `storage/logs/laravel.log`

---

## 🎉 You're All Set!

Your CarrierGo MVP is now:
- ✅ Running
- ✅ Free of database errors
- ✅ Ready to manage tenants
- ✅ Ready to track subscriptions
- ✅ Ready for the next phase

**Visit:** `http://localhost/carriergo/` and start exploring! 🚀
