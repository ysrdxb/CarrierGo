<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\Company;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the companies (assuming they have been seeded first)
        $company1 = Company::where('name', 'CarrierGo Logistics Inc.')->first();
        $company2 = Company::where('name', 'Express Transport Ltd.')->first();
        $company3 = Company::where('name', 'Global Freight Solutions')->first();

        // Settings for Company 1
        if ($company1) {
            Setting::create([
                'company_id' => $company1->id,
                'company_name' => 'CarrierGo Logistics Inc.',
                'address' => '123 Main Street',
                'zip_code' => '10001',
                'city' => 'New York',
                'logo' => null,
                'favicon' => null,
                'currency' => 'USD',
                'mail_from_name' => 'CarrierGo Support',
                'mail_from_address' => 'support@carriergo.com',
                'smtp_host' => 'smtp.mailtrap.io',
                'smtp_port' => '465',
                'smtp_username' => 'test_username',
                'smtp_password' => 'test_password',
            ]);
        }

        // Settings for Company 2
        if ($company2) {
            Setting::create([
                'company_id' => $company2->id,
                'company_name' => 'Express Transport Ltd.',
                'address' => '456 Commerce Avenue',
                'zip_code' => '90001',
                'city' => 'Los Angeles',
                'logo' => null,
                'favicon' => null,
                'currency' => 'USD',
                'mail_from_name' => 'Express Transport Support',
                'mail_from_address' => 'support@expresstransport.com',
                'smtp_host' => 'smtp.mailtrap.io',
                'smtp_port' => '465',
                'smtp_username' => 'test_username',
                'smtp_password' => 'test_password',
            ]);
        }

        // Settings for Company 3
        if ($company3) {
            Setting::create([
                'company_id' => $company3->id,
                'company_name' => 'Global Freight Solutions',
                'address' => '789 Industrial Drive',
                'zip_code' => '60601',
                'city' => 'Chicago',
                'logo' => null,
                'favicon' => null,
                'currency' => 'USD',
                'mail_from_name' => 'Global Freight Support',
                'mail_from_address' => 'support@globalfreight.com',
                'smtp_host' => 'smtp.mailtrap.io',
                'smtp_port' => '465',
                'smtp_username' => 'test_username',
                'smtp_password' => 'test_password',
            ]);
        }
    }
}
