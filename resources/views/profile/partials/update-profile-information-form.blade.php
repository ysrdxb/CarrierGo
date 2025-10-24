<div class="col-xl-8">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('Profile Information') }}</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('profile.update') }}" method="post">
              @csrf
              @method('patch')
                <div class="row">
                    <div class="col-lg-6 col-md-12">
                        <div class="form-group">
                            <label for="exampleInputname">First Name</label>
                            <input type="text" class="form-control" name="firstname" value="{{ old('firstname', $user->firstname) }}" id="firstname" placeholder="First Name">
                            @error('firstname')
                                <div class="text text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <div class="form-group">
                            <label for="exampleInputname1">Last Name</label>
                            <input type="text" name="lastname" class="form-control" value="{{ old('lastname', $user->lastname) }}" id="lastname" placeholder="Enter Last Name" required>
                            @error('lastname')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Email address</label>
                    <input type="email" class="form-control" placeholder="Email address" value="{{ old('email', $user->email) }}" name="email" id="email" required>
                    @error('email')
                        <div class="text text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="exampleInputnumber">Mobile Number</label>
                    <input type="number" class="form-control" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" placeholder="Contact number">
                    @error('phone')
                        <div class="text text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="card-footer text-end">
                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div>
                        <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                            {{ __('Your email address is unverified.') }}

                            <button form="send-verification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                                {{ __('Click here to re-send the verification email.') }}
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </p>
                        @endif
                    </div>
                @endif
                <button type="submit" class="btn btn-success my-1">Save</button>
                <a href="#" class="btn btn-danger my-1">Cancel</a>

                @if (session('status') === 'profile-updated')
                    <div class="text-success mt-2" role="alert">
                        {{ __('Profile Information Saved.') }}
                    </div>
                @endif

            </div>
        </form>
    </div>
</div>
