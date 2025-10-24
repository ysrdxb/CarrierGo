<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\Setting;
use App\Models\Tenant;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create the default tenant
        $tenant = Tenant::first() ?? Tenant::create([
            'name' => 'Default Tenant',
            'domain' => 'default',
            'subscription_plan' => 'basic',
            'subscription_status' => 'active',
            'tenancy_mode' => 'SHARED',
        ]);

        // Get or create default company
        $company = Company::withoutGlobalScopes()->first() ?? Company::create([
            'tenant_id' => $tenant->id,
            'name' => 'CarrierGo',
            'address' => 'Example Street 1',
            'zip_code' => '12345',
            'city' => 'Example City',
            'logo' => null,
        ]);

        // Create default settings if they don't exist
        Setting::withoutGlobalScopes()->firstOrCreate(
            ['company_id' => $company->id],
            [
                'tenant_id' => $tenant->id,
                'company_name' => 'CarrierGo',
                'address' => 'Example Street 1',
                'zip_code' => '12345',
                'city' => 'Example City',
                'currency' => 'EUR',
                'mail_from_name' => 'CarrierGo',
                'mail_from_address' => 'info@carriergo.de',
            ]
        );
    }
}
