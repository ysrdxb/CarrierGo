@extends('layouts.app')

@section('content')

    <div>

        <!-- GLOABAL LOADER -->
        <div id="global-loader">
            <img src="../assets/images/loader.svg" class="loader-img" alt="Loader">
        </div>
        <!-- /GLOABAL LOADER -->

        <!-- PAGE -->
        <div class="page">
            <div class="">
                <!-- Theme-Layout -->

                <!-- CONTAINER OPEN -->
                <div class="col col-login mx-auto mt-7">
                    <div class="text-center">
                        <img src="../assets/images/brand/logo-white.png" class="header-brand-img" alt="">
                    </div>
                </div>

                <div class="container-login100">
                    <div class="wrap-login100 p-6">
                        <form class="login100-form validate-form">
                            <span class="login100-form-title pb-5">
                                Enter verification code
                            </span>
                            <div class="panel panel-primary">
                                <div class="tab-menu-heading">
                                    
                                </div>
                                <div class="panel-body tabs-menu-body p-0 pt-5">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="tab5">
                                            <div class="wrap-input100 validate-input input-group" id="Password-toggle">
                                                <a href="#" class="input-group-text bg-white text-muted">
                                                    <i class="zmdi zmdi-key text-muted" aria-hidden="true"></i>
                                                </a>
                                                <input class="input100 form-control" type="password" placeholder="Code">
                                            </div>
                                            <div class="text-end pt-4">
                                                <p class="mb-0"><a href="#" class="text-primary ms-1">Code not received? Try again.</a></p>
                                            </div>
                                            <div class="container-login100-form-btn">
                                                <a href="dashboard.html" class="login100-form-btn btn-primary">
                                                        Login
                                                </a>
                                            </div>
                                            
                                        </div>
                                        <div class="tab-pane" id="tab6">
                                            <div id="mobile-num" class="wrap-input100 validate-input input-group mb-4">
                                                <a href="#" class="input-group-text bg-white text-muted">
                                                    <span>+91</span>
                                                </a>
                                                <input class="input100 form-control">
                                            </div>
                                            <div id="login-otp" class="justify-content-around mb-5">
                                                <input class="form-control text-center w-15" id="txt1" maxlength="1">
                                                <input class="form-control text-center w-15" id="txt2" maxlength="1">
                                                <input class="form-control text-center w-15" id="txt3" maxlength="1">
                                                <input class="form-control text-center w-15" id="txt4" maxlength="1">
                                            </div>
                                            <span>Note : Login with registered mobile number to generate OTP.</span>
                                            <div class="container-login100-form-btn ">
                                                <a href="#" class="login100-form-btn btn-primary" id="generate-otp">
                                                    Proceed
                                                </a>
                                            </div>
                                        </div>
                                        <br><hr>
                                            <div class="col-md-12 col-sm-12 text-center">
                                                Powered by <a href="#"> CarrierLAB </a>
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

@endsection