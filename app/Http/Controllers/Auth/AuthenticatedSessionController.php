<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\User;
use App\Services\SettingService;
use App\Services\OtpService;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AuthenticatedSessionController extends Controller
{
    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    public function create(): View
    {
        // dd(Hash::make('111'));
        return view('auth.login');
    }

    public function store(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');        
        $user = User::where('email', $request->email)->first();
    
        if ($user && Hash::check($request->password, $user->password)) {
            // Check if the end_date is not null and is less than today's date
            if ($user->end_date && Carbon::parse($user->end_date)->lessThan(Carbon::now())) {
                return back()->withErrors([
                    'email' => 'You cannot log in as your account has expired.',
                ]);
            }
    
            // Check if the start_date is in the future
            if ($user->start_date && Carbon::parse($user->start_date)->greaterThan(Carbon::now())) {
                return back()->withErrors([
                    'email' => 'You cannot access your account before ' . Carbon::parse($user->start_date)->format('d-m-Y') . '.',
                ]);
            }
    
            // if ($user->hasRole('Admin')) {
            //     // User is an admin, just log them in
            //     SettingService::fillSession();
            //     Auth::login($user);
            //     return redirect()->route('dashboard.index')->with([
            //         'success' => 'Logged in successfully!',
            //     ]);
            // }
    
            // User is not an admin, generate OTP (displayed on next page, no email)
            $request->session()->put('user_id', $user->id);
            $user->otp = $this->otpService->generateOTP();
            $user->otp_expiry = time() + 6 * 10;
            $user->save();

            return redirect()->route('otp.verify')->with([
                'success' => 'OTP has been generated. Check the verification page.',
                'otp' => $user->otp,
            ]);
        }
    
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }
          

    public function showEmailCodeForm()
    {
        return view('auth.verify-email-code');
    }

    public function resendEmailCode(Request $request)
    {
        $user = User::findOrFail($request->session()->get('user_id'));
        $user->otp = $this->otpService->generateOTP();
        $user->otp_expiry = time() + 6 * 10;
        $user->save();

        return back()->with([
            'success' => 'OTP Code regenerated successfully!',
            'otp' => $user->otp,
        ]);
    }

    public function verifyEmailCode(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        $user = User::findOrFail($request->session()->get('user_id'));

        if (!$user || !$user->otp) {
            return back()->withErrors([
                'error' => 'No OTP Code set for this user.',
            ]);
        }

        if ($request->otp == $user->otp) {
            if(time() <= $user->otp_expiry) {
                $request->session()->forget('user_id');
                Auth::login($user);
                SettingService::fillSession();

                // Redirect based on user role
                if ($user->hasRole('Super Admin') || $user->hasRole('Admin')) {
                    // Admin user - redirect to admin dashboard
                    return redirect()->route('dashboard.index')->with('success', 'Welcome back, Admin!');
                } else {
                    // Regular user - redirect to user dashboard
                    return redirect()->route('user.reference')->with('success', 'Welcome back!');
                }
            } else {
                return back()->withErrors([
                    'error' => 'OTP Code has expired.',
                ]);
            }
        }

        return back()->withErrors([
            'error' => 'OTP Code entered is invalid.',
        ]);
    }


    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
