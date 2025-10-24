<?php

namespace App\Livewire;

use App\Models\Registration;
use App\Models\Tenant;
use App\Models\Company;
use App\Models\User;
use App\Jobs\ProvisionTenant;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class CompanySetup extends Component
{
    public $registration_id;
    public $registration;

    // Form fields
    public $company_name = '';
    public $address = '';
    public $zip_code = '';
    public $city = '';
    public $country = '';
    public $phone = '';
    public $email = '';

    public $step = 1; // Step 1: Company Info, Step 2: Confirmation
    public $submitted = false;

    protected $rules = [
        'company_name' => 'required|string|min:3|max:255',
        'address' => 'required|string|min:5|max:500',
        'zip_code' => 'required|string|max:20',
        'city' => 'required|string|max:100',
        'country' => 'required|string|max:100',
        'phone' => 'required|string|max:20',
        'email' => 'required|email|max:255',
    ];

    public function mount($registration_id)
    {
        $this->registration_id = $registration_id;

        try {
            // Get registration record
            $this->registration = Registration::findOrFail($registration_id);

            \Log::info("CompanySetup mounted", [
                "registration_id" => $registration_id,
                "email" => $this->registration->email,
                "status" => $this->registration->status
            ]);

            // Check if registration is verified
            if ($this->registration->status !== 'verified') {
                $message = 'Registration status is: ' . $this->registration->status . '. Please verify your email first.';
                session()->flash('error', $message);
                \Log::warning("Registration not verified", ["status" => $this->registration->status]);
                redirect('/login');
                return;
            }

            // Pre-fill some fields
            $this->company_name = $this->registration->company_name;
            $this->email = $this->registration->email;
        } catch (\Exception $e) {
            \Log::error("CompanySetup mount error: " . $e->getMessage());
            session()->flash('error', 'Error loading registration: ' . $e->getMessage());
            redirect('/login');
        }
    }

    public function render()
    {
        return view('livewire.company-setup')->layout('components.layouts.guest');
    }

    /**
     * Submit company information and create tenant
     */
    public function submitCompanyInfo()
    {
        // Validate all fields
        $validated = $this->validate();

        try {
            // Create Tenant
            $tenant = Tenant::create([
                'name' => $this->company_name,
                'domain' => $this->registration->domain,
                'subscription_plan' => $this->registration->subscription_plan,
                'subscription_status' => 'active',
                'tenancy_mode' => 'SHARED',
                'trial_days' => $this->registration->trial_days ?? 14,
                'trial_expires_at' => $this->registration->trial_expires_at ?? now()->addDays(14),
            ]);

            \Log::info("Tenant created successfully", [
                "tenant_id" => $tenant->id,
                "registration_id" => $this->registration->id,
                "company_name" => $this->company_name
            ]);

            // Create Company
            $company = Company::create([
                'tenant_id' => $tenant->id,
                'name' => $this->company_name,
                'address' => $this->address,
                'zip_code' => $this->zip_code,
                'city' => $this->city,
                'country' => $this->country,
                'logo' => null,
            ]);

            \Log::info("Company created successfully", [
                "company_id" => $company->id,
                "tenant_id" => $tenant->id,
                "company_name" => $this->company_name
            ]);

            // Create User with tenant_id
            $user = User::create([
                'tenant_id' => $tenant->id,
                'firstname' => $this->registration->firstname,
                'lastname' => $this->registration->lastname,
                'email' => $this->registration->email,
                'phone' => $this->phone,
                'email_verified_at' => now(),
                'password' => $this->registration->password_hash,
                'otp' => rand(100000, 999999),
                'otp_expiry' => now()->addHours(1)->toDateTimeString(),
                'image' => '',
                'start_date' => now()->toDateString(),
            ]);

            // Assign Super Admin role
            $user->assignRole('Super Admin');

            \Log::info("User created successfully", [
                "user_id" => $user->id,
                "tenant_id" => $tenant->id,
                "email" => $this->registration->email
            ]);

            // Mark registration as completed
            $this->registration->status = 'completed';
            $this->registration->tenant_id = $tenant->id;
            $this->registration->save();

            \Log::info("Registration marked as completed", [
                "registration_id" => $this->registration->id,
                "tenant_id" => $tenant->id
            ]);

            // Set success message and redirect to login
            return redirect()->route('login')
                ->with('success', 'Company setup completed! You can now log in with your email and password.');

        } catch (\Exception $e) {
            \Log::error("Company setup error: " . $e->getMessage(), [
                "exception" => get_class($e),
                "file" => $e->getFile(),
                "line" => $e->getLine(),
                "trace" => $e->getTraceAsString()
            ]);
            $this->addError('form', 'An error occurred while setting up your company: ' . $e->getMessage());
        }
    }
}
