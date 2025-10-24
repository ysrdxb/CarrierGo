@extends('layouts.guest')

@section('content')

<div>
    <!-- PAGE -->
    <div class="page">
        <div class="">
            <!-- Theme-Layout -->

            <!-- CONTAINER OPEN -->
            <div class="col col-login mx-auto mt-7">
                <div class="text-center">
                    <img style="max-width: 150px" src="{{ asset('admin/images/brand/logo-main.png') }}" class="header-brand-img" alt="">
                </div>
            </div>

            <div class="container-login100">
                <div class="wrap-login100 p-6">
                    <form method="POST" action="{{ route('login') }}" class="login100-form validate-form">
                        @csrf
                        <span class="login100-form-title pb-1">
                            Login
                        </span>

                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="panel panel-primary">

                            <div class="panel-body tabs-menu-body p-0 pt-5">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab5">
                                        <div class="wrap-input100 validate-input input-group" data-bs-validate="Valid email is required: ex@abc.xyz">
                                            <a href="#" class="input-group-text bg-white text-muted">
                                                <i class="fas fa-envelope text-muted" aria-hidden="true"></i>
                                            </a>
                                            <input class="input100 form-control" type="email" name="email" value="{{ old('email') }}" placeholder="Email" autofocus autocomplete>
                                        </div>
                                        <div class="wrap-input100 validate-input input-group" id="Password-toggle">
                                            <a href="#" class="input-group-text bg-white text-muted">
                                                <i class="fas fa-eye text-muted" aria-hidden="true"></i>
                                            </a>
                                            <input class="input100 form-control" type="password" name="password" placeholder="Password">
                                        </div>
                                        <div class="text-end pt-4">
                                            <p class="mb-0"><a href="{{ route('password.request') }}" class="text-primary ms-1">Forgot Password?</a></p>
                                        </div>
                                        <div class="container-login100-form-btn">
                                            <button type="submit" class="login100-form-btn btn-primary">
                                                Login
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- CONTAINER CLOSED -->
        </div>
    </div>
    <!-- End PAGE -->

</div>
</div>

    @endsection
