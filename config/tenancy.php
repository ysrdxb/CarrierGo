<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Tenancy Mode Configuration
    |--------------------------------------------------------------------------
    |
    | Configure the multi-tenancy mode for your application.
    | 
    | Modes:
    | - 'shared': All tenants share a central database (lower cost, easier setup)
    | - 'separate': Each tenant gets their own database (better isolation, performance)
    |
    */
    'default_mode' => env('TENANCY_MODE', 'SHARED'),

    /*
    |--------------------------------------------------------------------------
    | Enable Separate Database Mode
    |--------------------------------------------------------------------------
    |
    | Set to true to allow admins to switch tenants to separate database mode.
    | This feature is locked (disabled) by default and can be activated later.
    |
    */
    'separate_mode_enabled' => env('TENANCY_SEPARATE_ENABLED', false),

    /*
    |--------------------------------------------------------------------------
    | Shared Database Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for shared database mode (SHARED).
    | All tenants share the central database with isolation by tenant_id column.
    |
    */
    'shared' => [
        'connection' => 'mysql',
        'database' => env('DB_DATABASE', 'carriergo_shared'),
        'isolation_method' => 'tenant_id', // Data isolation via tenant_id column
    ],

    /*
    |--------------------------------------------------------------------------
    | Separate Database Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for separate database mode (SEPARATE).
    | Each tenant gets their own dedicated database.
    | This is locked by default and can be activated in admin settings.
    |
    */
    'separate' => [
        'connection' => 'mysql',
        'database_prefix' => 'carriergo_tenant_',
        'host' => env('DB_HOST', 'localhost'),
        'port' => env('DB_PORT', 3306),
        'username' => env('DB_USERNAME'),
        'password' => env('DB_PASSWORD'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Strategy
    |--------------------------------------------------------------------------
    |
    | Define how tenants can be migrated between modes.
    |
    */
    'migration' => [
        // Allow migrating from SHARED to SEPARATE
        'shared_to_separate' => env('TENANCY_ALLOW_MIGRATION', false),
        
        // Reverse migration (SEPARATE to SHARED) - rarely used
        'separate_to_shared' => false,
    ],
];
