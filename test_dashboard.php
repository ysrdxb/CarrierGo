<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/vendor/autoload.php';

try {
    $app = require_once __DIR__ . '/bootstrap/app.php';

    $kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);

    // Create a test request
    $request = \Illuminate\Http\Request::create('/dashboard', 'GET');

    // Get the first user to test with
    $user = \App\Models\User::first();

    if (!$user) {
        echo "No users found in database\n";
        exit(1);
    }

    echo "Testing with user: " . $user->email . " (tenant_id: " . $user->tenant_id . ")\n";

    // Authenticate the user
    auth()->setUser($user);

    // Handle the request
    $response = $kernel->handle($request);

    echo "Status Code: " . $response->getStatusCode() . "\n";
    echo "Content Length: " . strlen($response->getContent()) . "\n";

    if ($response->getStatusCode() !== 200) {
        echo "\nResponse (first 5000 chars):\n";
        echo substr($response->getContent(), 0, 5000);
    }

} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "\nFull Trace:\n";
    echo $e->getTraceAsString();
}
