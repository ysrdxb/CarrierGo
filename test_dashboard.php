<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

// Set up a fake authenticated user in the session
session_start();

try {
    // Try to boot the application
    $kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
    echo "Kernel created successfully\n";
    
    // Try to create a dashboard request
    $request = \Illuminate\Http\Request::create('/dashboard', 'GET');
    echo "Request created\n";
    
    // Process the request
    $response = $kernel->handle($request);
    echo "Response status: " . $response->getStatusCode() . "\n";
    
    if ($response->getStatusCode() >= 500) {
        echo "ERROR DETECTED\n";
        echo $response->getContent();
    }
} catch (\Throwable $e) {
    echo "EXCEPTION: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Trace:\n";
    echo $e->getTraceAsString();
}
?>
