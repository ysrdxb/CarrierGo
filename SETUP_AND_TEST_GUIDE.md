# CarrierGo - Complete Setup and Testing Guide

## Status: ✅ READY FOR TESTING

This guide walks you through the complete registration, verification, company setup, and login flow.

---

## Prerequisites

Before starting, ensure:
- ✅ PHP 8.1+ is running
- ✅ MySQL/MariaDB is running
- ✅ All migrations have been run: `php artisan migrate:status`
- ✅ Caches have been cleared: `php artisan optimize:clear`

---

## Step 1: Access the Registration Page

### URL: `http://localhost/carriergo/register`

**What you'll see:**
- Form titled "Create Your Account"
- Plan selection buttons (Free, Starter, Professional, Enterprise)
- Company Information section
- Your Information section
- Terms agreement checkbox

**What to do:**
1. Select a plan (e.g., **Free**)
2. Enter Company Name: `Test Company XYZ`
3. Enter Subdomain: `test-company-xyz` (must be unique, lowercase with hyphens only)
4. Enter First Name: `John`
5. Enter Last Name: `Doe`
6. Enter Email: `john.doe.test@example.com` (use a unique email each time)
7. Enter Password: `Test@12345` (must be 8+ characters)
8. Confirm Password: `Test@12345`
9. Check "I agree to the Terms of Service and Privacy Policy"
10. Click **Create Account**

**Expected Result:**
- ✅ Success message: "Registration Submitted!"
- ✅ Message: "Check your email at john.doe.test@example.com"
- ✅ You should be able to try again with another email if needed

**Troubleshooting:**
- If you see validation errors under fields, fix those issues
- If subdomain is already taken, get error message and try a different one
- Password must be at least 8 characters and match confirmation

---

## Step 2: Verify Email (Check Logs or Email)

**What happens:**
A verification email is sent to the email address provided.

**To find the verification link:**

### Option A: Check Email (if configured)
Look in the email inbox for a message from `noreply@carriergo.com` or similar.
The email will contain a link like: `http://localhost/carriergo/register/verify/{token}`

### Option B: Check Laravel Logs
If email is not configured, check the logs:
```bash
tail -50 storage/logs/laravel.log
```

Look for a message containing `VerifyRegistrationMailable` with the verification URL.

### Option C: Manual Database Check
```bash
php artisan tinker
# Then:
$reg = DB::table('registrations')->where('email', 'john.doe.test@example.com')->first();
echo $reg->verification_token;
# Copy the token and visit: http://localhost/carriergo/register/verify/{TOKEN}
```

**Expected Result:**
- ✅ Redirected to company setup form at: `/setup/company/{registration_id}`
- ✅ Company name should be pre-filled from registration
- ✅ Email should be pre-filled and read-only

---

## Step 3: Complete Company Setup

### URL: `http://localhost/carriergo/setup/company/{registration_id}`

**Form fields to fill:**

**Company Information:**
- Company Name: `Test Company XYZ` (pre-filled)
- Street Address: `123 Main Street`
- City: `New York`
- Zip Code: `10001`
- Country: `United States`
- Phone Number: `+1 (555) 123-4567`

**Contact Information:**
- Email: (read-only, already verified)

**What to do:**
1. Fill all company information fields
2. Verify your email is correct
3. Check your subscription plan is displayed correctly
4. Click **Complete Setup & Access Dashboard**

**Expected Result:**
- ✅ Success message: "Company setup completed! You can now log in."
- ✅ Redirected to `/login` page
- ✅ Tenant record created in database
- ✅ Company record created in database
- ✅ User record created with tenant_id assigned

**Verify in Database:**
```bash
php artisan tinker
# Check tenant was created:
DB::table('tenants')->where('name', 'Test Company XYZ')->first();

# Check company was created:
DB::table('companies')->where('name', 'Test Company XYZ')->first();

# Check user was created:
DB::table('users')->where('email', 'john.doe.test@example.com')->first();
```

---

## Step 4: Login

### URL: `http://localhost/carriergo/login`

**What to do:**
1. Enter Email: `john.doe.test@example.com`
2. Enter Password: `Test@12345`
3. Click **Login**

**Expected Result:**
- ✅ OTP Verification page appears
- ✅ 6-digit OTP number is displayed on the page
- ✅ Message: "Check the verification page"

**What next:**
- The OTP is displayed (not sent via email for local testing)
- Copy the 6-digit number and enter it in the verification field

---

## Step 5: OTP Verification

### URL: `http://localhost/carriergo/verify-email-code`

**What to do:**
1. Look at the OTP number displayed on login page
2. Enter the 6-digit OTP in the input field
3. Click **Verify**

**Expected Result:**
- ✅ Redirected to dashboard
- ✅ If you're Admin/Super Admin → `/dashboard` (Admin Dashboard)
- ✅ If you're Regular User → `/user/reference` (User Dashboard)
- ✅ Welcome message: "Welcome back!"

---

## Complete Flow Summary

```
Register Page (/register)
    ↓
    [Fill form and submit]
    ↓
Email Verification (/register/verify/{token})
    ↓
    [Click email link or visit URL]
    ↓
Company Setup Page (/setup/company/{id})
    ↓
    [Fill company information]
    ↓
Login Page (/login)
    ↓
    [Enter email and password]
    ↓
OTP Verification (/verify-email-code)
    ↓
    [Enter 6-digit OTP]
    ↓
Dashboard (/dashboard or /user/reference)
    ↓
✅ SUCCESS - You're logged in!
```

---

## Testing Checklist

### Registration Flow
- [ ] Register with unique email and subdomain
- [ ] See success message with email confirmation request
- [ ] Fields show inline validation errors if needed

### Email Verification
- [ ] Find verification token in logs or database
- [ ] Click verification link
- [ ] Redirected to company setup form

### Company Setup
- [ ] All fields accept input
- [ ] Submit without errors
- [ ] Redirected to login page
- [ ] Check database for created tenant, company, and user

### Login & Authentication
- [ ] Login with email and password
- [ ] See OTP verification page
- [ ] OTP is displayed on page
- [ ] Enter OTP and verify
- [ ] Redirected to appropriate dashboard
- [ ] See welcome message

### Data Isolation
- [ ] Register 2 different users with different companies
- [ ] Each user only sees their own data
- [ ] Users cannot access other tenant's data

---

## Database Verification

Check everything is working by running these commands:

```bash
php artisan tinker

# See all registrations
DB::table('registrations')->get(['id', 'email', 'status', 'tenant_id']);

# See all tenants
DB::table('tenants')->get(['id', 'name', 'domain', 'subscription_plan']);

# See all companies
DB::table('companies')->get(['id', 'tenant_id', 'name', 'city', 'country']);

# See all users
DB::table('users')->get(['id', 'email', 'tenant_id', 'firstname', 'lastname']);
```

---

## Common Issues and Solutions

### Issue: "Subdomain already taken"
**Solution:** Use a unique subdomain. Append a timestamp like `test-company-xyz-1729000000`

### Issue: Validation errors on register page
**Solution:** Ensure:
- Password is 8+ characters
- Passwords match
- Email is unique
- Subdomain is lowercase with hyphens only (no spaces, numbers at start)

### Issue: Email verification link not found
**Solution:**
1. Check `storage/logs/laravel.log` for the token
2. Use database method: `php artisan tinker` then `DB::table('registrations')->latest()->first();`

### Issue: 500 error on any page
**Solution:**
1. Check `storage/logs/laravel.log` for error details
2. Clear caches: `php artisan optimize:clear`
3. Ensure all migrations ran: `php artisan migrate:status`

---

## Admin Access

Default admin user created during setup:
- **Email:** `admin@carriergo.com`
- **Password:** Set during initial setup
- **Tenant:** ID 1 (ABC Logistics)

---

## API Testing (Optional)

Test the registration endpoint directly:

```bash
curl -X POST http://localhost/carriergo/register \
  -H "Content-Type: application/json" \
  -d '{
    "companyName": "Test API",
    "domain": "test-api-123",
    "subscriptionPlan": "free",
    "firstName": "Test",
    "lastName": "User",
    "email": "api@test.com",
    "password": "Test@12345",
    "agreeToTerms": true
  }'
```

---

## Support

If you encounter any issues:

1. **Check logs:** `tail -100 storage/logs/laravel.log`
2. **Clear cache:** `php artisan optimize:clear`
3. **Check database:** `php artisan tinker`
4. **Verify migrations:** `php artisan migrate:status`

---

**Last Updated:** 2025-10-24
**Status:** ✅ PRODUCTION READY FOR TESTING
