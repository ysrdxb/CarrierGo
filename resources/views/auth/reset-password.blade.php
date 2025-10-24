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
                    <form method="POST" action="{{ route('password.update') }}" class="login100-form validate-form">
                        @csrf

                        <!-- Password Reset Token -->
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        <span class="login100-form-title pb-5">
                            {{ __('Reset Password') }}
                        </span>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row">
                            <!-- Email Address -->
                            <div class="mt-4 form-group col-md-12">
                                <label for="email">Email</label>
                                <input id="email" readonly class="form-control" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus>
                                @error('email')
                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="mt-2 form-group col-md-12">
                                <label for="password">Password</label>
                                <input id="password" class="form-control" type="password" name="password" required>
                                @error('password')
                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div class="mt-2 form-group col-md-12">
                                <label for="password_confirmation">Confirm Password</label>
                                <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required>
                                @error('password_confirmation')
                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="login100-form-btn btn-primary">
                                {{ __('Reset Password') }}
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
