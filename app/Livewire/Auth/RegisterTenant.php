<?php

namespace App\Livewire\Auth;

use App\Mail\VerifyRegistrationMailable;
use App\Models\Registration;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Component;

class RegisterTenant extends Component
{
    #[Validate('required|string|min:3|max:255')]
    public $companyName = '';

    #[Validate('required|string|unique:registrations,domain|regex:/^[a-z0-9-]+$/|min:3|max:63')]
    public $domain = '';

    #[Validate('required|in:free,starter,professional,enterprise')]
    public $subscriptionPlan = 'free';

    #[Validate('required|string|min:2|max:100')]
    public $firstName = '';

    #[Validate('required|string|min:2|max:100')]
    public $lastName = '';

    #[Validate('required|email|unique:registrations,email')]
    public $email = '';

    #[Validate('required|string|min:8')]
    public $password = '';

    #[Validate('required|string|min:8')]
    public $passwordConfirmation = '';

    public $agreeToTerms = false;

    public $showPassword = false;

    public $registrationSubmitted = false;

    public $registrationEmail = '';

    /**
     * List of subscription plans with descriptions and pricing
     */
    public static $plans = [
        'free' => [
            'name' => 'Free Plan',
            'price' => '$0',
            'period' => 'Forever',
            'description' => 'Perfect for getting started',
            'features' => [
                '1 user account',
                '5 shipments/month',
                'Basic reporting',
                '30-day trial for paid features'
            ]
        ],
        'starter' => [
            'name' => 'Starter Plan',
            'price' => '$99',
            'period' => 'per month',
            'description' => 'For small teams',
            'features' => [
                'Up to 5 users',
                '500 shipments/month',
                'Advanced reporting',
                'Email support',
                '14-day free trial'
            ]
        ],
        'professional' => [
            'name' => 'Professional Plan',
            'price' => '$299',
            'period' => 'per month',
            'description' => 'For growing businesses',
            'features' => [
                'Up to 20 users',
                'Unlimited shipments',
                'Custom integrations',
                'Priority support',
                '14-day free trial'
            ]
        ],
        'enterprise' => [
            'name' => 'Enterprise Plan',
            'price' => 'Custom',
            'period' => 'contact sales',
            'description' => 'For large organizations',
            'features' => [
                'Unlimited users',
                'Unlimited shipments',
                'Custom development',
                '24/7 phone support',
                'Custom SLA'
            ]
        ]
    ];

    public function render()
    {
        return view('livewire.auth.register-tenant')->layout('components.layouts.guest');
    }

    /**
     * Submit registration form
     */
    public function submitRegistration()
    {
        $this->validate(
            messages: [
                'domain.unique' => 'This subdomain is already taken. Please choose another one.',
                'domain.regex' => 'Subdomain can only contain lowercase letters, numbers, and hyphens.',
                'domain.min' => 'Subdomain must be at least 3 characters long.',
                'domain.max' => 'Subdomain must not exceed 63 characters.',
                'email.unique' => 'This email is already registered. Please use a different email or login.',
                'password.min' => 'Password must be at least 8 characters long.',
                'passwordConfirmation.min' => 'Password confirmation must be at least 8 characters long.',
                'agreeToTerms.required' => 'You must agree to the Terms of Service.',
            ]
        );

        // Verify passwords match
        if ($this->password !== $this->passwordConfirmation) {
            $this->addError('passwordConfirmation', 'The password confirmation does not match.');
            return;
        }

        // Verify terms agreement
        if (!$this->agreeToTerms) {
            $this->addError('agreeToTerms', 'You must agree to the terms and conditions.');
            return;
        }

        try {
            // Create registration record
            $registration = Registration::create([
                'company_name' => $this->companyName,
                'domain' => strtolower($this->domain),
                'subscription_plan' => $this->subscriptionPlan,
                'firstname' => $this->firstName,
                'lastname' => $this->lastName,
                'email' => strtolower($this->email),
                'password_hash' => bcrypt($this->password),
                'status' => 'pending',
                'verification_token' => Str::random(64),
                'verification_token_expires_at' => now()->addHours(24),
                'trial_days' => 14,
                'trial_expires_at' => now()->addDays(14),
            ]);

            // Log email tracking
            $registration->emails()->create([
                'email_type' => 'welcome',
                'status' => 'pending',
            ]);

            // Generate verification URL
            $verificationUrl = url("/register/verify/{$registration->verification_token}");

            // Send verification email
            Mail::queue(new VerifyRegistrationMailable(
                firstName: $this->firstName,
                lastName: $this->lastName,
                email: $this->email,
                companyName: $this->companyName,
                verificationUrl: $verificationUrl,
            ));

            // Update email tracking to sent
            $registration->emails()
                ->where('email_type', 'welcome')
                ->first()
                ?->markAsSent();

            // Show success state
            $this->registrationSubmitted = true;
            $this->registrationEmail = $this->email;

            // Reset form
            $this->resetForm();

        } catch (\Exception $e) {
            \Log::error('Registration submission error: ' . $e->getMessage());
            $this->addError('form', 'An error occurred during registration. Please try again.');
        }
    }

    /**
     * Reset the registration form
     */
    public function resetForm()
    {
        $this->companyName = '';
        $this->domain = '';
        $this->subscriptionPlan = 'free';
        $this->firstName = '';
        $this->lastName = '';
        $this->email = '';
        $this->password = '';
        $this->passwordConfirmation = '';
        $this->agreeToTerms = false;
        $this->showPassword = false;
        $this->resetValidation();
    }

    /**
     * Check domain availability in real-time
     */
    public function checkDomainAvailability()
    {
        $this->validateOnly('domain');
    }

    /**
     * Toggle password visibility
     */
    public function togglePasswordVisibility()
    {
        $this->showPassword = !$this->showPassword;
    }
}
