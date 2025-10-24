<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="text-center mb-5">
                <h1 class="h2 fw-bold mb-2">Create Your Account</h1>
                <p class="text-muted">Join {{ config("app.name") }} and start managing your freight operations</p>
            </div>

            @if ($registrationSubmitted)
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <h5 class="alert-heading">Registration Submitted!</h5>
                    <p class="mb-0">
                        Verification email has been sent to <strong>{{ $registrationEmail }}</strong>
                    </p>
                    <p class="text-muted small mt-2 mb-0">
                        Please check your email and click the verification link to complete registration.
                    </p>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <div class="text-center mt-4">
                    <p class="text-muted">Didn't receive the email?</p>
                    <button type="button" class="btn btn-link" wire:click="$set('registrationSubmitted', false)">
                        Try again
                    </button>
                </div>
            @else
                <form wire:submit="submitRegistration">
                    <!-- Plan Selection -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Choose Your Plan</h5>
                        </div>
                        <div class="card-body">
                            <style>
                                .plan-btn:checked + .plan-label {
                                    color: #fff !important;
                                    background-color: #0d6efd;
                                    border-color: #0d6efd;
                                }
                                .plan-btn:checked + .plan-label strong,
                                .plan-btn:checked + .plan-label small {
                                    color: #fff !important;
                                }
                            </style>
                            <div class="row">
                                <div class="col-md-6 col-lg-3">
                                    <input type="radio" class="btn-check plan-btn" id="p1" value="free" wire:model.live="subscriptionPlan">
                                    <label for="p1" class="btn btn-outline-primary w-100 py-3 plan-label">
                                        <div><strong>Free</strong></div>
                                        <small>$0/month</small>
                                    </label>
                                </div>
                                <div class="col-md-6 col-lg-3">
                                    <input type="radio" class="btn-check plan-btn" id="p2" value="starter" wire:model.live="subscriptionPlan">
                                    <label for="p2" class="btn btn-outline-primary w-100 py-3 plan-label">
                                        <div><strong>Starter</strong></div>
                                        <small>$99/month</small>
                                    </label>
                                </div>
                                <div class="col-md-6 col-lg-3">
                                    <input type="radio" class="btn-check plan-btn" id="p3" value="professional" wire:model.live="subscriptionPlan">
                                    <label for="p3" class="btn btn-outline-primary w-100 py-3 plan-label">
                                        <div><strong>Professional</strong></div>
                                        <small>$299/month</small>
                                    </label>
                                </div>
                                <div class="col-md-6 col-lg-3">
                                    <input type="radio" class="btn-check plan-btn" id="p4" value="enterprise" wire:model.live="subscriptionPlan">
                                    <label for="p4" class="btn btn-outline-primary w-100 py-3 plan-label">
                                        <div><strong>Enterprise</strong></div>
                                        <small>Custom</small>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Company Information -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Company Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Company Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('companyName') is-invalid @enderror"
                                               wire:model.blur="companyName" placeholder="Your Company Name">
                                        @error('companyName')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Subdomain <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control @error('domain') is-invalid @enderror"
                                                   wire:model.live.debounce-500ms="domain" placeholder="your-company">
                                            <span class="input-group-text">.carriergo.local</span>
                                        </div>
                                        @error('domain')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                        @if (!$errors->has('domain') && $domain)
                                            <small class="text-success d-block mt-1">
                                                <i class="fas fa-check-circle"></i> Subdomain is available
                                            </small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Your Information -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Your Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">First Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('firstName') is-invalid @enderror"
                                               wire:model.blur="firstName" placeholder="John">
                                        @error('firstName')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('lastName') is-invalid @enderror"
                                               wire:model.blur="lastName" placeholder="Doe">
                                        @error('lastName')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       wire:model.blur="email" placeholder="john@example.com">
                                @error('email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Password <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                                               wire:model.blur="password" placeholder="Minimum 8 characters">
                                        @error('password')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted d-block mt-1">At least 8 characters required</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control @error('passwordConfirmation') is-invalid @enderror"
                                               wire:model.blur="passwordConfirmation" placeholder="Confirm password">
                                        @error('passwordConfirmation')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Terms Agreement -->
                    <div class="mb-4">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input @error('agreeToTerms') is-invalid @enderror"
                                   id="agree" wire:model="agreeToTerms">
                            <label class="form-check-label" for="agree">
                                I agree to the Terms of Service and Privacy Policy
                            </label>
                        </div>
                        @error('agreeToTerms')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg" wire:loading.attr="disabled">
                            <span wire:loading.remove>Create Account</span>
                            <span wire:loading>
                                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                Creating Account...
                            </span>
                        </button>
                    </div>

                    <!-- Login Link -->
                    <div class="text-center mt-4">
                        <p class="text-muted">Already have an account? <a href="{{ route('login') }}" class="text-decoration-none">Login here</a></p>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>