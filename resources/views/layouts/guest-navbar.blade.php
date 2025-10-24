<!-- Guest Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">
            <img src="{{ asset('admin/images/brand/logo-main.png') }}" alt="CarrierGo" style="max-height: 40px; margin-right: 10px;">
            <span>{{ config('app.name') }}</span>
        </a>

        <!-- Mobile Toggle -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navigation Links -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('dashboard') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#about">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#contact">Contact</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#features">Features</a>
                </li>
                @guest
                <li class="nav-item">
                    <a class="nav-link btn btn-primary btn-sm text-white" href="{{ route('login') }}" style="margin-left: 10px;">Login</a>
                </li>
                @endguest
                @auth
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('dashboard.index') }}">Dashboard</a>
                </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
