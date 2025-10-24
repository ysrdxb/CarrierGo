<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/vendor/autoload.php';

try {
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
    $request = \Illuminate\Http\Request::create('/', 'GET');
    $response = $kernel->handle($request);

    echo "Status: " . $response->getStatusCode() . "\n";

    if ($response->getStatusCode() !== 200) {
        echo "\nError Response:\n";
        $content = $response->getContent();
        // Find the error message
        if (preg_match('/SQLSTATE\[.*?\]:(.*?)(?=\n|<|$)/s', $content, $matches)) {
            echo "DB Error: " . trim($matches[1]) . "\n";
        }
        if (preg_match('/<h1[^>]*>(.*?)<\/h1>/s', $content, $matches)) {
            echo "Error: " . trim($matches[1]) . "\n";
        }
    } else {
        echo "✓ Homepage loaded successfully!\n";
    }

} catch (\Throwable $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
