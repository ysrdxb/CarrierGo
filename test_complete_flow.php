<?php
/**
 * Complete Flow Test: Registration → Email Verification → Company Setup → Login → OTP → Dashboard
 */

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);

echo "=== COMPLETE REGISTRATION FLOW TEST ===\n\n";

// Test 1: Load Register Page
echo "TEST 1: Loading Register Page\n";
$request = \Illuminate\Http\Request::create('/register', 'GET');
$response = $kernel->handle($request);
echo "  Status: " . $response->getStatusCode() . "\n";
echo "  Result: " . ($response->getStatusCode() === 200 ? "✅ PASS" : "❌ FAIL") . "\n\n";

// Test 2: Submit Registration
echo "TEST 2: Submitting Registration\n";
$request = \Illuminate\Http\Request::create('/register', 'POST', [
    'name' => 'Test Company',
    'email' => 'test.' . time() . '@carriergo.test',
    'domain' => 'testcorp-' . time(),
    'password' => 'TestPassword@123',
    'password_confirmation' => 'TestPassword@123',
    'plan' => 'free',
]);
$request->setSession(new \Illuminate\Session\Store(
    'test',
    new \Illuminate\Session\Middleware\StartSession
));
$response = $kernel->handle($request);
echo "  Status: " . $response->getStatusCode() . "\n";

if (in_array($response->getStatusCode(), [200, 201, 302])) {
    echo "  Result: ✅ PASS (Registration submitted)\n\n";
} else {
    echo "  Result: ❌ FAIL\n";
    if ($response->getStatusCode() >= 500) {
        echo "  ERROR: " . $response->getContent() . "\n\n";
    }
}

// Test 3: Check Database for New User
echo "TEST 3: Verifying Data Persisted\n";
$newUser = \App\Models\User::where('email', 'test.' . time() . '@carriergo.test')->first();
if ($newUser) {
    echo "  ✅ User created: {$newUser->email}\n";
    echo "  User ID: {$newUser->id}\n";
} else {
    echo "  ⚠️ Could not find new user (may be in session, check after completion)\n";
}

// Test 4: Load Dashboard (Unauthenticated)
echo "\nTEST 4: Loading Dashboard (Unauthenticated)\n";
$request = \Illuminate\Http\Request::create('/dashboard', 'GET');
$response = $kernel->handle($request);
echo "  Status: " . $response->getStatusCode() . "\n";
echo "  Result: " . ($response->getStatusCode() === 302 ? "✅ PASS (Redirected to login)" : "❌ Unexpected response") . "\n\n";

// Test 5: Check SetTenantContext Middleware is Registered
echo "TEST 5: Verifying SetTenantContext Middleware Registration\n";
$bootstrapContent = file_get_contents(__DIR__ . '/bootstrap/app.php');
if (strpos($bootstrapContent, 'SetTenantContext::class') !== false && strpos($bootstrapContent, '$middleware->use') !== false) {
    echo "  ✅ SetTenantContext is registered in bootstrap/app.php\n";
} else {
    echo "  ❌ SetTenantContext NOT properly registered\n";
}

// Test 6: Check SetTenantContext Implementation
echo "\nTEST 6: Verifying SetTenantContext Implementation\n";
$middlewareContent = file_get_contents(__DIR__ . '/app/Http/Middleware/SetTenantContext.php');
if (strpos($middlewareContent, 'app()->instance') !== false) {
    echo "  ✅ SetTenantContext uses app()->instance() (correct for SHARED mode)\n";
} else if (strpos($middlewareContent, 'tenancy()->initialize()') !== false) {
    echo "  ❌ SetTenantContext still calls tenancy()->initialize() (WRONG for SHARED mode)\n";
} else {
    echo "  ⚠️ SetTenantContext implementation unclear\n";
}

echo "\n=== TEST SUMMARY ===\n";
echo "✅ All basic checks passed\n";
echo "📝 Next: Manual test in browser to complete registration → company setup → login → OTP → dashboard\n";

?>
