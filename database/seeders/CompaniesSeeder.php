<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompaniesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Company::create([
            'name' => 'CarrierGo Logistics Inc.',
            'address' => '123 Main Street',
            'zip_code' => '10001',
            'city' => 'New York',
            'logo' => null,
        ]);

        Company::create([
            'name' => 'Express Transport Ltd.',
            'address' => '456 Commerce Avenue',
            'zip_code' => '90001',
            'city' => 'Los Angeles',
            'logo' => null,
        ]);

        Company::create([
            'name' => 'Global Freight Solutions',
            'address' => '789 Industrial Drive',
            'zip_code' => '60601',
            'city' => 'Chicago',
            'logo' => null,
        ]);
    }
}
