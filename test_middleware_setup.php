<?php
/**
 * Verify SetTenantContext Middleware Setup
 */

echo "=== MIDDLEWARE SETUP VERIFICATION ===\n\n";

// Test 1: Check bootstrap/app.php has SetTenantContext imported
echo "TEST 1: SetTenantContext Import in bootstrap/app.php\n";
$bootstrapContent = file_get_contents(__DIR__ . '/bootstrap/app.php');
if (preg_match('/use\s+App\\Http\\Middleware\\SetTenantContext/', $bootstrapContent)) {
    echo "  ✅ PASS: SetTenantContext is imported\n";
} else {
    echo "  ❌ FAIL: SetTenantContext NOT imported\n";
}

// Test 2: Check SetTenantContext is registered in middleware->use()
echo "\nTEST 2: SetTenantContext Global Middleware Registration\n";
if (preg_match('/\$middleware->use\(\[[\s\n]*SetTenantContext::class/', $bootstrapContent)) {
    echo "  ✅ PASS: SetTenantContext is registered in \$middleware->use()\n";
} else {
    echo "  ❌ FAIL: SetTenantContext NOT in \$middleware->use()\n";
    echo "  Checking for registration in bootstrap/app.php...\n";
    if (strpos($bootstrapContent, 'SetTenantContext::class') !== false) {
        echo "  ⚠️  WARNING: SetTenantContext mentioned but may not be in correct place\n";
    }
}

// Test 3: Check SetTenantContext Implementation
echo "\nTEST 3: SetTenantContext Implementation (Must use app()->instance)\n";
$middlewareContent = file_get_contents(__DIR__ . '/app/Http/Middleware/SetTenantContext.php');

$hasAppInstance = strpos($middlewareContent, "app()->instance('tenant_id'") !== false;
$hasTenancyInitialize = strpos($middlewareContent, 'tenancy()->initialize()') !== false;
$hasAppForgetInstance = strpos($middlewareContent, "app()->forgetInstance('tenant_id')") !== false;

if ($hasAppInstance && !$hasTenancyInitialize) {
    echo "  ✅ PASS: Uses app()->instance() (correct for SHARED mode)\n";
    echo "  ✅ Does NOT call tenancy()->initialize() (correct)\n";
} else if ($hasTenancyInitialize) {
    echo "  ❌ FAIL: Calls tenancy()->initialize() (WRONG - package is disabled)\n";
} else {
    echo "  ❌ FAIL: Implementation unclear\n";
}

if ($hasAppForgetInstance) {
    echo "  ✅ Clears tenant_id on request (good for isolation)\n";
}

// Test 4: Check Database Session Issues
echo "\nTEST 4: Session Configuration Check\n";
$envContent = file_get_contents(__DIR__ . '/.env');
$configContent = file_get_contents(__DIR__ . '/config/session.php');

if (preg_match('/SESSION_DRIVER=file/', $envContent)) {
    echo "  ✅ PASS: SESSION_DRIVER=file (not database)\n";
} else {
    echo "  ❌ FAIL: SESSION_DRIVER not set to file\n";
}

if (preg_match('/CACHE_STORE=array/', $envContent)) {
    echo "  ✅ PASS: CACHE_STORE=array (not database)\n";
} else {
    echo "  ❌ FAIL: CACHE_STORE not set to array\n";
}

// Test 5: Database Connection Test
echo "\nTEST 5: Database Connection Test\n";
try {
    require 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $connection = $app->make('db');
    $connection->getPdo();
    echo "  ✅ PASS: Database connection working\n";
} catch (\Exception $e) {
    echo "  ❌ FAIL: Database connection error\n";
    echo "  Error: " . $e->getMessage() . "\n";
}

echo "\n=== VERIFICATION COMPLETE ===\n";
echo "All middleware checks should show ✅ for proper operation\n";

?>
