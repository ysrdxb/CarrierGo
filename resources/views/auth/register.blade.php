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
                    <form method="POST" action="{{ route('register') }}" class="login100-form validate-form">
                        @csrf
                        <span class="login100-form-title pb-5">
                            {{ __('Register') }}
                        </span>

                        <div>
                            <label for="name" class="text-sm text-gray-600 dark:text-gray-400">{{ __('First Name') }}</label>
                            <input id="name" class="input100 form-control" type="text" name="firstname" value="{{ old('firstname') }}" required autofocus autocomplete="firstname">
                            @if ($errors->has('firstname'))
                                <p class="text-sm text-red-600">{{ $errors->first('firstname') }}</p>
                            @endif
                        </div>

                        <div class="mt-4">
                            <label for="name" class="text-sm text-gray-600 dark:text-gray-400">{{ __('Last Name') }}</label>
                            <input id="name" class="input100 form-control" type="text" name="lastname" value="{{ old('lastname') }}" required autofocus autocomplete="lastname">
                            @if ($errors->has('lastname'))
                                <p class="text-sm text-red-600">{{ $errors->first('lastname') }}</p>
                            @endif
                        </div>

                        <div class="mt-4">
                            <label for="email" class="text-sm text-gray-600 dark:text-gray-400">{{ __('Email') }}</label>
                            <input id="email" class="input100 form-control" type="email" name="email" value="{{ old('email') }}" required autocomplete="username">
                            @if ($errors->has('email'))
                                <p class="text-sm text-danger">{{ $errors->first('email') }}</p>
                            @endif
                        </div>

                        <div class="mt-4">
                            <label for="password" class="text-sm text-gray-600 dark:text-gray-400">{{ __('Password') }}</label>
                            <input id="password" class="input100 form-control" type="password" name="password" required autocomplete="new-password">
                            @if ($errors->has('password'))
                                <p class="text-sm text-danger">{{ $errors->first('password') }}</p>
                            @endif
                        </div>

                        <div class="mt-4">
                            <label for="password_confirmation" class="text-sm text-gray-600 dark:text-gray-400">{{ __('Confirm Password') }}</label>
                            <input id="password_confirmation" class="input100 form-control" type="password" name="password_confirmation" required autocomplete="new-password">
                            @if ($errors->has('password_confirmation'))
                                <p class="text-sm text-danger">{{ $errors->first('password_confirmation') }}</p>
                            @endif
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('login') }}" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                                {{ __('Already registered?') }}
                            </a>

                            <button type="submit" class="login100-form-btn btn-primary">
                                {{ __('Register') }}
                            </button>
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
