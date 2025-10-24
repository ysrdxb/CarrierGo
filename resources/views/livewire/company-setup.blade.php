<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="text-center mb-5">
                <h1 class="h2 fw-bold mb-2">Complete Your Company Setup</h1>
                <p class="text-muted">Almost there! Tell us about your company to get started with {{ config('app.name') }}</p>
            </div>

            <!-- Error Alerts -->
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <h5 class="alert-heading">Setup Error</h5>
                    <p class="mb-0">{{ session('error') }}</p>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <h5 class="alert-heading">Please fix the following errors:</h5>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Company Setup Form -->
            <form wire:submit="submitCompanyInfo">
                <!-- Company Information Card -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Company Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="company_name" class="form-label">Company Name <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                id="company_name"
                                wire:model.blur="company_name"
                                class="form-control @error('company_name') is-invalid @enderror"
                                placeholder="Your Company Name"
                                required>
                            @error('company_name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Street Address <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                id="address"
                                wire:model.blur="address"
                                class="form-control @error('address') is-invalid @enderror"
                                placeholder="123 Business Street"
                                required>
                            @error('address')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="city" class="form-label">City <span class="text-danger">*</span></label>
                                    <input
                                        type="text"
                                        id="city"
                                        wire:model.blur="city"
                                        class="form-control @error('city') is-invalid @enderror"
                                        placeholder="New York"
                                        required>
                                    @error('city')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="zip_code" class="form-label">Zip Code <span class="text-danger">*</span></label>
                                    <input
                                        type="text"
                                        id="zip_code"
                                        wire:model.blur="zip_code"
                                        class="form-control @error('zip_code') is-invalid @enderror"
                                        placeholder="10001"
                                        required>
                                    @error('zip_code')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="country" class="form-label">Country <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                id="country"
                                wire:model.blur="country"
                                class="form-control @error('country') is-invalid @enderror"
                                placeholder="United States"
                                required>
                            @error('country')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                            <input
                                type="tel"
                                id="phone"
                                wire:model.blur="phone"
                                class="form-control @error('phone') is-invalid @enderror"
                                placeholder="+1 (555) 000-0000"
                                required>
                            @error('phone')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Contact Information Card -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Contact Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="email" class="form-label">Contact Email</label>
                            <input
                                type="email"
                                id="email"
                                wire:model.blur="email"
                                class="form-control"
                                placeholder="Email"
                                readonly>
                            <small class="text-muted d-block mt-2">
                                <i class="fas fa-check-circle text-success"></i> This email was verified during registration
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Subscription Plan Info -->
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <h5 class="alert-heading">Your Plan</h5>
                    <p class="mb-2">
                        <strong class="text-capitalize">{{ $registration->subscription_plan ?? 'Free' }} Plan</strong>
                    </p>
                    @if($registration->subscription_plan !== 'free')
                        <p class="mb-0 small">
                            Payment will be processed after you complete the setup. You'll be redirected to checkout.
                        </p>
                    @else
                        <p class="mb-0 small">
                            Enjoy unlimited access to all features during your trial period.
                        </p>
                    @endif
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>

                <!-- Submit Button -->
                <div class="d-grid gap-2 mb-3">
                    <button
                        type="submit"
                        class="btn btn-primary btn-lg"
                        wire:loading.attr="disabled">
                        <span wire:loading.remove>
                            <i class="fas fa-check"></i> Complete Setup & Access Dashboard
                        </span>
                        <span wire:loading>
                            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                            Setting up your company...
                        </span>
                    </button>
                </div>

                <div class="text-center">
                    <small class="text-muted">
                        All company information can be updated later in your account settings.
                    </small>
                </div>
            </form>
        </div>
    </div>
</div>
