# Tenant Registration & Onboarding Architecture

## Overview

This document defines the comprehensive approach for:
1. **Admin-Created Tenants** - Admin creates tenant + employee in one step
2. **Self-Registration** - Customer registers with plan selection, requires approval
3. **Data Persistence** - How user data is stored before tenant database exists

---

## Problem Statement

**Issue**: When a customer self-registers, their user data (name, email, password) has nowhere to be stored before their tenant database is created.

**Solution**: Use **central carrierlab database** to temporarily store registration data, then provision database and migrate data when approved.

---

## System Architecture

### Central Database (carrierlab) - Permanent

Stores:
- Tenant metadata (name, domain, plan, status)
- Registration tokens (temporary registration data)
- Admin users (only system admins)
- Subscription information
- Billing information

### Tenant Databases (carriergo_tenant_X) - Per-Tenant

Each tenant gets isolated database with:
- Their users/employees
- Their shipments
- Their invoices
- Their orders
- Their documents
- All business data

---

## Database Schema - New Tables Needed

### 1. carrierlab.registrations (New)

Stores temporary registration data before tenant DB is created.

```sql
CREATE TABLE registrations (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,

    -- Tenant Info
    company_name VARCHAR(255) NOT NULL,
    domain VARCHAR(255) NOT NULL UNIQUE,
    subscription_plan ENUM('free', 'starter', 'professional', 'enterprise') DEFAULT 'free',

    -- First User Info
    firstname VARCHAR(100) NOT NULL,
    lastname VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,

    -- Registration Status
    status ENUM('pending', 'approved', 'rejected', 'completed') DEFAULT 'pending',
    rejection_reason TEXT NULL,

    -- Payment Info (for paid plans)
    payment_method VARCHAR(50) NULL, -- 'credit_card', 'paypal', etc.
    payment_status ENUM('pending', 'completed', 'failed') NULL,
    stripe_customer_id VARCHAR(255) NULL,

    -- Trial Info
    trial_expires_at TIMESTAMP NULL,

    -- Timestamps
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    approved_at TIMESTAMP NULL,
    completed_at TIMESTAMP NULL,

    -- Token for verification
    verification_token VARCHAR(255) UNIQUE NULL,
    verification_token_expires_at TIMESTAMP NULL,

    -- Track if tenant DB was created
    tenant_id BIGINT NULL,
    tenant_database_created_at TIMESTAMP NULL,

    INDEX idx_status (status),
    INDEX idx_email (email),
    INDEX idx_domain (domain),
    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE SET NULL
);
```

### 2. carrierlab.tenants (Modified)

Add new columns:

```sql
ALTER TABLE tenants ADD COLUMN (
    created_by_admin BOOLEAN DEFAULT FALSE,
    approval_status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    trial_days INT DEFAULT 14,
    trial_expires_at TIMESTAMP NULL
);
```

### 3. carrierlab.onboarding_emails (New)

Track which emails were sent to avoid duplicates:

```sql
CREATE TABLE onboarding_emails (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    registration_id BIGINT,
    tenant_id BIGINT NULL,
    email_type ENUM('welcome', 'approval_required', 'approved', 'credentials', 'rejection') NOT NULL,
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (registration_id) REFERENCES registrations(id) ON DELETE CASCADE,
    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE
);
```

---

## Flow 1: Admin-Created Tenant (Recommended for B2B)

### User Journey

```
Admin Dashboard
    ↓
/admin/tenants
    ↓
Click: "+ New Tenant"
    ↓
Form appears:
├─ Tenant Name: "ABC Logistics"
├─ Domain: "abc-logistics" (slug format)
├─ Subscription Plan: "professional"
├─ Employee Info:
│  ├─ First Name: "John"
│  ├─ Last Name: "Doe"
│  ├─ Email: "john@abclogistics.com"
│  └─ Password: "Auto-generate or manual"
└─ Click: "Create Tenant & Send Credentials"
    ↓
System automatically:
├─ Validates domain availability
├─ Creates tenant record in carrierlab.tenants
├─ Creates database: carriergo_tenant_{id}
├─ Runs migrations
├─ Creates first admin user in new database
├─ Sends welcome email with credentials
└─ Shows success message with login link
    ↓
Employee can login immediately!
```

### Implementation Details

#### 1. Updated TenantManager Component

File: `app/Livewire/Admin/TenantManager.php`

```php
public $form = [
    'id' => null,
    'name' => '',
    'domain' => '',
    'subscription_plan' => 'free',
    'subscription_status' => 'active',
    // NEW: Employee fields
    'employee_firstname' => '',
    'employee_lastname' => '',
    'employee_email' => '',
    'employee_password' => '',
    'auto_generate_password' => true,
];

public function saveTenant()
{
    $this->validate([
        'form.name' => 'required|string|max:255',
        'form.domain' => 'required|string|max:255|unique:tenants,domain',
        'form.subscription_plan' => 'required|in:free,starter,professional,enterprise',
        // NEW: Validate employee
        'form.employee_firstname' => 'required|string|max:100',
        'form.employee_lastname' => 'required|string|max:100',
        'form.employee_email' => 'required|email|unique:tenants,domain', // Must be unique across all dbs
        'form.employee_password' => 'required_if:form.auto_generate_password,false|min:8',
    ]);

    try {
        // Create tenant
        $tenant = Tenant::create([
            'name' => $this->form['name'],
            'domain' => $this->form['domain'],
            'subscription_plan' => $this->form['subscription_plan'],
            'subscription_status' => 'active',
            'created_by_admin' => true, // NEW
        ]);

        // Generate password if needed
        $password = $this->form['auto_generate_password']
            ? Str::random(12)
            : $this->form['employee_password'];

        // Dispatch job to setup database and create user
        ProvisionTenant::dispatch(
            $tenant,
            $this->form['employee_firstname'],
            $this->form['employee_lastname'],
            $this->form['employee_email'],
            $password
        );

        // Send email with credentials
        Mail::to($this->form['employee_email'])->send(
            new TenantCredentialsMailable(
                $this->form['employee_firstname'],
                $tenant->domain,
                $this->form['employee_email'],
                $password
            )
        );

        session()->flash('success', 'Tenant created! Credentials sent to ' . $this->form['employee_email']);
        $this->resetForm();
        $this->showEditModal = false;
    } catch (\Exception $e) {
        session()->flash('error', 'Error: ' . $e->getMessage());
    }
}
```

#### 2. Updated View - tenant-manager.blade.php

Add employee fields to the modal:

```blade
<!-- Employee Fields (NEW) -->
<div class="modal-body">
    <!-- ... existing tenant fields ... -->

    <hr class="my-3">
    <h6>First Employee/Admin</h6>

    <div class="mb-3">
        <label class="form-label">First Name</label>
        <input
            type="text"
            wire:model="form.employee_firstname"
            class="form-control @error('form.employee_firstname') is-invalid @enderror"
            placeholder="E.g., John"
        >
    </div>

    <div class="mb-3">
        <label class="form-label">Last Name</label>
        <input
            type="text"
            wire:model="form.employee_lastname"
            class="form-control @error('form.employee_lastname') is-invalid @enderror"
            placeholder="E.g., Doe"
        >
    </div>

    <div class="mb-3">
        <label class="form-label">Email</label>
        <input
            type="email"
            wire:model="form.employee_email"
            class="form-control @error('form.employee_email') is-invalid @enderror"
            placeholder="E.g., john@example.com"
        >
    </div>

    <div class="mb-3">
        <label class="form-label">
            <input type="checkbox" wire:model="form.auto_generate_password">
            Auto-generate password
        </label>
    </div>

    @if(!$form['auto_generate_password'])
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input
                type="password"
                wire:model="form.employee_password"
                class="form-control @error('form.employee_password') is-invalid @enderror"
            >
        </div>
    @endif
</div>
```

#### 3. Email: TenantCredentialsMailable

File: `app/Mail/TenantCredentialsMailable.php`

```php
class TenantCredentialsMailable extends Mailable
{
    public function __construct(
        public string $firstName,
        public string $domain,
        public string $email,
        public string $password
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your CarrierGo Account is Ready',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.tenant-credentials',
            with: [
                'firstName' => $this->firstName,
                'domain' => $this->domain,
                'email' => $this->email,
                'password' => $this->password,
                'loginUrl' => "http://{$this->domain}.carriergo.com/login",
            ],
        );
    }
}
```

---

## Flow 2: Self-Registration (For Customers)

### User Journey

```
Customer visits: carriergo.com/register
    ↓
Fills registration form:
├─ Company Name: "XYZ Transport"
├─ Subdomain: "xyz-transport" (auto-slug, system checks availability)
├─ First Name: "Jane"
├─ Last Name: "Smith"
├─ Email: "jane@xyztransport.com"
├─ Password: (secure password)
├─ Plan: "Free Trial" | "Starter ($99/mo)" | "Professional ($299/mo)" | "Enterprise (Custom)"
└─ Accept Terms
    ↓
Submit
    ↓
System:
├─ Validates all fields
├─ Saves to carrierlab.registrations table (status='pending')
├─ Generates verification token
├─ Sends verification email
└─ Shows: "Check your email to verify account"
    ↓
Customer clicks email verification link
    ↓
System:
├─ Marks email as verified
├─ If FREE PLAN:
│  ├─ Auto-approves
│  ├─ Creates tenant in carrierlab.tenants
│  ├─ Creates database carriergo_tenant_X
│  ├─ Runs migrations
│  ├─ Creates user in new database
│  ├─ Sends "Account Ready" email
│  └─ Redirects to /login
│
└─ If PAID PLAN:
   ├─ Shows payment form (Stripe)
   ├─ After payment successful:
   │  ├─ Creates registration record with payment_status='completed'
   │  ├─ Sends to admin: "Approval Required" email
   │  └─ Shows: "Payment received. Admin will review and approve shortly"
   │
   └─ Admin approves in /admin/pending-registrations
      ├─ Creates tenant
      ├─ Creates database
      ├─ Creates user
      └─ Sends approval email to customer
    ↓
Customer logs in at: xyz-transport.carriergo.com/login
    ↓
Dashboard loaded with their data!
```

### Implementation Details

#### 1. Updated RegisterTenant Component

File: `app/Livewire/Auth/RegisterTenant.php`

```php
class RegisterTenant extends Component
{
    public string $company_name = '';
    public string $domain = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $firstname = '';
    public string $lastname = '';
    public string $subscription_plan = 'free';
    public bool $terms_accepted = false;

    public function register()
    {
        $this->validate([
            'company_name' => 'required|string|min:3|max:255',
            'domain' => ['required', 'regex:/^[a-z0-9-]+$/', Rule::unique('tenants', 'domain')],
            'firstname' => 'required|string|min:2|max:100',
            'lastname' => 'required|string|min:2|max:100',
            'email' => ['required', 'email', Rule::unique('registrations', 'email')],
            'password' => 'required|string|min:8|confirmed',
            'subscription_plan' => 'required|in:free,starter,professional,enterprise',
            'terms_accepted' => 'required|accepted',
        ]);

        try {
            // Create registration record (status=pending)
            $registration = Registration::create([
                'company_name' => $this->company_name,
                'domain' => strtolower($this->domain),
                'firstname' => $this->firstname,
                'lastname' => $this->lastname,
                'email' => $this->email,
                'password_hash' => Hash::make($this->password),
                'subscription_plan' => $this->subscription_plan,
                'status' => 'pending',
                'verification_token' => Str::random(64),
                'verification_token_expires_at' => now()->addHours(24),
                'trial_expires_at' => now()->addDays(14),
            ]);

            // Send verification email
            Mail::to($this->email)->send(
                new VerifyRegistrationMailable($registration)
            );

            session()->flash('success', 'Check your email to verify your account!');
            $this->resetForm();
        } catch (\Exception $e) {
            $this->addError('registration', $e->getMessage());
        }
    }

    public function verifyEmail(Request $request)
    {
        $registration = Registration::where('verification_token', $request->token)
            ->where('verification_token_expires_at', '>', now())
            ->firstOrFail();

        // If FREE plan, auto-approve
        if ($registration->subscription_plan === 'free') {
            $this->approveAndProvision($registration);
        }
        // If PAID plan, require payment first
        else {
            $registration->update(['status' => 'verified']);
            return redirect('/register/payment/' . $registration->id);
        }
    }

    private function approveAndProvision(Registration $registration)
    {
        try {
            // Create tenant
            $tenant = Tenant::create([
                'name' => $registration->company_name,
                'domain' => $registration->domain,
                'subscription_plan' => $registration->subscription_plan,
                'subscription_status' => 'active',
                'created_by_admin' => false, // Self-registered
                'trial_expires_at' => $registration->trial_expires_at,
            ]);

            // Provision database and user
            ProvisionTenant::dispatch(
                $tenant,
                $registration->firstname,
                $registration->lastname,
                $registration->email,
                $registration->password_hash,
                false // Don't hash, already hashed
            );

            // Mark registration as completed
            $registration->update([
                'status' => 'completed',
                'completed_at' => now(),
                'tenant_id' => $tenant->id,
            ]);

            // Send welcome email
            Mail::to($registration->email)->send(
                new RegistrationApprovedMailable($registration, $tenant)
            );

            return redirect('/login')->with('success', 'Account created! You can now login.');
        } catch (\Exception $e) {
            $registration->update(['status' => 'rejected', 'rejection_reason' => $e->getMessage()]);
            throw $e;
        }
    }
}
```

#### 2. Pending Registrations Admin Interface

File: `app/Livewire/Admin/PendingRegistrations.php`

```php
class PendingRegistrations extends Component
{
    use WithPagination;

    public function approveRegistration($registrationId)
    {
        $registration = Registration::findOrFail($registrationId);

        if ($registration->status !== 'paid') {
            throw new \Exception('Only paid registrations can be approved');
        }

        // Create tenant
        $tenant = Tenant::create([
            'name' => $registration->company_name,
            'domain' => $registration->domain,
            'subscription_plan' => $registration->subscription_plan,
            'subscription_status' => 'active',
        ]);

        // Provision
        ProvisionTenant::dispatch(
            $tenant,
            $registration->firstname,
            $registration->lastname,
            $registration->email,
            $registration->password_hash,
            false
        );

        // Mark as completed
        $registration->update([
            'status' => 'completed',
            'tenant_id' => $tenant->id,
            'completed_at' => now(),
        ]);

        // Send approval email
        Mail::to($registration->email)->send(
            new RegistrationApprovedMailable($registration, $tenant)
        );

        session()->flash('success', 'Registration approved!');
    }

    public function rejectRegistration($registrationId, $reason)
    {
        $registration = Registration::findOrFail($registrationId);
        $registration->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
        ]);

        Mail::to($registration->email)->send(
            new RegistrationRejectedMailable($registration, $reason)
        );

        session()->flash('success', 'Registration rejected');
    }
}
```

---

## Data Flow Diagram

```
┌─────────────────────────────────────────────────────────────────────┐
│                    SELF-REGISTRATION FLOW                            │
└─────────────────────────────────────────────────────────────────────┘

Customer Registers
    ↓
carrierlab.registrations (NEW)
├─ Status: pending
├─ Verification: email sent
    ↓
Customer verifies email
    ↓
[FREE PLAN?]
├─ YES:
│  ├─ Create tenant in carrierlab.tenants
│  ├─ Create database carriergo_tenant_X
│  ├─ Run migrations
│  ├─ Create user in new DB
│  ├─ Mark registration as 'completed'
│  └─ Send welcome email
│
└─ NO (PAID):
   ├─ Customer pays
   ├─ Payment saved in registrations (payment_status='completed')
   ├─ Admin gets notification
   ├─ Admin reviews and approves
   ├─ Auto-provision database (same as free)
   └─ Send approval email

┌─────────────────────────────────────────────────────────────────────┐
│                    ADMIN CREATION FLOW                               │
└─────────────────────────────────────────────────────────────────────┘

Admin Creates Tenant
    ↓
carrierlab.tenants (NEW)
├─ created_by_admin: true
├─ approval_status: approved (auto)
    ↓
Dispatch ProvisionTenant Job
├─ Create database carriergo_tenant_X
├─ Run migrations
├─ Create first user
    ↓
Send credentials email to employee
    ↓
Employee can login immediately!
```

---

## Key Benefits

### Admin-Created Flow
✅ **Instant Setup** - Employee can login immediately
✅ **No Payment** - For B2B contracts, invitations, etc.
✅ **Full Control** - Admin sets employee credentials
✅ **Quick Onboarding** - No email verification delays

### Self-Registration Flow
✅ **Customer Self-Service** - No admin involvement for free trial
✅ **Payment Capture** - Integrates with Stripe
✅ **Admin Approval** - Control over paid signups
✅ **Email Verification** - Ensures valid emails
✅ **Audit Trail** - All registration data stored

### Data Persistence
✅ **Universal** - Works for both flows
✅ **Temporary Storage** - Registration data in carrierlab
✅ **Provisioning** - Auto-creates database when approved
✅ **Data Integrity** - User data migrated safely

---

## Migration Requirements

```bash
# New tables
php artisan make:migration create_registrations_table
php artisan make:migration create_onboarding_emails_table
php artisan make:migration add_approval_to_tenants_table

# Models
php artisan make:model Registration
php artisan make:model OnboardingEmail

# Mailables
php artisan make:mail TenantCredentialsMailable
php artisan make:mail VerifyRegistrationMailable
php artisan make:mail RegistrationApprovedMailable
php artisan make:mail RegistrationRejectedMailable

# Livewire Components
php artisan make:livewire Admin/PendingRegistrations
# Update existing RegisterTenant
# Update existing TenantManager
```

---

## Email Templates Needed

1. **TenantCredentialsMailable** - Admin-created tenant credentials
2. **VerifyRegistrationMailable** - Self-registration verification link
3. **RegistrationApprovedMailable** - Approval after payment
4. **RegistrationRejectedMailable** - Rejection reason

---

## Routes Needed

```php
// Public
Route::get('/register', RegisterTenant::class)->name('tenant.register');
Route::get('/register/verify/{token}', [RegisterTenant::class, 'verifyEmail']);
Route::get('/register/payment/{id}', PaymentGateway::class);

// Admin
Route::middleware(['admin'])->group(function () {
    Route::get('/admin/pending-registrations', PendingRegistrations::class)
        ->name('admin.registrations.pending');
});
```

---

## Implementation Order

1. **Phase 1** (This Week)
   - Create migration tables
   - Update TenantManager to support employee creation
   - Dispatch ProvisionTenant with new parameters
   - Create credentials email

2. **Phase 2** (Next Week)
   - Update RegisterTenant for self-registration
   - Create registration table
   - Add verification email flow
   - Auto-provision for free tier

3. **Phase 3** (Following Week)
   - Add payment integration
   - Create pending registrations admin interface
   - Admin approval workflow
   - Payment emails

---

## Security Considerations

✅ **Verification Tokens** - 24-hour expiry
✅ **Password Hashing** - Bcrypt for all passwords
✅ **Database Isolation** - Each tenant completely separated
✅ **Email Verification** - Prevents fake registrations
✅ **Admin Approval** - Control over paid signups
✅ **Rate Limiting** - Prevent registration spam

---

## Success Metrics

- ✅ Admin can create tenant + user in one step
- ✅ Employee receives credentials email
- ✅ Employee can login immediately
- ✅ Customer can self-register free trial
- ✅ Admin approves paid registrations
- ✅ Automatic database provisioning
- ✅ Complete data isolation
- ✅ Email notifications sent correctly

