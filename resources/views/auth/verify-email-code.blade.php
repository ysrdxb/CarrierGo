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
                    <form method="POST" action="{{ route('otp.verify') }}" class="login100-form validate-form">
                        @csrf
                        <span class="login100-form-title pb-5">
                            Verify OTP
                        </span>

                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                                @if (session('otp'))
                                    <br><strong>Your OTP Code: {{ session('otp') }}</strong>
                                @endif
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
                            <div class="panel-body tabs-menu-body p-0 pt-1">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab5">
                                        <div class="mt-4">
                                            <label for="code" class="form-label">Enter OTP</label>
                                            <input id="code" type="number" class="form-control" name="otp" required>
                                        </div>
                                        <div class="pt-4">
                                            <div class="row">
                                                <div class="col text-start">
                                                    <a href="{{ route('login') }}" class="text-primary"><i class="fas fa-arrow-left"></i> Back to login</a>
                                                </div>
                                                <div class="col text-end">
                                                    <a href="{{ route('otp.resend') }}" class="text-primary"><i class="fas fa-arrow-refresh"></i> Resend OTP</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="container-login100-form-btn">
                                            <button type="submit" class="login100-form-btn btn-primary">
                                                Verify Email
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

    @endsection
