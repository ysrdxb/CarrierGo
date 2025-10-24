<div class="col-xl-4">
    <div class="card">
        <div class="card-header">
            <div class="card-title">{{ __('Update Password') }}</div>
        </div>
        <div class="card-body">
            <div class="text-center chat-image mb-5">
                <div class="avatar avatar-xxl chat-profile mb-3 brround">
                    <a class="" href="javascript:;"><img alt="avatar" src="{{ asset('admin/images/users/7.jpg') }}" class="brround"></a>
                </div>
                <div class="main-chat-msg-name">
                    <a href="profile.html">
                        <h5 class="mb-1 text-dark fw-semibold">{{ $user->firstname . ' ' . $user->lastname }}</h5>
                    </a>
                    <p class="text-muted mt-0 mb-0 pt-0 fs-13">Admin</p>
                </div>
            </div>
            <form method="post" action="{{ route('password.update') }}" class="space-y-6">
                @csrf
                @method('put')

                <div class="form-group">
                    <label for="current_password" class="form-label">Current Password</label>
                    <input id="current_password" name="current_password" type="password" class="form-control" placeholder="Current Password">
                    @if ($errors->updatePassword->has('current_password'))
                        <div class="text-danger mt-2">
                            {{ $errors->updatePassword->first('current_password') }}
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">New Password</label>
                    <input id="password" name="password" type="password" class="form-control" placeholder="New Password">
                    @if ($errors->updatePassword->has('password'))
                        <div class="text-danger mt-2">
                            {{ $errors->updatePassword->first('password') }}
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" placeholder="Confirm Password">
                    @if ($errors->updatePassword->has('password_confirmation'))
                        <div class="text-danger mt-2">
                            {{ $errors->updatePassword->first('password_confirmation') }}
                        </div>
                    @endif

                </div>
        </div>
        <div class="card-footer text-end">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="#" class="btn btn-danger">Cancel</a>

            @if (session('status') === 'password-updated')
                <div class="text-success mt-2" role="alert">
                    {{ __('Password Changed Successfully.') }}
                </div>
            @endif

        </div>
        </form>
    </div>
</div>
