<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\Tenant;
use App\Jobs\ProvisionTenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RegistrationController extends Controller
{
    /**
     * Handle email verification with token
     */
    public function verifyEmail(string $token)
    {
        try {
            // Find registration by verification token
            $registration = Registration::where("verification_token", $token)
                ->where("status", "pending")
                ->first();

            if (!$registration) {
                return redirect("/login")->with("error", "Invalid or expired verification link.");
            }

            // Check if token has expired
            if ($registration->isVerificationTokenExpired()) {
                return redirect("/login")->with("error", "Verification link has expired. Please register again.");
            }

            // Mark as verified
            $registration->markAsVerified();

            Log::info("Registration verified", ["registration_id" => $registration->id]);

            // Determine next action based on subscription plan
            if ($registration->canAutoProvision()) {
                return $this->autoProvisionFreeAccount($registration);
            } else {
                return redirect("/registration/pending")
                    ->with("registration_id", $registration->id)
                    ->with("message", "Your registration has been verified! Our team will review and approve it shortly.");
            }

        } catch (\Exception $e) {
            Log::error("Email verification error: " . $e->getMessage());
            return redirect("/login")->with("error", "An error occurred during verification. Please try again.");
        }
    }

    /**
     * Redirect to company setup page for all accounts
     * Tenant will be created during company setup
     */
    private function autoProvisionFreeAccount(Registration $registration)
    {
        try {
            // Don't change status - keep it as 'verified' so CompanySetup can access it
            // The company setup will mark it as 'completed' after creating tenant

            Log::info("Email verified, redirecting to company setup", ["registration_id" => $registration->id]);

            // Redirect to company setup form instead of auto-provisioning
            return redirect()->route('company-setup', ['registration_id' => $registration->id])
                ->with("success", "Email verified! Now please tell us about your company.");

        } catch (\Exception $e) {
            Log::error("Verification error: " . $e->getMessage());
            return redirect("/login")->with("error", "An error occurred. Please try again.");
        }
    }

    /**
     * Show pending approval page for paid tier
     */
    public function showPendingApproval(Request $request)
    {
        $registrationId = $request->query("registration_id");

        if (!$registrationId) {
            return redirect("/login");
        }

        $registration = Registration::findOrFail($registrationId);

        return view("auth.pending-approval", [
            "registration" => $registration,
        ]);
    }
}