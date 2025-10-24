# Task 6: Create Admin Panel for Tenant Management

**Status:** 🚀 Ready to Start
**Week:** 1 (Final Task)
**Acceptance Criteria:** Complete admin CRUD for tenants, subscription management, and basic analytics

---

## 📋 IMPLEMENTATION PLAN

This is a **Super Admin Control Panel** to manage all tenants globally - separate from the tenant-side admin interface that manages employees, companies, etc.

### **Phase 1: Core Admin Components** (Priority: HIGH)

#### 1.1 AdminDashboard Component
**Purpose:** Overview of all tenants and system statistics

**File:** `app/Livewire/Admin/AdminDashboard.php`

**Features:**
- Total tenants count
- Active subscriptions count
- Revenue from active subscriptions
- Signup stats (this week, this month)
- Failed provisioning attempts
- Upcoming subscription expirations
- Quick actions (create tenant, view reports)

**Data Points:**
```php
- Total Tenants: DB::table('tenants')->count()
- Active Subscriptions: DB::table('tenants')->where('subscription_status', 'active')->count()
- Suspended Tenants: DB::table('tenants')->where('subscription_status', 'suspended')->count()
- Failed Tenants: DB::table('tenants')->where('subscription_status', 'failed')->count()
- Free Trials: DB::table('tenants')->where('subscription_plan', 'free')->count()
- MRR (Monthly Recurring Revenue): sum from paid plans
```

---

#### 1.2 TenantsList Component
**Purpose:** List all tenants with search, filters, and bulk actions

**File:** `app/Livewire/Admin/TenantsList.php`

**Features:**
- Table with pagination (50 per page)
- Columns: ID, Name, Domain, Plan, Status, Created, Actions
- Search by name or domain
- Filter by:
  - Subscription Plan (Free, Starter, Pro, Enterprise)
  - Subscription Status (Active, Suspended, Failed)
  - Date range (created between dates)
- Sort by: Name, Created Date, Status
- Actions: View, Edit, Suspend, Delete, View Database
- Bulk actions: Suspend Multiple, Delete Multiple, Export CSV

**Livewire Methods:**
```php
- search()                    // Search by name/domain
- filterByPlan()             // Filter by subscription plan
- filterByStatus()           // Filter by subscription status
- filterByDateRange()        // Filter by creation date
- sortBy()                   // Sort results
- viewTenant()               // Open detail modal
- editTenant()               // Open edit form
- suspendTenant()            // Toggle suspension
- deleteTenant()             // Soft delete
- bulkSuspend()              // Suspend multiple
- bulkDelete()               // Delete multiple
- exportCSV()                // Export to CSV
```

---

#### 1.3 TenantDetail Component (Modal/Page)
**Purpose:** View detailed information about a specific tenant

**File:** `app/Livewire/Admin/TenantDetail.php`

**Fields Displayed:**
```
Basic Info:
- Tenant ID
- Company Name
- Domain
- Status
- Created Date
- Last Login (if tracked)

Subscription Info:
- Current Plan
- Status
- Subscription Start Date
- Subscription Expiry Date
- Days Until Expiry / Days Since Expiry

Database Info:
- Database Name: tenant_{id}
- Database Size (if available)
- Table Count: 40
- User Count (from tenant DB)

Quick Stats:
- Total Users (from tenant DB)
- Total Companies (from tenant DB)
- Total Invoices (from tenant DB)
- Total Shipments/References (from tenant DB)
```

---

#### 1.4 TenantEdit Component
**Purpose:** Edit tenant information and subscription details

**File:** `app/Livewire/Admin/TenantEdit.php`

**Editable Fields:**
```
Company Information:
- Company Name (text input)
- Domain (text input, with validation)
- Status (dropdown: active, suspended, failed)

Subscription Management:
- Subscription Plan (dropdown: free, starter, pro, enterprise)
- Subscription Status (dropdown: active, suspended, inactive)
- Subscription Expires At (date picker)
- Set free trial? (toggle + days selector)

Admin Notes (textarea):
- Notes for internal use
```

**Validation:**
- Company name: 3-100 chars
- Domain: unique (except current), lowercase, alphanumeric + hyphens
- Subscription expires at: future date or null
- Free trial days: 1-90

**Actions:**
- Save Changes (with confirmation)
- Reset to Defaults (cancel changes)
- Send Welcome Email (to admin)
- View Tenant Database (link to MySQL info)

---

#### 1.5 SubscriptionManager Component
**Purpose:** Manage subscription plans and track usage

**File:** `app/Livewire/Admin/SubscriptionManager.php`

**Features:**

**Subscription Plans Table:**
```
Plan Types:
- Free (Default on signup)
  - Max Users: Unlimited
  - Max Shipments: 50/month
  - Storage: 1GB
  - Support: Email

- Starter ($99/month)
  - Max Users: 5
  - Max Shipments: 500/month
  - Storage: 10GB
  - Support: Email + Chat

- Pro ($299/month)
  - Max Users: 20
  - Max Shipments: 2000/month
  - Storage: 100GB
  - Support: Priority

- Enterprise (Custom)
  - Unlimited Everything
  - Custom Support
```

**Tenant Subscription Operations:**
```
- Change Plan (upgrade/downgrade)
- Pause/Resume Subscription
- Send Invoice (manual)
- Edit Billing Period
- Add/Remove Features
- View Usage (shipments used, storage used)
- Set Custom Limits
```

---

#### 1.6 AdminUsers Component
**Purpose:** Manage super admin accounts with access to the admin panel

**File:** `app/Livewire/Admin/AdminUsers.php`

**Features:**
- List all admin users
- Create new admin account
- Edit admin profile
- Change password
- Assign roles (Super Admin, Admin, Moderator)
- Suspend/activate admin account
- View last login
- Two-factor authentication toggle
- API token management

---

#### 1.7 UsageAnalytics Component
**Purpose:** Analyze tenant usage and trends

**File:** `app/Livewire/Admin/UsageAnalytics.php`

**Metrics:**
- Tenants Created (daily/weekly/monthly)
- Signups Trend (chart)
- Subscription Distribution (pie chart: free vs paid)
- Revenue Trend (line chart)
- Churn Rate (how many tenants suspended/deleted)
- Feature Usage (most used features across tenants)
- Top Tenants (by usage, by revenue)
- Database Size Trends

---

### **Phase 2: Routes & Middleware**

#### 2.1 Admin Routes
**File:** `routes/web.php` (new admin route group)

```php
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', AdminDashboard::class)->name('dashboard');

    // Tenants Management
    Route::get('/tenants', TenantsList::class)->name('tenants.list');
    Route::get('/tenants/{tenant}', TenantDetail::class)->name('tenants.show');
    Route::get('/tenants/{tenant}/edit', TenantEdit::class)->name('tenants.edit');

    // Subscriptions
    Route::get('/subscriptions', SubscriptionManager::class)->name('subscriptions.manage');

    // Admin Users
    Route::get('/users', AdminUsers::class)->name('users.list');

    // Analytics
    Route::get('/analytics', UsageAnalytics::class)->name('analytics');

    // Reports
    Route::get('/reports', AdminReports::class)->name('reports');
});
```

#### 2.2 Admin Middleware
**File:** `app/Http/Middleware/AdminMiddleware.php`

```php
// Check if user has admin role
// Redirect to dashboard if not authorized
// Log admin access for security
```

---

### **Phase 3: Admin Layout & Views**

#### 3.1 Admin Layout
**File:** `resources/views/layouts/admin.blade.php`

**Components:**
- Top navigation bar (logo, breadcrumbs, user menu)
- Left sidebar (navigation menu):
  - Dashboard
  - Tenants Management
  - Subscriptions
  - Admin Users
  - Analytics & Reports
  - Settings
- Main content area
- Footer with current time, PHP version

**Styling:** Tailwind CSS (consistent with existing UI)

**Features:**
- Collapsible sidebar on mobile
- Dark mode toggle (optional)
- Quick search (global tenant search)
- Notifications bell (pending actions)
- User profile dropdown

---

#### 3.2 View Files
```
resources/views/admin/
├── dashboard.blade.php         (Admin dashboard main page)
├── tenants/
│   ├── list.blade.php          (Tenants list table)
│   ├── detail.blade.php        (Tenant detail view)
│   └── form.blade.php          (Tenant edit form)
├── subscriptions/
│   ├── plans.blade.php         (Subscription plans table)
│   └── usage.blade.php         (Usage details)
├── users/
│   ├── list.blade.php          (Admin users list)
│   └── form.blade.php          (Create/edit admin user)
└── analytics/
    ├── dashboard.blade.php     (Analytics overview)
    └── reports.blade.php       (Detailed reports)
```

---

### **Phase 4: Database & Models**

#### 4.1 Models (use central database)

**Tenant Model** (already exists: `app/Models/Tenant.php`)
- Add methods:
  ```php
  public function getDatabaseSize()        // Query tenant DB size
  public function getUserCount()            // Count users in tenant
  public function isActive()                // Check if subscription is active
  public function daysUntilExpiry()         // Days until subscription expires
  public function suspend()                 // Mark as suspended
  public function activate()                // Mark as active
  public function getUsageStats()           // Get usage metrics
  ```

**AdminUser Model** (new: `app/Models/AdminUser.php`)
- For managing admin accounts separately
- Has role and permissions
- Can have multiple admin accounts for different regions/teams

---

### **Phase 5: Features & Polish**

#### 5.1 Admin-Only Features
- Create new tenant manually (without going through signup)
- Bulk import tenants from CSV
- Backup tenant databases
- Restore from backup
- Send mass emails to all tenants
- Create announcement/notification for all tenants
- View tenant activity logs
- IP whitelisting for admin panel
- Two-factor authentication for admins

#### 5.2 Security
- Role-based access control (Super Admin, Admin, Moderator)
- Audit logging (who accessed what and when)
- IP whitelisting for admin panel
- Rate limiting on sensitive operations
- Encryption of sensitive data
- Admin session timeout after inactivity

#### 5.3 Performance
- Pagination (50 tenants per page)
- Lazy loading of heavy components
- Caching of dashboard stats (5-minute cache)
- Database query optimization
- Indexing for search and filters

---

## 🎯 ACCEPTANCE CRITERIA

### Minimum Requirements (Phase 1)
- [ ] AdminDashboard component with tenant count and basic stats
- [ ] TenantsList component with pagination and search
- [ ] TenantDetail component to view tenant information
- [ ] TenantEdit component to modify tenant details and subscription
- [ ] SubscriptionManager component for plan management
- [ ] Admin routes with middleware protection
- [ ] Admin layout with sidebar navigation
- [ ] All components styled with Tailwind CSS
- [ ] No database access errors or missing data

### Nice to Have (Phase 2)
- [ ] AdminUsers component for managing super admins
- [ ] UsageAnalytics component with charts
- [ ] CSV export functionality
- [ ] Bulk operations (suspend/delete multiple)
- [ ] Tenant database size display
- [ ] Activity logs and audit trails

---

## 📊 IMPLEMENTATION SEQUENCE

**Step 1:** Create AdminDashboard component
**Step 2:** Create TenantsList component with Livewire pagination
**Step 3:** Create TenantDetail & TenantEdit components
**Step 4:** Create SubscriptionManager component
**Step 5:** Create AdminMiddleware for route protection
**Step 6:** Set up admin routes in routes/web.php
**Step 7:** Create admin layout blade file
**Step 8:** Create admin views and Tailwind styling
**Step 9:** Test all components
**Step 10:** Update PROGRESS_TRACKER.md

---

## 💾 FILES TO CREATE

```
app/Livewire/Admin/
├── AdminDashboard.php         (Stats & overview)
├── TenantsList.php             (List with pagination/search)
├── TenantDetail.php            (View details)
├── TenantEdit.php              (Edit form)
├── SubscriptionManager.php     (Plan management)
├── AdminUsers.php              (Admin account management)
└── UsageAnalytics.php          (Charts and analytics)

app/Http/Middleware/
└── AdminMiddleware.php         (Check admin authorization)

app/Models/
└── AdminUser.php               (Model for admin accounts)

resources/views/admin/
├── dashboard.blade.php
├── layout.blade.php
├── tenants/
│   ├── list.blade.php
│   ├── detail.blade.php
│   └── form.blade.php
└── subscriptions/
    └── manage.blade.php
```

---

## 🚀 ESTIMATED TIMELINE

- AdminDashboard: 30 minutes
- TenantsList: 45 minutes
- TenantDetail & Edit: 1 hour
- SubscriptionManager: 30 minutes
- Middleware & Routes: 15 minutes
- Admin Layout & Views: 45 minutes
- Testing & Polish: 30 minutes

**Total: ~4 hours**

---

**Ready to Start Implementation!** ✅

Should I begin with:
1. ✅ AdminDashboard component, or
2. ✅ TenantsList component, or
3. ✅ AdminMiddleware first (foundation), or
4. ✅ All of the above (recommended)?
