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
                    <form method="POST" action="{{ route('password.confirm') }}" class="login100-form validate-form">
                        @csrf

                        <span class="login100-form-title pb-5">
                            {{ __('Confirm Password') }}
                        </span>

                        <!-- Password -->
                        <div>
                            <label for="password">Password</label>
                            <input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password">
                            @if ($errors->has('password'))
                                <p class="text-sm text-red-600">{{ $errors->first('password') }}</p>
                            @endif
                        </div>

                        <div class="flex justify-end mt-4">
                            <button type="submit" class="login100-form-btn btn-primary">
                                {{ __('Confirm') }}
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
