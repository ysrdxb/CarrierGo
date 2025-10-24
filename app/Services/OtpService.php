<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;

class OtpService
{
    /**
     * Generate a random OTP.
     *
     * @return int
     */
    public function generateOTP()
    {
        return $otp = rand(100000, 999999);
        $hashOtp = Hash::make($otp);
        return $hashOtp;
    }

    /**
     * Send OTP to the specified email address.
     *
     * @param string $email
     * @param int $otp
     * @return void
     */
    public function sendOTP($email, $otp)
    {
        Mail::raw('Your OTP is: ' . $otp, function ($message) use ($email) {
            $message->to($email)->subject('OTP Verification');
        });
    }
}
