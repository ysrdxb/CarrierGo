<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Get all permissions
        $allPermissions = Permission::all()->pluck('name')->toArray();

        // Super Admin - Gets ALL permissions
        $superAdminRole = Role::where('name', 'Super Admin')->first();
        if ($superAdminRole) {
            $superAdminRole->syncPermissions($allPermissions);
        }

        // Admin - Gets most permissions except some system-level ones
        $adminRole = Role::where('name', 'Admin')->first();
        if ($adminRole) {
            $adminPermissions = [
                'manage_user',
                'edit_user',
                'delete_user',
                'view_user',
                'manage_roles',
                'create_role',
                'edit_role',
                'delete_role',
                'view_role',
                'manage_permission',
                'create_permission',
                'edit_permission',
                'delete_permission',
                'view_permission',
                'create_reference',
                'edit_reference',
                'delete_reference',
                'view_reference',
                'view_all_references',
                'create_invoice',
                'edit_invoice',
                'delete_invoice',
                'view_invoice',
                'view_all_invoices',
                'download_invoice',
                'view_shipment',
                'track_shipment',
                'manage_shipment',
                'create_transport_order',
                'edit_transport_order',
                'delete_transport_order',
                'manage_settings',
                'manage_bank_details',
                'view_reports',
                'manage_languages',
                'manage_freight_types',
                'manage_destinations',
                'download_document',
                'send_document',
                'manage_documents',
                'manage_employees',
                'view_employees',
                'create_incoming_invoice',
                'edit_incoming_invoice',
                'delete_incoming_invoice',
                'view_incoming_invoice',
                'view_all_incoming_invoices',
            ];
            $adminRole->syncPermissions($adminPermissions);
        }

        // Supervisor - Can view and manage most content but not system settings
        $supervisorRole = Role::where('name', 'Supervisor')->first();
        if ($supervisorRole) {
            $supervisorPermissions = [
                'view_user',
                'view_role',
                'view_permission',
                'create_reference',
                'edit_reference',
                'view_reference',
                'view_all_references',
                'create_invoice',
                'edit_invoice',
                'view_invoice',
                'view_all_invoices',
                'download_invoice',
                'view_shipment',
                'track_shipment',
                'create_transport_order',
                'edit_transport_order',
                'view_reports',
                'download_document',
                'send_document',
                'view_employees',
                'view_incoming_invoice',
                'view_all_incoming_invoices',
            ];
            $supervisorRole->syncPermissions($supervisorPermissions);
        }

        // Employee - Basic permissions for daily work
        $employeeRole = Role::where('name', 'Employee')->first();
        if ($employeeRole) {
            $employeePermissions = [
                'create_reference',
                'edit_reference',
                'view_reference',
                'create_invoice',
                'edit_invoice',
                'view_invoice',
                'download_invoice',
                'view_shipment',
                'track_shipment',
                'download_document',
                'view_incoming_invoice',
            ];
            $employeeRole->syncPermissions($employeePermissions);
        }
    }
}
