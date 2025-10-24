<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-body text-center p-5">
                    <i class="fas fa-clock fa-4x text-warning mb-4"></i>
                    
                    <h2 class="card-title fw-bold mb-2">Registration Pending Approval</h2>
                    
                    <p class="text-muted mb-4">
                        Thank you for registering with {{ config("app.name") }}!
                    </p>

                    <div class="alert alert-info mb-4" role="alert">
                        <p class="mb-0">
                            Your registration for <strong>{{ $registration->company_name }}</strong> has been verified and is now pending approval from our team. 
                        </p>
                        <small class="text-muted d-block mt-2">You should receive an approval email within 24 hours.</small>
                    </div>

                    <div class="alert alert-light border mb-4" role="alert">
                        <p class="mb-1"><strong>What happens next?</strong></p>
                        <ul class="list-unstyled text-start mt-3">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> We review your account details</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Our team verifies your information</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> You receive approval email</li>
                            <li><i class="fas fa-check text-success me-2"></i> Set up payment method (if needed)</li>
                        </ul>
                    </div>

                    <div class="alert alert-secondary" role="alert">
                        <p class="mb-0"><small>Check your email at <strong>{{ $registration->email }}</strong> for updates.</small></p>
                    </div>

                    <p class="text-muted mt-4">
                        <small>Questions? Contact us at support@{{ config("app.domain", "carriergo.com") }}</small>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>