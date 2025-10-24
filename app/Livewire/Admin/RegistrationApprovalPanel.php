<?php

namespace App\Livewire\Admin;

use App\Mail\RegistrationApprovedMailable;
use App\Mail\RegistrationRejectedMailable;
use App\Models\Registration;
use App\Jobs\ProvisionTenant;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

class RegistrationApprovalPanel extends Component
{
    use WithPagination;

    #[Validate('required|string')]
    public $rejectionReason = '';

    public $selectedRegistration = null;

    public $showRejectionForm = false;

    public function render()
    {
        $registrations = Registration::where('status', 'verified')
            ->where('subscription_plan', '!=', 'free')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.registration-approval-panel', [
            'registrations' => $registrations,
        ]);
    }

    public function selectRegistration($registrationId)
    {
        $this->selectedRegistration = Registration::findOrFail($registrationId);
        $this->showRejectionForm = false;
    }

    public function approveRegistration()
    {
        if (!$this->selectedRegistration) {
            $this->addError('form', 'No registration selected.');
            return;
        }

        try {
            $registration = $this->selectedRegistration;
            $registration->markAsApproved();

            // Create Tenant record
            $tenant = \App\Models\Tenant::create([
                'name' => $registration->company_name,
                'domain' => $registration->domain,
                'subscription_plan' => $registration->subscription_plan,
                'subscription_status' => 'pending', // Will be set to 'active' by ProvisionTenant job
                'created_by_admin' => true,
                'approval_status' => 'approved',
                'trial_days' => 14,
                'trial_expires_at' => now()->addDays(14),
                'tenancy_mode' => 'SHARED',
            ]);

            // Create Company record
            \App\Models\Company::create([
                'tenant_id' => $tenant->id,
                'name' => $registration->company_name,
                'address' => '',
                'zip_code' => '',
                'city' => '',
                'country' => '',
            ]);

            // Update registration with tenant_id
            $registration->update(['tenant_id' => $tenant->id]);

            // Dispatch the ProvisionTenant job to create admin user
            ProvisionTenant::dispatch(
                tenant: $tenant,
                firstname: $registration->firstname,
                lastname: $registration->lastname,
                email: $registration->email,
                password: $registration->password_hash,
                isPlainText: false,
                tenancyMode: 'SHARED'
            );

            $loginUrl = url('/login');
            Mail::queue(new RegistrationApprovedMailable(
                firstName: $registration->firstname,
                lastName: $registration->lastname,
                companyName: $registration->company_name,
                domain: $registration->domain,
                loginUrl: $loginUrl,
            ));

            $registration->emails()->create([
                'email_type' => 'approved',
                'status' => 'sent',
                'sent_at' => now(),
            ]);

            $this->selectedRegistration = null;
            \Log::info("Registration {$registration->id} approved and tenant provisioned");

        } catch (\Exception $e) {
            \Log::error('Registration approval error: ' . $e->getMessage());
            $this->addError('form', 'An error occurred. Please try again.');
        }
    }

    public function toggleRejectionForm()
    {
        $this->showRejectionForm = !$this->showRejectionForm;
    }

    public function rejectRegistration()
    {
        $this->validate();

        if (!$this->selectedRegistration) {
            $this->addError('form', 'No registration selected.');
            return;
        }

        try {
            $registration = $this->selectedRegistration;
            $registration->markAsRejected($this->rejectionReason);

            Mail::queue(new RegistrationRejectedMailable(
                firstName: $registration->firstname,
                lastName: $registration->lastname,
                companyName: $registration->company_name,
                rejectionReason: $this->rejectionReason,
            ));

            $registration->emails()->create([
                'email_type' => 'rejected',
                'status' => 'sent',
                'sent_at' => now(),
            ]);

            $this->selectedRegistration = null;
            $this->showRejectionForm = false;
            $this->rejectionReason = '';

        } catch (\Exception $e) {
            \Log::error('Registration rejection error: ' . $e->getMessage());
            $this->addError('form', 'An error occurred. Please try again.');
        }
    }
}
