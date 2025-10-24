# CarrierGo MVP - AI Master Context

**NEVER MODIFY THIS FILE - AI READS ONLY**

## Project Overview
- **Name:** CarrierGo
- **Type:** Multi-tenant Freight Management SaaS
- **Tech Stack:** Laravel 11, Livewire 3, Alpine.js, Tailwind CSS
- **Database:** MySQL/PostgreSQL
- **Platform:** Replit
- **Repo:** https://github.com/ysrdxb/CarrierGo

## Current State
- Single-tenant freight management system
- Modules: Shipments, Invoices, Documents, Employees, Users
- Needs: Multi-tenancy, Customer Portal, Payment Processing

## Goal
8-week MVP to launch SaaS with:
- Multi-tenant architecture
- Customer self-service portal
- Stripe payment integration
- Automated billing
- Modern UI/UX

## Development Rules

### Code Standards
1. Follow Laravel 11 best practices
2. Use Livewire 3 for interactive components
3. Use Tailwind CSS for styling (match existing patterns)
4. Write clean, commented code
5. Add proper error handling and validation
6. Use Replit integrations for external services
7. Test features before marking complete

### File Management Rules
1. **ALWAYS update PROGRESS_TRACKER.md** after completing any task
2. **ALWAYS update CURRENT_TASK.md** with what you're working on
3. **ALWAYS append to SESSION_LOG.md** with summary when done
4. **ALWAYS update FILES_MODIFIED.md** with changed files
5. Use ✅ for completed tasks, ⏳ for in-progress, [ ] for pending

### Workflow
1. Read PROGRESS_TRACKER.md to see what's done
2. Read CURRENT_TASK.md to know what to work on
3. Implement the task
4. Update all tracking files
5. Ask user: "Task complete. Continue to next?" or "Session complete. Summary logged."

## Tenant Registration & Onboarding (UPDATED WEEK 1)

### Problem Solved
Previously, there was no clear path for how customers self-register or how admins create tenants with employees. This has been designed:

**See: docs/TENANT_REGISTRATION_AND_ONBOARDING_ARCHITECTURE.md**

### Two Registration Flows

#### 1. Admin-Created Tenant (B2B)
- Admin goes to `/admin/tenants`
- Admin fills: Tenant info + First Employee info
- System automatically:
  - Creates tenant record
  - Creates database
  - Creates first user
  - Sends credentials email to employee
  - Employee can login immediately!

#### 2. Self-Registration (B2C)
- Customer visits `/register`
- Fills: Company + Personal + Plan info
- System:
  - Saves to `carrierlab.registrations` (temporary table)
  - Sends verification email
  - If FREE: Auto-approves, provisions database, creates user
  - If PAID: Requires payment + admin approval first
  - After approval: Creates database, sends welcome email

### Key Insight: Data Storage Before Tenant DB Exists
- Use **carrierlab.registrations** table for temporary storage
- After approval, tenant DB is created
- User data migrated to new tenant database
- Registration marked as 'completed'

### Required Tables
- `carrierlab.registrations` - NEW
- `carrierlab.onboarding_emails` - NEW
- `tenants` - Modified with approval_status, trial fields

### Required Models
- `Registration` model
- `OnboardingEmail` model

### Required Email Templates
- TenantCredentialsMailable (admin-created)
- VerifyRegistrationMailable (self-registration)
- RegistrationApprovedMailable (after approval)
- RegistrationRejectedMailable (rejection)

---

## Tech Stack Details

### Backend
- Laravel 11 (PHP 8.2+)
- Eloquent ORM
- Livewire 3.4
- Spatie Laravel Permission
- DomPDF for PDFs
- Yajra DataTables
- Laravel Mail (for notifications)

### Frontend
- Livewire 3.4
- Alpine.js 3.4
- Tailwind CSS 3.x (existing) / Bootstrap 5 (admin panel)
- Vite 5.x

### Packages to Use
- `stancl/tenancy` - Multi-tenancy (Week 1) ✅
- `filament/filament` or `wireui/wireui` - UI components (Week 2)
- Stripe via Replit integration (Week 5)
- Laravel Mail - Email notifications

## 8-Week Plan Summary

**Week 1:** Multi-tenancy foundation
**Week 2:** UI/UX + Subscription setup
**Week 3:** Customer portal (auth & dashboard)
**Week 4:** Customer portal (tracking & documents)
**Week 5:** Payment integration (Stripe)
**Week 6:** Automated billing + notifications
**Week 7:** Tenant signup + subscription billing
**Week 8:** Polish, testing, launch

## Integration Guidelines

**Always use Replit integrations for:**
- Stripe (payments)
- Twilio (SMS)
- OpenAI (if adding AI features)
- Email services

**Command:** Use `search_integrations` tool before manually implementing.

## After Each Task

Provide this format:

✅ TASK COMPLETED: [Task name]

FILES MODIFIED:

path/to/file1.php
path/to/file2.blade.php
DATABASE CHANGES:

Created migration: xxxx_create_table
Modified table: table_name
TESTING:

✅ Feature works as expected
✅ No breaking changes
UPDATED DOCS:

✅ PROGRESS_TRACKER.md
✅ CURRENT_TASK.md
✅ FILES_MODIFIED.md
NEXT: [Next task name] or ASK USER

---
**This context never changes. AI reads it every session.**