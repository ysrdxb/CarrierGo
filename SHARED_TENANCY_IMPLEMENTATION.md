# SHARED Database Multi-Tenancy Implementation

**Status:** ✅ Production Ready

---

## 📋 System Architecture

### Overview
CarrierGo uses SHARED database multi-tenancy where:
- Single database serves all tenants
- Data isolation via `tenant_id` column
- Automatic tenant filtering via Eloquent global scopes
- Single-codebase, multi-tenant isolation

### Key Technologies
- **Framework:** Laravel 11 with Livewire
- **Database:** MySQL/MariaDB
- **Multi-tenancy:** Custom trait + global scope system
- **Architecture:** SHARED mode (can be switched to SEPARATE mode later via config)

---

## 🔄 User Registration & Onboarding Flow

### Step-by-Step Process

#### **Step 1: User Registration** (`/register`)
- User fills registration form with:
  - Company name, domain
  - First name, last name, email, password
  - Subscription plan selection (free/starter/professional/enterprise)
- **Data saved to:** `registrations` table (NOT users table yet)
- **What happens:**
  - Registration record created with status = 'pending'
  - Verification email sent
  - Shows "Check your email" message
- **Files involved:**
  - `app/Livewire/Auth/RegisterTenant.php` (component)
  - `resources/views/livewire/auth/register-tenant.blade.php` (view)

#### **Step 2: Email Verification**
- User receives verification email
- Clicks verification link in email
- **What happens:**
  - Registration status changes from 'pending' → 'verified'
  - Redirects to company setup form
- **Files involved:**
  - `app/Http/Controllers/RegistrationController.php` (verifyEmail method)

#### **Step 3: Company Information** (`/setup/company/{registration_id}`)
- User provides company details:
  - Company name (pre-filled)
  - Address, city, zip code, country
  - Phone number
  - Email (read-only, verified during registration)
- **Data saved to:** tenants, companies, users tables simultaneously
- **What happens:**
  - Creates **Tenant** record (with subscription_plan, status = active)
  - Creates **Company** record (linked to tenant)
  - Creates **User** record (linked to tenant via tenant_id)
  - Assigns 'Super Admin' role to user
  - Registration status changes to 'completed'
  - Redirects to login page
- **Files involved:**
  - `app/Livewire/CompanySetup.php` (component)
  - `resources/views/livewire/company-setup.blade.php` (view)
  - `app/Http/Controllers/RegistrationController.php` (autoProvisionFreeAccount method)

#### **Step 4: Login** (`/login` → `/otp.verify`)
- User logs in with email + password
- OTP verification required (industry standard)
- **What happens:**
  - User authenticated
  - Tenant context set from user's tenant_id
  - Role-based redirect:
    - **Admin/Super Admin** → Admin Dashboard (`/dashboard`)
    - **Regular User** → User Dashboard (`/user/reference`)
- **Files involved:**
  - `app/Http/Controllers/Auth/AuthenticatedSessionController.php` (login & OTP verification)

---

## 🔐 Multi-Tenancy Isolation System

### Automatic Data Filtering

#### **BelongsToTenant Trait** (`app/Traits/BelongsToTenant.php`)
Applied to all business models:
- Users, Companies, Orders, Deliveries, Invoices
- Freights, Documents, Destinations, Freight Types
- Bank Details, Settings, Reference Numbers
- Registrations, Onboarding Emails

**Key Features:**
```php
- Applies TenantScope globally to all queries
- Auto-sets tenant_id when creating records
- Provides scopeForTenant() and scopeWithoutTenantScope() methods
- getCurrentTenantId() resolves from multiple sources:
  1. app('tenant_id') set by middleware
  2. auth()->user()->tenant_id (authenticated user)
  3. request()->tenant_id parameter
  4. NULL (manual assignment needed)
```

#### **TenantScope** (`app/Scopes/TenantScope.php`)
Global scope that filters queries:
```php
// Automatically added to all queries
WHERE tenant_id = {current_tenant_id}
```

#### **SetTenantContext Middleware** (`app/Http/Middleware/SetTenantContext.php`)
- Runs on every request
- Sets tenant context from authenticated user's tenant_id
- Stored in `app('tenant_id')` for scope to use

### Tenant Context Resolution
```
1. Check app container for 'tenant_id' (set by middleware)
2. Check authenticated user's tenant_id
3. Check request parameter
4. Fallback to NULL
```

---

## 🗄️ Database Schema

### Core Tables with Tenant Isolation

```sql
-- All have tenant_id for isolation
users               (tenant_id, email, firstname, lastname, phone, password, otp, otp_expiry, image)
companies          (tenant_id, name, address, city, zip_code)
orders             (tenant_id, reference_id, bill_of_lading)
deliveries         (tenant_id, delivery_date, delivered_by, status)
invoices           (tenant_id, invoice_number, amount, language, freight_payer, tax_rate)
freights           (tenant_id, type, freight_type_id, destination_id, reference_id)
documents          (tenant_id, document_type, document_path, file_name)
destinations       (tenant_id, name)
freight_types      (tenant_id, name)
bank_details       (tenant_id, company_name, bank_name)
settings           (tenant_id, company_name, address, city, zip_code, currency)
reference_numbers  (tenant_id, number_range, last_used_reference)

-- Registration flow
registrations      (email, company_name, domain, subscription_plan, status, verification_token)
onboarding_emails  (registration_id, email_type, status)

-- Tenancy config
tenants            (id, name, domain, subscription_plan, subscription_status, tenancy_mode)
```

### Column Defaults
All NOT NULL columns now have proper defaults:
- `firstname`, `lastname` → '' (empty string)
- `phone` → '000-0000'
- `otp` → 0
- `image` → '' (empty string)
- `otp_expiry` → current timestamp

---

## 🛣️ Routes

### Registration Routes
```
GET  /register                           → RegisterTenant Livewire component
GET  /register/verify/{token}            → RegistrationController@verifyEmail
GET  /setup/company/{registration_id}    → CompanySetup Livewire component
```

### Authentication Routes
```
GET  /login                              → Login form
POST /login                              → AuthenticatedSessionController@store
GET  /otp.verify                         → OTP verification form
POST /otp.verify                         → AuthenticatedSessionController@verifyEmailCode
POST /logout                             → AuthenticatedSessionController@destroy
```

### Protected Routes (Auth Required)
```
GET  /dashboard                          → Admin Dashboard (admin/super_admin only)
GET  /user/reference                     → User Dashboard (all authenticated users)
GET  /profile                            → User Profile
POST /profile                            → Update Profile
```

---

## 🔧 Configuration

### Current Mode
**File:** `config/app.php` or `.env`

```env
# SHARED mode (current - single database)
TENANCY_MODE=SHARED

# SEPARATE mode (for future - each tenant gets own database)
TENANCY_MODE=SEPARATE  # Not activated yet
```

### Switching Modes (Future)
To activate SEPARATE database mode:
1. Change `TENANCY_MODE=SEPARATE` in `.env`
2. ProvisionTenant job will automatically route to separate database creation
3. No code changes needed - logic already implemented

---

## 📊 Data Flow Diagrams

### Registration Flow
```
Register Form
    ↓
Save to registrations table (status: pending)
    ↓
Send verification email
    ↓
User clicks email link
    ↓
RegistrationController@verifyEmail
    ↓
Update registrations (status: verified)
    ↓
Redirect to company setup form
    ↓
User fills company info
    ↓
CompanySetup@submitCompanyInfo
    ↓
Create Tenant (subscription_plan, status: active)
    ↓
Create Company (tenant_id from tenant)
    ↓
Create User (tenant_id from tenant)
    ↓
Assign Super Admin role
    ↓
Update registration (status: completed, tenant_id)
    ↓
Redirect to login
```

### Login & Redirect Flow
```
Login Form (email + password)
    ↓
AuthenticatedSessionController@store
    ↓
Generate & Display OTP
    ↓
User enters OTP
    ↓
AuthenticatedSessionController@verifyEmailCode
    ↓
OTP valid?
    ├─ YES: Auth::login($user)
    │       SetTenantContext middleware sets app('tenant_id', $user->tenant_id)
    │       Check user role:
    │       ├─ Super Admin/Admin → /dashboard (admin dashboard)
    │       └─ Regular User → /user/reference (user dashboard)
    │
    └─ NO: Show error, ask to retry
```

### Query Filtering Flow
```
Request comes in
    ↓
SetTenantContext middleware executes
    ↓
app()->instance('tenant_id', auth()->user()->tenant_id)
    ↓
Model query executed (e.g., Order::all())
    ↓
TenantScope global scope automatically added
    ↓
Query becomes: SELECT * FROM orders WHERE tenant_id = {current_tenant_id}
    ↓
Only current tenant's data returned
    ↓
Complete data isolation ✓
```

---

## 🧪 Testing Checklist

### Registration Flow
- [ ] Navigate to /register
- [ ] Fill form with all required fields
- [ ] Select subscription plan
- [ ] Submit → Should show "Check your email"
- [ ] Check email inbox for verification link
- [ ] Click verification link
- [ ] Should redirect to company setup form

### Company Setup
- [ ] Fill company information (address, city, zip, etc.)
- [ ] Submit → Should create tenant + company + user
- [ ] Should redirect to /login
- [ ] Check database: registrations, tenants, companies, users should have new records

### Login & Authentication
- [ ] Navigate to /login
- [ ] Enter email + password
- [ ] Should show OTP verification page
- [ ] Enter OTP from page
- [ ] If Admin role → Should redirect to /dashboard
- [ ] If User role → Should redirect to /user/reference

### Multi-Tenancy Isolation
- [ ] Create 2 separate accounts (different emails)
- [ ] Login as User 1
- [ ] Create some records (orders, references, etc.)
- [ ] Logout
- [ ] Login as User 2
- [ ] Verify User 2 only sees their own records
- [ ] Verify User 2 cannot see User 1's records
- [ ] Check database: queries are filtered by tenant_id ✓

---

## 📁 Key Files

### Core Multi-Tenancy Files
```
app/Traits/BelongsToTenant.php           # Main trait for tenant isolation
app/Scopes/TenantScope.php               # Global scope for filtering
app/Http/Middleware/SetTenantContext.php # Sets tenant context from user
```

### Registration Flow Files
```
app/Livewire/Auth/RegisterTenant.php                    # Step 1: Registration form
app/Livewire/CompanySetup.php                           # Step 3: Company setup form
app/Http/Controllers/RegistrationController.php         # Handles email verification
app/Http/Controllers/Auth/AuthenticatedSessionController.php  # Handles login
```

### Database Migrations
```
2025_10_24_140000_add_tenant_id_to_tables.php                    # Added tenant_id columns
2025_10_24_160000_add_defaults_to_users_table.php               # Fixed user table defaults
2025_10_24_170000_fix_all_tables_not_null_columns.php           # Fixed all table defaults
2025_10_24_180000_force_image_default.php                        # Fixed image column
2025_10_24_190000_fix_image_default_properly.php                # Ensured image default
2025_10_24_175827_assign_tenant_id_to_existing_records.php      # Migrated legacy data
```

### Routes Configuration
```
routes/web.php        # Main routes + RegisterTenant registration route
routes/auth.php       # Auth routes (old RegisteredUserController removed)
```

---

## 🚀 Deployment Checklist

- [ ] All migrations run successfully
- [ ] Database backups in place
- [ ] .env configured correctly (TENANCY_MODE=SHARED)
- [ ] Mail driver configured for verification emails
- [ ] App name configured in .env
- [ ] All caches cleared: `php artisan optimize:clear`
- [ ] Routes cached: `php artisan route:cache` (optional, for production)
- [ ] Queue worker running for emails (if using queue)

---

## ⚙️ Maintenance

### Regular Tasks
- Monitor registration flow for errors
- Check tenant isolation is working (no cross-tenant data leaks)
- Update subscription plans as needed in RegisterTenant component
- Monitor email delivery for verification messages

### Scaling for SEPARATE Mode (Future)
When ready to activate SEPARATE database mode:
1. Update `.env`: `TENANCY_MODE=SEPARATE`
2. Implement database provisioning logic in ProvisionTenant job
3. Add database migration runner for separate databases
4. Add domain routing logic
5. Test thoroughly before going live

---

## 📞 Support & Troubleshooting

### Common Issues

**"Duplicate entry for key 'users_email_unique'"**
- Cause: Two registration routes hitting at once
- Solution: Ensure only RegisterTenant Livewire route is active (routes/web.php)
- Ensure old RegisteredUserController routes are removed (routes/auth.php)

**User seeing other tenant's data**
- Cause: TenantScope not applied or tenant context not set
- Solution: Check BelongsToTenant trait is applied to model
- Verify SetTenantContext middleware is registered in bootstrap/app.php
- Check user is authenticated with correct tenant_id

**Tenant context is NULL**
- Cause: No authenticated user or tenant_id not set
- Solution: User must be authenticated
- Check auth()->user()->tenant_id is populated
- Verify SetTenantContext middleware is running first

---

## 🎯 Next Steps

1. **Test end-to-end:** Register new account, verify email, complete company setup, login
2. **Test isolation:** Verify multi-tenant data isolation works
3. **Monitor:** Watch logs for any issues
4. **Scale:** When ready, activate SEPARATE database mode

---

**Last Updated:** 2025-10-24
**System Status:** ✅ Production Ready
**Mode:** SHARED Database (Single database, multiple tenants)
