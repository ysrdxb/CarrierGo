<!-- Guest Footer -->
<footer class="bg-dark text-white py-5 mt-5">
    <div class="container">
        <div class="row">
            <!-- Company Info -->
            <div class="col-lg-4 col-md-6 mb-4">
                <h5 class="mb-3">
                    <img src="{{ asset('admin/images/brand/logo-main.png') }}" alt="CarrierGo" style="max-height: 30px; margin-right: 8px; filter: brightness(0) invert(1);">
                    {{ config('app.name') }}
                </h5>
                <p class="text-muted">
                    Professional freight and logistics management platform designed to streamline your shipping operations.
                </p>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6 mb-4">
                <h5 class="mb-3">Quick Links</h5>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="{{ route('dashboard') }}" class="text-muted text-decoration-none">Home</a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('tenant.register') }}" class="text-muted text-decoration-none">Register</a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('login') }}" class="text-muted text-decoration-none">Login</a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="text-muted text-decoration-none">Track Shipment</a>
                    </li>
                </ul>
            </div>

            <!-- Company -->
            <div class="col-lg-2 col-md-6 mb-4">
                <h5 class="mb-3">Company</h5>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="#about" class="text-muted text-decoration-none">About Us</a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="text-muted text-decoration-none">Blog</a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="text-muted text-decoration-none">Careers</a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="text-muted text-decoration-none">Press</a>
                    </li>
                </ul>
            </div>

            <!-- Support -->
            <div class="col-lg-2 col-md-6 mb-4">
                <h5 class="mb-3">Support</h5>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="#contact" class="text-muted text-decoration-none">Contact Us</a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="text-muted text-decoration-none">Help Center</a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="text-muted text-decoration-none">Documentation</a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="text-muted text-decoration-none">Status</a>
                    </li>
                </ul>
            </div>

            <!-- Legal -->
            <div class="col-lg-2 col-md-6 mb-4">
                <h5 class="mb-3">Legal</h5>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="#" class="text-muted text-decoration-none">Privacy Policy</a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="text-muted text-decoration-none">Terms of Service</a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="text-muted text-decoration-none">Cookie Policy</a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="text-muted text-decoration-none">Compliance</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="border-top border-secondary mt-4 pt-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="text-muted mb-0">
                        &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="social-links">
                        <a href="#" class="text-muted text-decoration-none me-3">
                            <i class="fab fa-facebook"></i>
                        </a>
                        <a href="#" class="text-muted text-decoration-none me-3">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="text-muted text-decoration-none me-3">
                            <i class="fab fa-linkedin"></i>
                        </a>
                        <a href="#" class="text-muted text-decoration-none">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
