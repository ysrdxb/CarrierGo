# Tenant Registration & Onboarding - Complete Summary

## Your Question Answered

**"How should ABC Logistics (or any tenant) get set up after the admin creates them? Where does the data go before the tenant database exists?"**

### The Solution: Universal Data Persistence Pattern

**Use the central `carrierlab` database as a temporary holding area**

```
Step 1: Admin/Customer provides registration data
    ↓
Step 2: Save to carrierlab.registrations table
    ↓
Step 3: Verify email / Process payment / Admin approval
    ↓
Step 4: Create tenant database (carriergo_tenant_X)
    ↓
Step 5: Migrate data from registrations → new tenant database
    ↓
Step 6: Mark registration as completed
    ↓
Step 7: Send credentials/approval email
    ↓
Step 8: User can login to their domain!
```

---

## Two Complete Flows

### FLOW 1: Admin Creates Tenant (What You Want for ABC Logistics)

**Current State:**
```bash
1. Admin clicks: /admin/tenants → "+ New Tenant"
2. Fills:
   - Tenant Name: ABC Logistics
   - Domain: abc-logistics.carriergo.local
   - Plan: professional
3. Submits → Database created (carriergo_tenant_1)
4. ❌ Problem: No user exists!
```

**Desired State:**
```bash
1. Admin clicks: /admin/tenants → "+ New Tenant"
2. Fills TENANT INFO:
   - Tenant Name: ABC Logistics
   - Domain: abc-logistics.carriergo.local
   - Plan: professional
3. Fills EMPLOYEE/USER INFO:
   - First Name: John
   - Last Name: Doe
   - Email: john@abclogistics.com
   - Password: [auto-generate or manual]
4. Clicks: "Create Tenant & Send Credentials"
5. ✅ System automatically:
   - Creates tenant in carrierlab.tenants
   - Creates database carriergo_tenant_1
   - Creates user John Doe in new database
   - Sends email: john@abclogistics.com
   - John can login immediately!
```

### FLOW 2: Customer Self-Registers (B2C)

**Registration Page: /register**

```bash
Customer fills:
├─ Company Name: "XYZ Transport"
├─ Subdomain: "xyz-transport" (auto-slug, check availability)
├─ First Name: "Jane"
├─ Last Name: "Smith"
├─ Email: "jane@xyztransport.com"
├─ Password: "SecurePass123"
├─ Plan: [Free Trial | Starter $99 | Professional $299 | Enterprise]
└─ Accept Terms

System:
├─ Validates all fields
├─ Saves to carrierlab.registrations (status=pending)
├─ Sends verification email
└─ Shows: "Check your email"

Customer verifies email:
├─ Clicks link in email
│
├─ IF FREE PLAN:
│  ├─ Auto-approves
│  ├─ Creates database carriergo_tenant_2
│  ├─ Creates user Jane Smith
│  ├─ Sends "Ready to login!" email
│  └─ Jane can login!
│
└─ IF PAID PLAN:
   ├─ Shows payment form
   ├─ Customer pays
   ├─ Admin gets notification
   ├─ Admin reviews & approves in /admin/pending-registrations
   ├─ System provisions database
   ├─ System sends approval email
   └─ Jane can login!
```

---

## The Key Insight: Where Does Data Go?

### Before Tenant Database Exists

**Create `registrations` table in `carrierlab` (central database):**

```sql
CREATE TABLE registrations (
    id BIGINT PRIMARY KEY,
    -- Tenant info
    company_name VARCHAR(255),
    domain VARCHAR(255) UNIQUE,
    subscription_plan ENUM('free', 'starter', 'professional', 'enterprise'),

    -- User info
    firstname VARCHAR(100),
    lastname VARCHAR(100),
    email VARCHAR(255) UNIQUE,
    password_hash VARCHAR(255),

    -- Status tracking
    status ENUM('pending', 'approved', 'rejected', 'completed'),
    verification_token VARCHAR(255),
    tenant_id BIGINT (NULL until provisioned),

    -- Timestamps
    created_at TIMESTAMP,
    approved_at TIMESTAMP,
    completed_at TIMESTAMP,
);
```

### How Data Flows

**Admin-Created:**
```
TenantManager form input
    ↓
Validate
    ↓
Create tenant in carrierlab.tenants
    ↓
Dispatch ProvisionTenant job
    ├─ Create database carriergo_tenant_X
    ├─ Create user in new database
    └─ Send email
    ↓
Done! User can login
```

**Self-Registration:**
```
RegisterTenant form input
    ↓
Validate
    ↓
Save to carrierlab.registrations
    ↓
Send verification email
    ↓
Customer verifies email
    ↓
[AUTO (free) or ADMIN APPROVED (paid)]
    ↓
Create tenant in carrierlab.tenants
    ↓
Dispatch ProvisionTenant job
    ├─ Create database
    ├─ Create user
    └─ Send credentials
    ↓
Update registration (status=completed, tenant_id=X)
    ↓
Done! User can login
```

---

## Implementation Phases

### ✅ PHASE 1: Admin-Created Tenant (Do This First)

**What changes in TenantManager:**

1. Add form fields for employee:
   ```
   Tenant Name [          ]
   Domain [               ]
   Plan [dropdown         ]
   ─────────────────────
   FIRST EMPLOYEE
   First Name [           ]
   Last Name [            ]
   Email [                ]
   Auto-gen password? [x]
   ```

2. When submitted, system:
   ```php
   $tenant = Tenant::create([
       'name' => 'ABC Logistics',
       'domain' => 'abc-logistics',
       ...
   ]);

   ProvisionTenant::dispatch(
       $tenant,
       'John',
       'Doe',
       'john@abclogistics.com',
       'GeneratedPassword123'
   );
   ```

3. ProvisionTenant job sends email:
   ```
   Subject: Your ABC Logistics Account is Ready

   Hi John,

   Your ABC Logistics account has been created!

   Login Details:
   Domain: abc-logistics.carriergo.local
   Email: john@abclogistics.com
   Password: GeneratedPassword123

   Click here to login: [link]

   Change your password on first login.
   ```

### PHASE 2: Self-Registration Flow

**New registrations table stores pending signups in carrierlab**

Customer fills form → Saved to registrations table → Email verification → Auto/Admin provision → Done

### PHASE 3: Admin Approval Interface

**New page: /admin/pending-registrations**

Shows list of pending paid tier registrations. Admin can:
- View registration details
- Approve (auto-provisions)
- Reject with reason

---

## Database Changes Required

### New Tables:

```bash
1. carrierlab.registrations
   - For temporary storage of registration data
   - Tracks status: pending → verified → approved → completed

2. carrierlab.onboarding_emails
   - Log of emails sent (welcome, approval, credentials)
   - Prevents duplicate emails

3. Modify tenants table
   - Add: created_by_admin (bool)
   - Add: approval_status (enum)
   - Add: trial_days (int)
   - Add: trial_expires_at (timestamp)
```

### Migrations to Create:

```bash
php artisan make:migration create_registrations_table
php artisan make:migration create_onboarding_emails_table
php artisan make:migration add_approval_to_tenants_table
```

---

## Models & Components to Create

### Models:
```
app/Models/Registration.php
app/Models/OnboardingEmail.php
```

### Livewire Components:
```
app/Livewire/Admin/PendingRegistrations.php (new)
(Update existing TenantManager.php)
(Update existing RegisterTenant.php)
```

### Mailables (Email Templates):
```
app/Mail/TenantCredentialsMailable.php (admin-created)
app/Mail/VerifyRegistrationMailable.php (self-registration)
app/Mail/RegistrationApprovedMailable.php (approval)
app/Mail/RegistrationRejectedMailable.php (rejection)
```

### Email Views:
```
resources/views/emails/tenant-credentials.blade.php
resources/views/emails/verify-registration.blade.php
resources/views/emails/registration-approved.blade.php
resources/views/emails/registration-rejected.blade.php
```

---

## Routes Needed

```php
// Public
Route::get('/register', RegisterTenant::class)->name('tenant.register');
Route::post('/register', [RegisterTenant::class, 'register']);
Route::get('/verify-email/{token}', [RegisterTenant::class, 'verifyEmail']);

// Admin
Route::middleware(['admin'])->group(function () {
    Route::get('/admin/pending-registrations', PendingRegistrations::class)
        ->name('admin.registrations.pending');
    Route::post('/admin/pending-registrations/{id}/approve', ...)
        ->name('admin.registrations.approve');
    Route::post('/admin/pending-registrations/{id}/reject', ...)
        ->name('admin.registrations.reject');
});
```

---

## Why This Solution Works

### Universal (Works for Both Flows)
✅ Admin-created: Minimal data, direct provisioning
✅ Self-registration: Full data, pending provisioning

### Persistent (Data Always Saved)
✅ Registration data in carrierlab.registrations
✅ Never lost, always available for audit/recovery
✅ Tracks complete history

### Scalable (Grows with Business)
✅ Can handle 1000s of registrations
✅ Easy filtering and search
✅ Simple to add approval workflows

### Secure (No Data Leaks)
✅ Data isolated until approved
✅ Verification tokens prevent unauthorized access
✅ Password hashed immediately
✅ Each tenant completely isolated

### Flexible (Extensible)
✅ Easy to add approval workflows
✅ Easy to add payment integration
✅ Easy to add notification preferences
✅ Easy to extend with custom fields

---

## Next Steps for Implementation

### Week 1 Continued: Task 6.5

**Phase 1 (Day 1):** Admin-Created Flow
- [ ] Update TenantManager with employee fields
- [ ] Create TenantCredentialsMailable
- [ ] Test end-to-end

**Phase 2 (Day 2):** Self-Registration Setup
- [ ] Create registrations table migration
- [ ] Create Registration model
- [ ] Update RegisterTenant component

**Phase 3 (Day 3):** Verification & Auto-Provisioning
- [ ] Add email verification flow
- [ ] Auto-provision for free tier
- [ ] Test email sending

**Phase 4 (Day 4):** Approval Interface
- [ ] Create PendingRegistrations component
- [ ] Build admin interface
- [ ] Test approval workflow

**Phase 5 (Day 5):** Testing & Documentation
- [ ] Full end-to-end testing
- [ ] Email template testing
- [ ] Documentation update

---

## For Your ABC Logistics Example

**Right now:**
```
✅ Tenant created in admin panel
✅ Database created (carriergo_tenant_1)
❌ No user exists
```

**After implementation:**
```
1. Admin goes to /admin/tenants
2. Clicks "+ New Tenant"
3. Fills:
   - Name: ABC Logistics
   - Domain: abc-logistics
   - Plan: professional
   - Employee: John Doe (john@abc.com)
4. Clicks "Create"
5. System:
   ✅ Creates tenant
   ✅ Creates database
   ✅ Creates user John
   ✅ Sends email with credentials
6. John receives email
7. John logs in and uses system!
```

All automatic, zero manual steps!

---

## Documentation

**Complete architecture saved in:**
`docs/TENANT_REGISTRATION_AND_ONBOARDING_ARCHITECTURE.md`

**Updated master context in:**
`docs/AI_MASTER_CONTEXT.md`

**Task tracking in:**
`docs/CURRENT_TASK.md` (Task 6.5)

---

## Summary

The solution is:
1. **Two flows** (admin-created, self-registration)
2. **One database pattern** (registrations table in carrierlab)
3. **Universal architecture** (works for all scenarios)
4. **Email-driven** (credentials/approvals via email)
5. **Scalable** (grows with your business)

Ready to implement! 🚀
