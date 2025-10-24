<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load composer autoloader first
require_once __DIR__ . '/vendor/autoload.php';

try {
    // Load the application
    $app = require_once __DIR__ . '/bootstrap/app.php';

    $kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);

    // Create a test request
    $request = \Illuminate\Http\Request::create('/', 'GET');

    // Handle the request
    $response = $kernel->handle($request);

    // Output the response
    echo "Status Code: " . $response->getStatusCode() . "\n";
    echo "Content Length: " . strlen($response->getContent()) . "\n";

    if ($response->getStatusCode() !== 200) {
        echo "\nResponse Content:\n";
        echo $response->getContent();
    }

} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "\nFull Trace:\n";
    echo $e->getTraceAsString();
}
