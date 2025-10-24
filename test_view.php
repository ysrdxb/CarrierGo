<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/vendor/autoload.php';

try {
    $app = require_once __DIR__ . '/bootstrap/app.php';

    // Get first user
    $user = \App\Models\User::first();

    if (!$user) {
        echo "No users found\n";
        exit(1);
    }

    // Authenticate the user
    auth()->setUser($user);

    // Set tenant context manually (simulating middleware)
    app()->instance('tenant_id', $user->tenant_id);

    // Get data for admin dashboard
    if ($user->hasRole('Admin')) {
        echo "Testing admin dashboard\n";

        $reference_numbers = \App\Models\ReferenceNumber::all();
        echo "Reference numbers: " . $reference_numbers->count() . "\n";

        $query = \App\Models\Reference::query();
        $options = [];

        foreach ($reference_numbers as $reference_number) {
            $start = intval($reference_number->number_range);
            $lastUsedReferenceParts = explode('-', $reference_number->last_used_reference);

            if (count($lastUsedReferenceParts) != 2) {
                continue;
            }

            $end = intval($lastUsedReferenceParts[0]);
            $year = intval($lastUsedReferenceParts[1]);

            $currentYear = date('Y');
            $currentYear = substr($currentYear, -2);
            if ($year != $currentYear) {
                $end = $start - 1;
                $year = $currentYear;
            }

            for ($i = $start; $i <= $end; $i++) {
                $optionLabel = $i;
                $optionValue = $i . '-' . $year;
                $options[$optionValue] = $optionLabel;
            }
        }

        $carrierTotalSum = $query->sum('carrier_fees');
        echo "Carrier total sum: $carrierTotalSum\n";

        // Try to render the view
        $view = view('dashboard', compact('options', 'carrierTotalSum'));
        echo "View rendered successfully\n";
        echo "View content length: " . strlen($view) . "\n";

    } else {
        echo "Testing customer dashboard\n";
        $view = view('customer_dashboard');
        echo "View rendered successfully\n";
        echo "View content length: " . strlen($view) . "\n";
    }

} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "\nTrace:\n";
    echo $e->getTraceAsString();
}
