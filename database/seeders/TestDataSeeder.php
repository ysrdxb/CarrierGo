<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Reference;
use App\Models\ReferenceNumber;
use App\Models\DatabaseEntry;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ===================================
        // 1. CREATE ADMIN USER (or update existing)
        // ===================================

        // Update user ID 1 to be Admin
        $adminUser = User::find(1);
        if ($adminUser) {
            $adminUser->update([
                'firstname' => 'Admin',
                'lastname' => 'User',
                'email' => 'admin@carriergo.com',
                'phone' => '+1-555-0100',
            ]);
            $adminUser->syncRoles(['Admin']);

            // Assign reference number to admin
            ReferenceNumber::updateOrCreate(
                ['user_id' => $adminUser->id],
                [
                    'number_range' => '5000',
                    'last_used_reference' => '5000',
                    'year' => date('Y'),
                ]
            );
        }

        // Create another Admin user
        $admin2 = User::updateOrCreate(
            ['email' => 'admin2@carriergo.com'],
            [
                'firstname' => 'Sarah',
                'lastname' => 'Admin',
                'phone' => '+1-555-0104',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'otp' => 111111,
                'otp_expiry' => now()->addHours(1)->toDateTimeString(),
                'image' => '',
                'start_date' => now()->subMonths(12)->toDateString(),
                'end_date' => null,
            ]
        );
        $admin2->syncRoles(['Admin']);

        // Assign reference number to admin2
        ReferenceNumber::updateOrCreate(
            ['user_id' => $admin2->id],
            [
                'number_range' => '6000',
                'last_used_reference' => '6000',
                'year' => date('Y'),
            ]
        );

        // ===================================
        // 2. SET UP EMPLOYEE ROLES & REFERENCE NUMBERS
        // ===================================

        // Update John Doe to be Super Admin with ref 5001
        $johnDoe = User::where('email', 'john.doe@carriergo.com')->first();
        if ($johnDoe) {
            $johnDoe->syncRoles(['Super Admin']);
            ReferenceNumber::updateOrCreate(
                ['user_id' => $johnDoe->id],
                [
                    'number_range' => '5001',
                    'last_used_reference' => '5001',
                    'year' => date('Y'),
                ]
            );
        }

        // Update Jane Smith to be Admin with ref 6001
        $janeSmith = User::where('email', 'jane.smith@carriergo.com')->first();
        if ($janeSmith) {
            $janeSmith->syncRoles(['Admin']);
            ReferenceNumber::updateOrCreate(
                ['user_id' => $janeSmith->id],
                [
                    'number_range' => '6001',
                    'last_used_reference' => '6001',
                    'year' => date('Y'),
                ]
            );
        }

        // Update Mike Johnson to be Supervisor with ref 3000
        $mikeJohnson = User::where('email', 'mike.johnson@carriergo.com')->first();
        if ($mikeJohnson) {
            $mikeJohnson->syncRoles(['Supervisor']);
            ReferenceNumber::updateOrCreate(
                ['user_id' => $mikeJohnson->id],
                [
                    'number_range' => '3000',
                    'last_used_reference' => '3000',
                    'year' => date('Y'),
                ]
            );
        }

        // Create Employee users with different reference ranges
        $employee1 = User::updateOrCreate(
            ['email' => 'employee1@carriergo.com'],
            [
                'firstname' => 'Robert',
                'lastname' => 'Employee',
                'phone' => '+1-555-0105',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'otp' => 222222,
                'otp_expiry' => now()->addHours(1)->toDateTimeString(),
                'image' => '',
                'start_date' => now()->subMonths(2)->toDateString(),
                'end_date' => null,
            ]
        );
        $employee1->syncRoles(['Employee']);
        ReferenceNumber::updateOrCreate(
            ['user_id' => $employee1->id],
            [
                'number_range' => '1000',
                'last_used_reference' => '1000',
                'year' => date('Y'),
            ]
        );

        $employee2 = User::updateOrCreate(
            ['email' => 'employee2@carriergo.com'],
            [
                'firstname' => 'Emily',
                'lastname' => 'Employee',
                'phone' => '+1-555-0106',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'otp' => 333333,
                'otp_expiry' => now()->addHours(1)->toDateTimeString(),
                'image' => '',
                'start_date' => now()->subMonths(4)->toDateString(),
                'end_date' => null,
            ]
        );
        $employee2->syncRoles(['Employee']);
        ReferenceNumber::updateOrCreate(
            ['user_id' => $employee2->id],
            [
                'number_range' => '2000',
                'last_used_reference' => '2000',
                'year' => date('Y'),
            ]
        );

        $employee3 = User::updateOrCreate(
            ['email' => 'employee3@carriergo.com'],
            [
                'firstname' => 'David',
                'lastname' => 'Employee',
                'phone' => '+1-555-0107',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'otp' => 444444,
                'otp_expiry' => now()->addHours(1)->toDateTimeString(),
                'image' => '',
                'start_date' => now()->subMonths(3)->toDateString(),
                'end_date' => null,
            ]
        );
        $employee3->syncRoles(['Employee']);
        ReferenceNumber::updateOrCreate(
            ['user_id' => $employee3->id],
            [
                'number_range' => '4000',
                'last_used_reference' => '4000',
                'year' => date('Y'),
            ]
        );

        // ===================================
        // 3. CREATE SAMPLE DATABASE ENTRIES (for references)
        // ===================================

        // Create sample companies/entries for references
        $client = DatabaseEntry::updateOrCreate(
            ['name' => 'Global Shipping Ltd.'],
            [
                'type' => 'client',
                'address' => '123 Port Street',
                'city' => 'New York',
                'zip_code' => '10001',
                'country' => 'USA',
                'contact_person' => 'John Smith',
                'email' => 'john@globalshipping.com',
                'phone' => '+1-555-1000',
            ]
        );

        $consignee = DatabaseEntry::updateOrCreate(
            ['name' => 'Express Logistics Inc.'],
            [
                'type' => 'consignee',
                'address' => '456 Commerce Ave',
                'city' => 'Los Angeles',
                'zip_code' => '90001',
                'country' => 'USA',
                'contact_person' => 'Jane Doe',
                'email' => 'jane@expresslogistics.com',
                'phone' => '+1-555-2000',
            ]
        );

        $merchant = DatabaseEntry::updateOrCreate(
            ['name' => 'Premium Trading Co.'],
            [
                'type' => 'merchant',
                'address' => '789 Market Street',
                'city' => 'Chicago',
                'zip_code' => '60601',
                'country' => 'USA',
                'contact_person' => 'Mike Johnson',
                'email' => 'mike@premiumtrading.com',
                'phone' => '+1-555-3000',
            ]
        );

        $agent = DatabaseEntry::updateOrCreate(
            ['name' => 'Cargo Agents USA'],
            [
                'type' => 'agent',
                'address' => '321 Transport Way',
                'city' => 'Houston',
                'zip_code' => '77001',
                'country' => 'USA',
                'contact_person' => 'Tom Brown',
                'email' => 'tom@cargoagents.com',
                'phone' => '+1-555-4000',
            ]
        );

        $carrier = DatabaseEntry::updateOrCreate(
            ['name' => 'International Carriers LLC'],
            [
                'type' => 'carrier',
                'address' => '654 Shipping Lane',
                'city' => 'Miami',
                'zip_code' => '33101',
                'country' => 'USA',
                'contact_person' => 'Lisa White',
                'email' => 'lisa@intlcarriers.com',
                'phone' => '+1-555-5000',
            ]
        );

        // ===================================
        // 4. CREATE SAMPLE REFERENCES
        // ===================================

        $currentYear = date('y');

        // Sample Reference 1 (created by Robert Employee)
        Reference::updateOrCreate(
            ['reference_no' => "1000-{$currentYear}"],
            [
                'created_by' => $employee1->id,
                'reference_no' => "1000-{$currentYear}",
                'status' => 'New',
                'client_id' => $client->id,
                'consignee_id' => $consignee->id,
                'merchant_id' => $merchant->id,
                'agent_id' => $agent->id,
                'carrier_id' => $carrier->id,
                'carrier_fees' => 500.00,
                'agent_fees' => 100.00,
                'price' => 2000.00,
                'payment' => 'Pending',
                'notes' => 'Sample reference for testing',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Sample Reference 2 (created by Emily Employee)
        Reference::updateOrCreate(
            ['reference_no' => "2000-{$currentYear}"],
            [
                'created_by' => $employee2->id,
                'reference_no' => "2000-{$currentYear}",
                'status' => 'Booked',
                'client_id' => $client->id,
                'consignee_id' => $consignee->id,
                'merchant_id' => $merchant->id,
                'agent_id' => $agent->id,
                'carrier_id' => $carrier->id,
                'carrier_fees' => 750.00,
                'agent_fees' => 150.00,
                'price' => 3000.00,
                'payment' => 'Pending',
                'notes' => 'Booked for shipment next week',
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ]
        );

        // Sample Reference 3 (created by David Employee)
        Reference::updateOrCreate(
            ['reference_no' => "4000-{$currentYear}"],
            [
                'created_by' => $employee3->id,
                'reference_no' => "4000-{$currentYear}",
                'status' => 'Shipped',
                'client_id' => $client->id,
                'consignee_id' => $consignee->id,
                'merchant_id' => $merchant->id,
                'agent_id' => $agent->id,
                'carrier_id' => $carrier->id,
                'carrier_fees' => 600.00,
                'agent_fees' => 120.00,
                'price' => 2500.00,
                'payment' => 'Paid',
                'notes' => 'Shipped via DHL',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(1),
            ]
        );

        // Sample Reference 4 (created by Admin)
        Reference::updateOrCreate(
            ['reference_no' => "5000-{$currentYear}"],
            [
                'created_by' => $adminUser->id,
                'reference_no' => "5000-{$currentYear}",
                'status' => 'Arrived',
                'client_id' => $client->id,
                'consignee_id' => $consignee->id,
                'merchant_id' => $merchant->id,
                'agent_id' => $agent->id,
                'carrier_id' => $carrier->id,
                'carrier_fees' => 800.00,
                'agent_fees' => 200.00,
                'price' => 3500.00,
                'payment' => 'Paid',
                'notes' => 'Shipment arrived at destination',
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(3),
            ]
        );

        // Sample Reference 5 (created by Supervisor)
        Reference::updateOrCreate(
            ['reference_no' => "3000-{$currentYear}"],
            [
                'created_by' => $mikeJohnson->id,
                'reference_no' => "3000-{$currentYear}",
                'status' => 'Released',
                'client_id' => $client->id,
                'consignee_id' => $consignee->id,
                'merchant_id' => $merchant->id,
                'agent_id' => $agent->id,
                'carrier_id' => $carrier->id,
                'carrier_fees' => 950.00,
                'agent_fees' => 250.00,
                'price' => 4000.00,
                'payment' => 'Paid',
                'notes' => 'Fully released by customs',
                'created_at' => now()->subDays(15),
                'updated_at' => now()->subDays(5),
            ]
        );
    }
}
