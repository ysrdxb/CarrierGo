# Tenant Onboarding Guide - Complete Flow

## Overview

There are **two different ways** tenants are created and set up in CarrierGo:

1. **Self-Registration Flow** - Tenant signs up themselves at `/register`
2. **Admin-Created Flow** - Admin creates tenant in admin panel

This guide explains both flows and what happens next.

---

## Flow 1: Self-Registration (Recommended for Customers)

### What Happens:

```
1. Customer visits: http://carriergo.com/register
2. Fills in form:
   ├─ Company Name: "ABC Logistics"
   ├─ Domain: "abc-logistics" (auto-generated)
   ├─ First Name: "John"
   ├─ Last Name: "Doe"
   ├─ Email: "john@abclogistics.com"
   ├─ Password: "SecurePassword123"
   └─ Accept Terms

3. System automatically:
   ├─ Creates tenant in carrierlab.tenants
   ├─ Dispatches ProvisionTenant job which:
   │  ├─ Creates database: carriergo_tenant_1
   │  ├─ Runs all migrations
   │  ├─ Seeds roles and permissions
   │  └─ Creates first admin user (John Doe)

4. System shows: "Success! Your account is being set up..."

5. Customer redirected to: /login

6. Customer logs in with:
   ├─ Email: john@abclogistics.com
   ├─ Password: SecurePassword123
   └─ Visits: abc-logistics.carriergo.com

7. Customer can now use the system!
```

### Step-by-Step for Self-Registration:

```bash
# 1. Customer visits public registration page
http://carriergo.com/register

# 2. Fills form with:
#    - Company: ABC Logistics
#    - Domain: abc-logistics
#    - Name: John Doe
#    - Email: john@abclogistics.com
#    - Password: SecurePassword123

# 3. Clicks "Create Account"

# 4. Behind the scenes:
#    - Tenant created in carrierlab
#    - Database created: carriergo_tenant_1
#    - Tables created (30+)
#    - Admin user created with their credentials
#    - Email verified automatically

# 5. Redirected to login

# 6. Login with email/password

# 7. Access at: abc-logistics.carriergo.com
```

---

## Flow 2: Admin-Created Tenant (Current Situation)

### What You Did:

```
1. Logged in as Admin
2. Visited: /admin/tenants
3. Clicked: "+ New Tenant"
4. Filled form:
   ├─ Tenant Name: "ABC Logistics"
   ├─ Domain: "abc-logistics.carriergo.local"
   ├─ Plan: "professional"
   └─ Status: "active"
5. Clicked: "Save Tenant"
   └─ Result: Tenant created (ID=1)

6. Ran command:
   php artisan tenant:create-db 1
   └─ Result: Database created (carriergo_tenant_1)
```

### The Problem:

❌ **No user was created for the tenant!**

The tenant database exists, but ABC Logistics has no employees/users to login with.

### The Solution:

You need to create a user for ABC Logistics. Here are **3 options**:

---

## Option 1: Manual User Creation via Artisan (For Testing)

### Step 1: Create User in Tenant Database

```bash
php artisan tinker
```

Then in the tinker shell:

```php
# Configure connection to ABC Logistics database
>>> config(['database.connections.tenant.database' => 'carriergo_tenant_1']);

# Create user in that database
>>> DB::connection('tenant')->table('users')->insert([
    'firstname' => 'John',
    'lastname' => 'Doe',
    'email' => 'john@abclogistics.com',
    'password' => bcrypt('password123'),
    'phone' => '+1-555-0100',
    'email_verified_at' => now(),
    'image' => '',
    'start_date' => now()->toDateString(),
    'created_at' => now(),
    'updated_at' => now(),
]);

# Verify user was created
>>> DB::connection('tenant')->table('users')->count();
# Output: 1

>>> exit
```

### Step 2: Assign Role to User

```bash
php artisan tinker
```

```php
# Use tenant database
>>> config(['database.connections.tenant.database' => 'carriergo_tenant_1']);
>>> DB::setDefaultConnection('tenant');

# Get the user
>>> $user = App\Models\User::where('email', 'john@abclogistics.com')->first();

# Assign role
>>> $user->assignRole('Super Admin');

# Verify
>>> $user->roles;

>>> exit
```

### Step 3: ABC Logistics Can Now Login

```
Domain: http://abc-logistics.carriergo.local/
Email: john@abclogistics.com
Password: password123
```

---

## Option 2: Create an Admin User Management Interface for Tenants

This is what we should build next. The admin can create users for tenants.

**What we need:**
```
/admin/tenants/{id}/users

Features:
- View users for a tenant
- Create new user
- Edit user
- Delete user
- Assign roles
```

This way:

```
1. Admin creates tenant: ABC Logistics
2. Admin clicks: "Manage Users" button
3. Admin creates user: john@abclogistics.com
4. System sends: Setup link or credentials to tenant
5. Tenant can login immediately
```

---

## Option 3: Tenant Self-Onboarding Link

Create a setup/onboarding link that the tenant can use to create their first admin user.

**What would happen:**

```
1. Admin creates tenant: ABC Logistics
2. System generates unique setup link
3. Admin sends link to: contact@abclogistics.com
4. Tenant visits link (valid for 7 days)
5. Tenant creates their admin account
6. Tenant can access their domain
```

---

## Current Architecture

### Database Structure for ABC Logistics:

```
carrierlab (Central)                carriergo_tenant_1 (ABC's Database)
├── tenants                          ├── users
│   └── ID=1                         │   └── (EMPTY - no users yet!)
│       Name: ABC Logistics          │
│       Domain: abc-logistics        ├── shipments
│       Plan: professional           │   └── (EMPTY)
│       Status: active               │
│                                    ├── invoices
                                     │   └── (EMPTY)
                                     │
                                     └── ... 30+ tables
```

### What Needs to Happen:

```
Create Users in carriergo_tenant_1:

User 1:
├─ Email: john@abclogistics.com
├─ Role: Super Admin
└─ Password: (set by admin or tenant)

User 2:
├─ Email: employee@abclogistics.com
├─ Role: Employee
└─ Can create shipments, invoices, etc.
```

---

## How ABC Logistics Uses The System

### Step 1: Access Their Domain

```
Visit: http://abc-logistics.carriergo.local/

TenantMiddleware does:
1. Reads subdomain: "abc-logistics"
2. Queries carrierlab.tenants
3. Finds: ID=1
4. Gets database: carriergo_tenant_1
5. Switches connection
6. Loads ABC's data only
```

### Step 2: Login

```
Email: john@abclogistics.com
Password: password123
```

### Step 3: Dashboard

```
Shows:
├─ Shipments (ABC's shipments only)
├─ Invoices (ABC's invoices only)
├─ Orders (ABC's orders only)
└─ Company data (ABC's data only)

Can manage:
├─ Create shipments
├─ Create invoices
├─ Manage employees
├─ View reports
└─ Generate documents
```

---

## Complete Onboarding Example: ABC Logistics

### Week 1: Setup

```
Monday:
  1. Admin creates tenant in /admin/tenants
     └─ Name: ABC Logistics
     └─ Domain: abc-logistics.carriergo.local

  2. Admin runs: php artisan tenant:create-db 1

  3. Admin creates first admin user (Option 1 above)
     └─ Email: john@abclogistics.com
     └─ Role: Super Admin

Tuesday:
  1. Admin sends credentials to ABC:
     └─ Domain: abc-logistics.carriergo.local
     └─ Email: john@abclogistics.com
     └─ Temp Password: Password123!

  2. ABC logs in and:
     └─ Changes password
     └─ Updates profile
     └─ Invites team members

  3. ABC starts creating shipments
```

### Week 2-4: Active Usage

```
ABC Logistics uses system to:
├─ Track shipments
├─ Generate invoices
├─ Manage customers
├─ Create documents
├─ View analytics
└─ Manage team
```

---

## Quick Command Reference

### Create tenant database:
```bash
php artisan tenant:create-db {tenant_id}
```

### Create user manually:
```bash
php artisan tinker
>>> config(['database.connections.tenant.database' => 'carriergo_tenant_{id}']);
>>> DB::connection('tenant')->table('users')->insert([...]);
>>> exit
```

### Login to tenant database:
```bash
php artisan tinker
>>> DB::connection('tenant')->table('users')->count();
```

### Test tenant isolation:
```bash
php artisan tinker
>>> config(['database.connections.tenant.database' => 'carriergo_tenant_1']);
>>> DB::connection('tenant')->table('users')->count(); // Tenant 1's users
>>> config(['database.connections.tenant.database' => 'carriergo_tenant_2']);
>>> DB::connection('tenant')->table('users')->count(); // Tenant 2's users
```

---

## Recommended Next Steps

### Priority 1: Build Tenant User Management Interface

Create: `/admin/tenants/{id}/users`

This would let admins:
- View tenant's employees
- Create new employees
- Edit employees
- Delete employees
- Assign roles

### Priority 2: Onboarding Email

When tenant is created, send email with:
- Setup link
- Domain
- First admin credentials
- Quick start guide

### Priority 3: Tenant Self-Service User Invites

Allow tenant admins to:
- Invite other employees
- Send invitation emails
- Manage team members
- Assign permissions

---

## Summary

| Step | What Happens | Result |
|------|---|---|
| 1 | Admin creates tenant | Tenant record in carrierlab |
| 2 | Admin runs `tenant:create-db` | Database created with tables |
| 3 | Admin creates first user | User can login |
| 4 | Tenant logins | Redirected to their domain |
| 5 | Tenant uses system | Can create shipments, invoices, etc. |

**Current Status**: Steps 1-2 are automated. Step 3 requires manual work.
**Next Goal**: Automate step 3 with UI for admins to create tenant users.

---

## For Your ABC Logistics Example

**Right now:**
```bash
1. Created tenant: ABC Logistics ✅
2. Created database: carriergo_tenant_1 ✅
3. NO users exist yet ❌
```

**To get them online:**
```bash
1. Create user with Option 1 (Artisan tinker)
2. Send them login credentials
3. They can login and use system!
```

**To improve process:**
```bash
Build admin UI for managing tenant users
└─ Then admins can create users with a few clicks
└─ Much faster and easier than manual tinker commands
```

---

**Status**: Multi-tenant setup complete, onboarding process needs UI improvement
**Priority**: Build tenant user management interface
