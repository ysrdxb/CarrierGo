<?php

// Test if the homepage can be accessed
try {
    require_once __DIR__ . '/bootstrap/app.php';

    $app = require_once __DIR__ . '/bootstrap/app.php';
    $kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);

    $request = \Illuminate\Http\Request::create('/', 'GET');
    $response = $kernel->handle($request);

    echo "Status: " . $response->getStatusCode() . "\n";
    echo "Response length: " . strlen($response->getContent()) . "\n";

    if ($response->getStatusCode() !== 200) {
        echo "Error response:\n";
        echo $response->getContent() . "\n";
    }
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}
