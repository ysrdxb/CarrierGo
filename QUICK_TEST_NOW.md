# Quick Test - Complete Flow (5 Minutes)

## CRITICAL BUG FIXED ✅

**Issue:** Registration status was being changed incorrectly, preventing access to company setup.
**Fixed:** Registration now maintains "verified" status through company setup.

---

## Test This Right Now

### **Step 1: Register** (30 seconds)
```
URL: http://localhost/carriergo/register
```

Fill the form with:
- Plan: **Free**
- Company Name: `MyCompany2025`
- Subdomain: `mycompany2025`
- First Name: `John`
- Last Name: `Doe`
- Email: `john@test2025.com` ← **USE UNIQUE EMAIL**
- Password: `Test@12345`
- Confirm: `Test@12345`
- Check: "I agree..."
- Click: **Create Account**

**Expected:** Success message "Check your email"

---

### **Step 2: Get Verification Link** (1 minute)
Run this command:
```bash
php artisan tinker
DB::table('registrations')->latest()->first();
# Copy the value of: verification_token
exit
```

Or check logs:
```bash
tail -20 storage/logs/laravel.log
```

Look for: `VerifyRegistrationMailable` and find the URL or token.

---

### **Step 3: Verify Email** (30 seconds)
Visit the link:
```
http://localhost/carriergo/register/verify/{TOKEN}
```

Replace `{TOKEN}` with the token from Step 2.

**Expected:** Redirected to company setup form with success message

---

### **Step 4: Complete Company Setup** (1 minute)
Fill the form:
- Company Name: `MyCompany2025` (pre-filled)
- Address: `123 Main Street`
- City: `New York`
- Zip: `10001`
- Country: `USA`
- Phone: `+1-555-0123`

Click: **Complete Setup & Access Dashboard**

**Expected:** Redirected to login page with success message

---

### **Step 5: Login & OTP** (1 minute)
At `/login`:
- Email: `john@test2025.com`
- Password: `Test@12345`
- Click: **Login**

See OTP page showing a 6-digit number. Copy it.

At `/verify-email-code`:
- Enter the 6-digit OTP
- Click: **Verify**

**Expected:** Redirected to dashboard, "Welcome back!" message

---

## Verify in Database

```bash
php artisan tinker

# Check registration completed
DB::table('registrations')->where('email', 'john@test2025.com')->first();

# Check tenant created
DB::table('tenants')->latest()->first();

# Check company created
DB::table('companies')->latest()->first();

# Check user created
DB::table('users')->where('email', 'john@test2025.com')->first();

exit
```

**All should have:**
- ✅ User with tenant_id
- ✅ Tenant record
- ✅ Company record
- ✅ Registration with status "completed"

---

## If You Hit Any 500 Errors

1. **Check logs:**
   ```bash
   tail -50 storage/logs/laravel.log
   ```

2. **Clear cache:**
   ```bash
   php artisan optimize:clear
   ```

3. **Verify database:**
   ```bash
   php artisan tinker
   DB::table('users')->count();
   DB::table('tenants')->count();
   exit
   ```

---

## Expected Success Indicators

- ✅ Registration page loads without errors
- ✅ Form accepts all input
- ✅ Success message shows after submit
- ✅ Verification link works
- ✅ Redirects to company setup
- ✅ Company setup form loads
- ✅ Company setup submits successfully
- ✅ Redirects to login
- ✅ Login accepts credentials
- ✅ OTP page shows number
- ✅ OTP verification works
- ✅ Dashboard loads
- ✅ Database has all records

**If ALL of these work, system is ERROR-FREE!** ✅

---

**Run this test and let me know the results!**
