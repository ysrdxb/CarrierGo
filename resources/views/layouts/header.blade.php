            <!-- app-Header -->
            <div class="header sticky fixed-header visible-title hor-header">
                <div class="container-fluid main-container">
                    <div class="d-flex">
                        <a aria-label="Hide Sidebar" class="app-sidebar__toggle" data-bs-toggle="sidebar" href="#"></a>
                        <!-- sidebar-toggle-->
                        <a class="logo-horizontal " href="{{ route('dashboard') }}">
                            <img style="max-width:68px;" src="{{ asset('admin/images/brand/logo-main.png') }}" class="header-brand-img desktop-logo" alt="logo">
                            <img style="max-width:68px;" src="{{ asset('admin/images/brand/logo-main.png') }}" class="header-brand-img light-logo1" alt="logo">
                        </a>
                        <!-- LOGO -->

                        <div class="d-flex order-lg-2 ms-auto header-right-icons">
                            <div class="dropdown d-lg-none d-md-block d-none">
                                <a href="#" class="nav-link icon" data-bs-toggle="dropdown">
                                    <i class="fe fe-search"></i>
                                </a>
                                <div class="dropdown-menu header-search dropdown-menu-start">
                                    <div class="input-group w-100 p-2">
                                        <input type="text" class="form-control" placeholder="Search....">
                                        <div class="input-group-text btn btn-primary">
                                            <i class="fa fa-search" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- SEARCH -->
                            <button class="navbar-toggler navresponsive-toggler d-md-none ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent-4" aria-controls="navbarSupportedContent-4" aria-expanded="false" aria-label="Toggle navigation">
                                    <span class="navbar-toggler-icon fe fe-more-vertical"></span>
                                </button>
                            <div class="navbar navbar-collapse responsive-navbar p-0">
                                <div class="collapse navbar-collapse" id="navbarSupportedContent-4">
                                    <div class="d-flex order-lg-2">
                                        <div class="dropdown d-md-none d-flex">
                                            <a href="#" class="nav-link icon" data-bs-toggle="dropdown">
                                                <i class="fe fe-search"></i>
                                            </a>
                                            <div class="dropdown-menu header-search dropdown-menu-start">
                                                <div class="input-group w-100 p-2">
                                                    <input type="text" class="form-control" placeholder="Search....">
                                                    <div class="input-group-text btn btn-primary">
                                                        <i class="fa fa-search" aria-hidden="true"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- COUNTRY -->
                                        <div class="d-flex country">
                                            <a class="nav-link icon text-center" data-bs-target="#country-selector" data-bs-toggle="modal">
                                                <i class="fe fe-globe"></i><span class="fs-16 ms-2 d-none d-xl-block">English</span>
                                            </a>
                                        </div>
                                        <!-- SEARCH -->

                                        <!-- Theme-Layout -->
                                        <div class="dropdown d-flex">
                                            <a class="nav-link icon full-screen-link nav-link-bg">
                                                <i class="fe fe-minimize fullscreen-button"></i>
                                            </a>
                                        </div>
                                        <!-- SIDE-MENU -->
                                        @if(Auth::check())
                                        <div class="dropdown d-flex profile-1">
                                            <a href="#" data-bs-toggle="dropdown" class="nav-link leading-none d-flex">
                                                <img src="{{ asset('admin/images/users/21.jpg') }}" alt="profile-user" class="avatar  profile-user brround cover-image">
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                <div class="drop-heading">
                                                    <div class="text-center">
                                                        <h5 class="text-dark mb-0 fs-14 fw-semibold">{{ Auth::user()->firstname . ' ' . Auth::user()->lastname }}</h5>
                                                        <small class="text-muted">
                                                           
                                                            {{ Auth::user()->hasRole('Admin') ? 'Admin' : 'Employee' }}
                                                            
                                                        </small>
                                                    </div>                                                                                                       
                                                </div>
                                                <div class="dropdown-divider m-0"></div>
                                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                                    <i class="dropdown-icon fe fe-user"></i> Edit Profile
                                                </a>                                                

                                                @if(Auth::user()->hasRole('Admin'))
                                                    <a class="dropdown-item" href="{{ route('admin.tenants.list') }}">
                                                        <i class="dropdown-icon fe fe-users"></i> Manage Tenants
                                                    </a>
                                                    <a class="dropdown-item" href="{{ route('admin.registrations.approvals') }}">
                                                        <i class="dropdown-icon fe fe-check-circle"></i> Approve Registrations
                                                    </a>
                                                    <a class="dropdown-item" href="{{ route('settings.list') }}">
                                                        <i class="dropdown-icon fe fe-settings"></i> Settings
                                                    </a>                         
                                                    <a class="dropdown-item" href="{{ route('freighttypes.list') }}">
                                                        <i class="dropdown-icon fe fe-truck"></i> Freight Types
                                                    </a>      
                                                    <a class="dropdown-item" href="{{ route('destinations.list') }}">
                                                        <i class="dropdown-icon fe fe-globe"></i> Destinations
                                                    </a> 												
                                                    <a class="dropdown-item" href="{{ route('bankDetails.list') }}">
                                                        <i class="dropdown-icon fe fe-dollar-sign"></i> Bank Details
                                                    </a>
                                                    <a class="dropdown-item" href="{{ route('users.list') }}">
                                                        <i class="dropdown-icon fe fe-users"></i> Employees
                                                    </a>
                                                    {{-- <a class="dropdown-item" href="{{ route('companies.list') }}">
                                                        <i class="dropdown-icon fe fe-book"></i> Companies
                                                    </a>
                                                    <a class="dropdown-item" href="{{ route('languages.list') }}">
                                                        <i class="dropdown-icon fe fe-globe"></i> Languages
                                                    </a>
                                                    <a class="dropdown-item" href="{{ route('roles.list') }}">
                                                        <i class="dropdown-icon fe fe-shield"></i> Roles
                                                    </a>
                                                    <a class="dropdown-item" href="{{ route('permissions.list') }}">
                                                        <i class="dropdown-icon fe fe-lock"></i> Permissions
                                                    </a> --}}
                                                @else
                                                    <a class="dropdown-item" href="{{ route('user.reference') }}">
                                                        <i class="dropdown-icon fe fe-book"></i> My Ref Numbers
                                                    </a>
                                                @endif
                                                <form method="POST" action="{{ route('logout') }}">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item" onclick="event.preventDefault(); this.closest('form').submit();">
                                                        <i class="dropdown-icon fe fe-log-out"></i>
                                                        {{ __('Log Out') }}
                                                    </button>                                                        
                                                </form>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /app-Header -->

            <div class="sticky">
                <div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
                <div class="app-sidebar">
                    <div class="side-header">
                        <a class="header-brand1" href="{{ route('dashboard') }}">
                            <img src="{{ session()->has('logo') ? asset('storage/' . session('logo')) : asset('admin/images/brand/logo.png') }}" class="header-brand-img desktop-logo" alt="logo">
                            <img src="{{ session()->has('logo') ? asset('storage/' . session('logo')) : asset('admin/images/brand/logo.png') }}" class="header-brand-img toggle-logo" alt="logo">
                            <img src="{{ session()->has('logo') ? asset('storage/' . session('logo')) : asset('admin/images/brand/logo.png') }}" class="header-brand-img light-logo" alt="logo">
                            <img src="{{ session()->has('logo') ? asset('storage/' . session('logo')) : asset('admin/images/brand/logo.png') }}" class="header-brand-img light-logo1" alt="logo">
                        </a>
                        <!-- LOGO -->

                    </div>
                    <div class="main-sidemenu">
                        <div class="slide-left disabled" id="slide-left"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"><path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"/></svg></div>
                        <ul class="side-menu">
                            <li class="slide">
                                <a class="side-menu__item" data-bs-toggle="slide" href="{{ route('dashboard.index') }}"><span class="side-menu__label">Dashboard</span></a>
                            </li>
                            <li class="slide">
                                <a class="side-menu__item" data-bs-toggle="slide" href="{{ route('references.list') }}"><span class="side-menu__label">References</span></a>
                            </li>
                            <li class="slide">
                                <a class="side-menu__item" data-bs-toggle="slide" href="{{ route('invoices.list') }}"><span class="side-menu__label">Invoices</span></a>
                            </li>
                            <li class="slide">
                                <a class="side-menu__item" data-bs-toggle="slide" href="{{ route('incinvoices.list') }}"><span class="side-menu__label">Incoming Invoices</span></a>
                            </li>                            
                            <li class="slide">
                                <a class="side-menu__item" data-bs-toggle="slide" href="{{ route('databases.list') }}"><span class="side-menu__label">Database</span></a>
                            </li>
                            <li class="slide">
                                <a class="side-menu__item" data-bs-toggle="slide" href="{{ route('references.create') }}"><span class="badge bg-info badge-sm  me-1 mb-1 mt-1"><i class="fe fe-plus me-2"></i>NEW REF</span></a>
                            </li>
                            <li class="slide">
                                <a class="side-menu__item" data-bs-toggle="slide" href="{{ route('incinvoices.create') }}"><span class="badge bg-info badge-sm  me-1 mb-1 mt-1"><i class="fe fe-plus me-2"></i>INC INVOICE</span></a>
                            </li>

                        </ul>

                </div>
                <!--/APP-SIDEBAR-->
            </div>
