<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Super Admin role
        Role::create(['name' => 'Super Admin', 'guard_name' => 'web']);

        // Create Admin role
        Role::create(['name' => 'Admin', 'guard_name' => 'web']);

        // Create Supervisor role
        Role::create(['name' => 'Supervisor', 'guard_name' => 'web']);

        // Create Employee role
        Role::create(['name' => 'Employee', 'guard_name' => 'web']);
    }
}
