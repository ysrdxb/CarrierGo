<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // User Management Permissions
        Permission::create(['name' => 'manage_user', 'guard_name' => 'web']);
        Permission::create(['name' => 'edit_user', 'guard_name' => 'web']);
        Permission::create(['name' => 'delete_user', 'guard_name' => 'web']);
        Permission::create(['name' => 'view_user', 'guard_name' => 'web']);

        // Role Management Permissions
        Permission::create(['name' => 'manage_roles', 'guard_name' => 'web']);
        Permission::create(['name' => 'create_role', 'guard_name' => 'web']);
        Permission::create(['name' => 'edit_role', 'guard_name' => 'web']);
        Permission::create(['name' => 'delete_role', 'guard_name' => 'web']);
        Permission::create(['name' => 'view_role', 'guard_name' => 'web']);

        // Permission Management
        Permission::create(['name' => 'manage_permission', 'guard_name' => 'web']);
        Permission::create(['name' => 'create_permission', 'guard_name' => 'web']);
        Permission::create(['name' => 'edit_permission', 'guard_name' => 'web']);
        Permission::create(['name' => 'delete_permission', 'guard_name' => 'web']);
        Permission::create(['name' => 'view_permission', 'guard_name' => 'web']);

        // Reference Management
        Permission::create(['name' => 'create_reference', 'guard_name' => 'web']);
        Permission::create(['name' => 'edit_reference', 'guard_name' => 'web']);
        Permission::create(['name' => 'delete_reference', 'guard_name' => 'web']);
        Permission::create(['name' => 'view_reference', 'guard_name' => 'web']);
        Permission::create(['name' => 'view_all_references', 'guard_name' => 'web']);

        // Invoice Management
        Permission::create(['name' => 'create_invoice', 'guard_name' => 'web']);
        Permission::create(['name' => 'edit_invoice', 'guard_name' => 'web']);
        Permission::create(['name' => 'delete_invoice', 'guard_name' => 'web']);
        Permission::create(['name' => 'view_invoice', 'guard_name' => 'web']);
        Permission::create(['name' => 'view_all_invoices', 'guard_name' => 'web']);
        Permission::create(['name' => 'download_invoice', 'guard_name' => 'web']);

        // Shipment/Transport Management
        Permission::create(['name' => 'view_shipment', 'guard_name' => 'web']);
        Permission::create(['name' => 'track_shipment', 'guard_name' => 'web']);
        Permission::create(['name' => 'manage_shipment', 'guard_name' => 'web']);
        Permission::create(['name' => 'create_transport_order', 'guard_name' => 'web']);
        Permission::create(['name' => 'edit_transport_order', 'guard_name' => 'web']);
        Permission::create(['name' => 'delete_transport_order', 'guard_name' => 'web']);

        // System Settings
        Permission::create(['name' => 'manage_settings', 'guard_name' => 'web']);
        Permission::create(['name' => 'manage_bank_details', 'guard_name' => 'web']);
        Permission::create(['name' => 'view_reports', 'guard_name' => 'web']);
        Permission::create(['name' => 'manage_languages', 'guard_name' => 'web']);
        Permission::create(['name' => 'manage_freight_types', 'guard_name' => 'web']);
        Permission::create(['name' => 'manage_destinations', 'guard_name' => 'web']);

        // Document Management
        Permission::create(['name' => 'download_document', 'guard_name' => 'web']);
        Permission::create(['name' => 'send_document', 'guard_name' => 'web']);
        Permission::create(['name' => 'manage_documents', 'guard_name' => 'web']);

        // Employee Management
        Permission::create(['name' => 'manage_employees', 'guard_name' => 'web']);
        Permission::create(['name' => 'view_employees', 'guard_name' => 'web']);

        // Incoming Invoice Management
        Permission::create(['name' => 'create_incoming_invoice', 'guard_name' => 'web']);
        Permission::create(['name' => 'edit_incoming_invoice', 'guard_name' => 'web']);
        Permission::create(['name' => 'delete_incoming_invoice', 'guard_name' => 'web']);
        Permission::create(['name' => 'view_incoming_invoice', 'guard_name' => 'web']);
        Permission::create(['name' => 'view_all_incoming_invoices', 'guard_name' => 'web']);
    }
}
