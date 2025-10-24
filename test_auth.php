<?php
// Simple test script to diagnose authentication issues

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);

try {
    // Create a test request for the dashboard
    $request = \Illuminate\Http\Request::create('/dashboard', 'GET');
    $response = $kernel->handle($request);

    echo "Status Code: " . $response->getStatusCode() . "\n";

    if ($response->getStatusCode() === 500) {
        echo "500 Error detected. Checking logs...\n";
        $logFile = 'storage/logs/laravel.log';
        if (file_exists($logFile)) {
            $logs = file_get_contents($logFile);
            $lines = explode("\n", $logs);
            echo "Last 50 log lines:\n";
            echo implode("\n", array_slice($lines, -50));
        } else {
            echo "No log file found!\n";
        }
    } else {
        echo "Response OK\n";
    }
} catch (\Exception $e) {
    echo "Exception caught: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Stack trace:\n";
    echo $e->getTraceAsString();
}
