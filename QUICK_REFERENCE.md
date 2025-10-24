# CarrierGo SHARED Tenancy - Quick Reference

## 🚀 System Ready for Testing

✅ **Registration Flow:** Complete
✅ **Multi-Tenancy Isolation:** Active
✅ **Database Defaults:** Configured
✅ **Role-Based Redirect:** Implemented
✅ **All Routes Configured:** Ready

---

## 🔗 Important URLs

| Purpose | URL | Notes |
|---------|-----|-------|
| Register | `/register` | Uses RegisterTenant Livewire |
| Email Verify | `/register/verify/{token}` | Auto-filled in email |
| Company Setup | `/setup/company/{registration_id}` | After email verification |
| Login | `/login` | Email + Password required |
| OTP Verify | `/otp.verify` | 6-digit OTP shown on page |
| Admin Dashboard | `/dashboard` | For Super Admin / Admin users |
| User Dashboard | `/user/reference` | For regular users |
| Shipment Track | `/` or `/track-shipment` | Public, no auth required |

---

## 👤 Test Accounts

### For Testing Registration
1. Go to `/register`
2. Fill with test data:
   - Company: "Test Company"
   - Domain: "test-company-xyz" (must be unique)
   - Plan: "free" (recommended for testing)
   - Email: test123@example.com (use different each time)
   - Password: Test@123456

3. Check email (or logs) for verification link
4. Click link → company setup form
5. Fill company details
6. Submit → creates tenant + user
7. Go to `/login` with same email
8. Enter OTP shown on verification page
9. If Super Admin → `/dashboard`
10. If Regular User → `/user/reference`

---

## 📊 Data Flow in 4 Steps

```
STEP 1: Register
  → Save to registrations table
  → Send verification email

STEP 2: Verify Email
  → Click email link
  → Redirect to company setup

STEP 3: Company Setup
  → Create Tenant
  → Create Company
  → Create User (with tenant_id)
  → Assign Super Admin role

STEP 4: Login → Role-based Redirect
  → Admin → /dashboard
  → User → /user/reference
```

---

## 🔐 Multi-Tenancy How It Works

```
User A (tenant_id=1)      User B (tenant_id=2)
         |                        |
    Logs in              Logs in
         |                        |
  SetTenantContext        SetTenantContext
  sets tenant_id=1        sets tenant_id=2
         |                        |
   Runs Query             Runs Query
  SELECT * FROM orders    SELECT * FROM orders
         |                        |
   TenantScope adds:      TenantScope adds:
   WHERE tenant_id=1      WHERE tenant_id=2
         |                        |
  Sees only User A's data  Sees only User B's data
         |                        |
      ISOLATED                ISOLATED
```

---

## 🧪 Quick Test Commands

```bash
# Clear all caches
php artisan optimize:clear

# Check routes
php artisan route:list | grep register

# Check migrations
php artisan migrate:status

# Test database
php artisan tinker
# Then in tinker:
\App\Models\User::count()
\App\Models\Tenant::count()
\App\Models\Registration::count()
```

---

## 📋 What's NOT in Testing Yet

These features can be added later:
- [ ] Payment processing (Stripe/PayPal)
- [ ] Email queue workers
- [ ] SMS verification
- [ ] Admin approval for paid plans
- [ ] SEPARATE database mode activation
- [ ] Subscription management dashboard
- [ ] Plan upgrades/downgrades

---

## 🛠️ If Something Breaks

### Issue: 500 Error on Registration
**Check:**
1. Logs: `tail -100 storage/logs/laravel.log`
2. Clear caches: `php artisan optimize:clear`
3. Check routes: `php artisan route:list | grep register`

### Issue: Duplicate Email Error
**Cause:** Old RegisteredUserController route still active
**Fix:**
1. Verify `/register` route in `routes/auth.php` is removed
2. Only `/register` in `routes/web.php` should exist
3. Clear caches: `php artisan optimize:clear`

### Issue: User Can See Other Tenant's Data
**Cause:** TenantScope not working
**Check:**
1. Is BelongsToTenant trait applied to model?
2. Is SetTenantContext middleware in bootstrap/app.php?
3. Is user authenticated with tenant_id?
4. Clear caches: `php artisan optimize:clear`

### Issue: Tenant Context is NULL
**Check:**
1. Is user logged in? `auth()->check()`
2. Does user have tenant_id? `auth()->user()->tenant_id`
3. Is SetTenantContext middleware running first?
4. Check logs for errors

---

## 📁 Key Files You'll Need to Know

### If you need to modify registration:
- `app/Livewire/Auth/RegisterTenant.php` - Registration form component
- `app/Livewire/CompanySetup.php` - Company setup form

### If you need to modify login:
- `app/Http/Controllers/Auth/AuthenticatedSessionController.php` - Login & OTP logic

### If you need to modify tenancy:
- `app/Traits/BelongsToTenant.php` - Main isolation trait
- `app/Scopes/TenantScope.php` - Query filtering scope
- `app/Http/Middleware/SetTenantContext.php` - Tenant context setter

### If you need to add fields:
1. Create migration to add column to relevant table(s)
2. Add column to $fillable in model
3. If optional: add DEFAULT clause to migration
4. Run: `php artisan migrate`
5. Clear caches: `php artisan optimize:clear`

---

## ✅ Final Checklist Before Going Live

- [ ] Test complete registration flow (register → email verify → company setup → login)
- [ ] Test multi-tenancy isolation (User A can't see User B's data)
- [ ] Test both admin and regular user dashboards
- [ ] Test logout
- [ ] Check email sending works
- [ ] Verify OTP verification works
- [ ] Test with different subscription plans
- [ ] Monitor error logs for 24 hours
- [ ] Check database for proper tenant_id assignment
- [ ] Verify no duplicate emails in users table

---

**Status:** ✅ READY FOR TESTING
**Created:** 2025-10-24
**Mode:** SHARED Database Multi-Tenancy
